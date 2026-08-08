<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\TrainingMedia;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'media' => ['required',
                'file',
                'mimes:jpg,jpeg,png,gif,svg,pdf,mp4,webm,mov',
                'max:51200'],
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
