<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        "uuid",
        "type",
        "ticket_id",
        "size",
        "path"
    ];
}
