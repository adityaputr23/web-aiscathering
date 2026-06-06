<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chats';
    protected $fillable = ['sender_email', 'receiver_email', 'message', 'sender_type', 'is_read'];
    public $timestamps = false; // App chats table uses created_at default current_timestamp
}
