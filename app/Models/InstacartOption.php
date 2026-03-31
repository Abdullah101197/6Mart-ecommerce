<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstacartOption extends Model
{
    protected $fillable = [
        'type',
        'name',
        'module_id',
    ];
}

