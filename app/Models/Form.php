<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Form extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'is_published', 'shareable_link',
    ];

    protected static function booted()
    {
        static::creating(function ($form) {
            $form->shareable_link = Str::random(12);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class)->orderBy('order');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}