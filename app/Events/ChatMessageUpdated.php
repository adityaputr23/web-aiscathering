<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $userEmail = $this->message->sender_type === 'ADMIN' 
            ? $this->message->receiver_email 
            : $this->message->sender_email;

        return [
            new Channel('chat.' . $userEmail),
            new Channel('chats')
        ];
    }

    public function broadcastAs()
    {
        return 'ChatMessageUpdated';
    }
}
