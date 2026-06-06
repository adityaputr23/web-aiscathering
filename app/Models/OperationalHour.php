<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalHour extends Model
{
    protected $fillable = [
        'day_index',
        'day_name',
        'open_time',
        'close_time',
        'is_closed'
    ];
}
