<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Module extends Model
{
    protected $fillable = ['title', 'media_id', 'training_id', 'user_id'];

//    protected $hidden = [
//        'user_id',
//    ];

    public function media(): hasOne
    {
        return $this->hasOne(TrainingMedia::class, 'module_id');
    }
}
