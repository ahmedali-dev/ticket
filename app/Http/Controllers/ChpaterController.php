<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class ChpaterController extends Controller
{
    public function store(request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'module_id' => 'required|exists:modules,id',
            'date' => 'nullable|string|max:20|regex:/^\d{1,3}:\d{1,2}$/',
        ]);

        $validated['user_id'] = auth()->id();
        $chapter = Chapter::create($validated);
//        dd($chapter);
        response()->json(['chapter' => $validated]);
    }
}
