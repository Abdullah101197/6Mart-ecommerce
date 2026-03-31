<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $fillable = [
        'name',
        'is_veg',
        'module_id',
    ];
}

