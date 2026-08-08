<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Training;
use App\Models\TrainingMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $validated = request()->validate([
            'search' => 'nullable|string',
        ]);
        $isAdmin = auth()->user()->type == 'admin';
        $trainings = Training::query();
        if (!$isAdmin) {
            $trainings = $trainings->where('active', true);
        }
        if (isset($validated['search'])) {
            $trainings = $trainings->where('title', 'like', '%' . $validated['search'] . '%');
        }

        $trainings = $trainings->latest()->get();
//        dd($trainings);

        return view('training.index', ['trainings' => $trainings, 'isAdmin' => $isAdmin]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('training.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function formatFileSize(int|float $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes <= 0) {
            return '0 B';
        }

        $factor = floor(log($bytes, 1024));

        return sprintf(
            "%.{$decimals}f %s",
            $bytes / (1024 ** $factor),
            $units[$factor]
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6048'
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['id'] = Training::count() + 1;
        Training::create($data);
        if ($request->hasFile('image')) {

            $data['media_id'] = Str::orderedUuid();
            $filename = Str::slug($data['title']) . $data['media_id'] . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('training', $filename, 'public');
            TrainingMedia::create([
                "type" => $request->file('image')->getMimeType(),
                "size" => $this->formatFileSize($request->file('image')->getSize(), 2),
                "path" => $path,
                "training_id" => $data['id'],
                "module_id" => null
            ]);
        }
//        dd($data);

        return to_route('training.index')->with('success', 'Training created!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        if (auth()->user()->type == 'user' && !$training->active) {
            return to_route('training.index')->with('error', 'You are not allowed to access this training!');
        }
        $module = $training->module->find(1);
        return view('training.show', ['training' => $training, 'module' => $module]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
