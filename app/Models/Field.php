<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'form_id', 'type', 'label', 'placeholder', 'options', 'required', 'order',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}