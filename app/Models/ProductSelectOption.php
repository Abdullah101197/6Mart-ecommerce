<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSelectOption extends Model
{
    protected $fillable = [
        'type',
        'name',
        'value',
        'module_id',
    ];
}

