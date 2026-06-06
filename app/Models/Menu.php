<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'image_url',
        'is_featured',
        'emoji',
        'rating',
        'sold',
        'is_available',
        'review_count',
    ];

    protected $casts = [
        'price'        => 'integer',
        'rating'       => 'float',
        'sold'         => 'integer',
        'is_featured'  => 'boolean',
        'is_available' => 'boolean',
    ];
}
