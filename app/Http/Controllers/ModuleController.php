<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function create()
    {
        return view("training.module.create");
    }
}
