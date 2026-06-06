<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'user_email',
        'items_title',
        'items_json',
        'total_price',
        'status',
        'shipping_address',
        'payment_method',
        'payment_status',
        'fcm_token_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
