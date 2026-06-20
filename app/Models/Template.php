<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'fields', 'category', 'icon', 'is_active'
    ];

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];
}