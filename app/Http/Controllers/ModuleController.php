<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\TrainingMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function update(Request $request, Module $module)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'media' => ['nullable', 'file', 'mimes:mp4,webm,mov,pdf', 'max:102400'],
        ]);
        $file = $request->file('media');
        $newPath = $file?->store('media', 'public');
        $oldPath = null;
        try {
            DB::transaction(function () use ($module, $data, $file, $newPath, &$oldPath) {
                if ($file) {
                    $media = TrainingMedia::findOrFail($module->media_id);
                    $oldPath = $media->path;
                    $media->update([
                        'path' => $newPath,
                        'type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'module_id' => $module->id,
                    ]);
                }
                $module->update(['title' => $data['title']]);
            });
        } catch (\Throwable $exception) {
            if ($newPath) Storage::disk('public')->delete($newPath);
            throw $exception;
        }
        if ($oldPath && $oldPath !== $newPath) Storage::disk('public')->delete($oldPath);

        if ($request->expectsJson()) {
            return response()->json(['url' => route('training.module_show', ['training' => $module->training_id, 'module' => $module->id])]);
        }

        return to_route('training.module_show', ['training' => $module->training_id, 'module' => $module->id]);
    }

    public function destroy(Request $request, Module $module)
    {
        abort_unless($request->user()->isAdmin(), 403);
        $trainingId = $module->training_id;
        $media = TrainingMedia::where('module_id', $module->id)->orWhere('id', $module->media_id)->get();
        DB::transaction(function () use ($module, $media) {
            $module->delete();
            TrainingMedia::whereIn('id', $media->modelKeys())->delete();
        });
        foreach ($media as $item) {
            Storage::disk('public')->delete($item->path);
        }

        return to_route('training.show', $trainingId);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'media' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,svg,pdf,mp4,webm,mov',
                'max:102400'
            ],
            'training_id' => 'required|exists:trainings,id'
        ]);


        $media = null;
        if ($request->hasFile('media')) {
            $filename = $data['media']->getClientOriginalName() . '_' . time() . '.' . $data['media']->getClientOriginalExtension();
            $path = $data['media']->storeAs('media', $filename, 'public');
            $media = TrainingMedia::create([
                'type' => $request->file('media')->getMimeType(),
                'path' => $path,
                'size' => $request->file('media')->getSize(),
            ]);
        }



        // dd($media);
        $module = Module::create([
            'title' => $data['title'],
            'media_id' => $media->id,
            'training_id' => $data['training_id'],
            'user_id' => auth()->user()->id
        ]);

        $media->module_id = $module->id;
        $media->save();
        return response()->json($module->makeHidden('user_id'));
    }
}
