<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialPackage extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'badge',
        'features',
        'order',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
