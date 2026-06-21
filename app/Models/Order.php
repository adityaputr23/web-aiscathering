<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'user_email',
        'order_id',
        'customer_name',
        'customer_phone',
        'items_title',
        'items_subtitle',
        'items_json',
        'emoji',
        'total_price',
        'subtotal',
        'shipping_cost',
        'discount',
        'member_discount',
        'status',
        'shipping_address',
        'delivery_date',
        'cancel_reason',
        'payment_method',
        'payment_status',
        'fcm_token_user',
        'notified_admin',
        'notified_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
