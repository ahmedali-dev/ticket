<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $fillable = [
        'title',
        'order',
        'user_id',
        'active',
        'media_id',
        'id',
    ];

    public function user():belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media():HasMany
    {
        return $this->hasMany(TrainingMedia::class, 'training_id');
    }

    public function module():hasMany
    {
        return $this->hasMany(Module::class);
    }
}
