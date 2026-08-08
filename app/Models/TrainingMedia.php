<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingMedia extends Model
{
    protected $fillable = [
        'type',
        'size',
        'path',
        'training_id',
        "module_id"
    ];
}
