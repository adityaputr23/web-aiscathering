<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $userEmail;

    public function __construct($messageId, $userEmail)
    {
        $this->messageId = $messageId;
        $this->userEmail = $userEmail;
    }

    public function broadcastOn()
    {
        return [
            new Channel('chat.' . $this->userEmail),
            new Channel('chats')
        ];
    }

    public function broadcastAs()
    {
        return 'ChatMessageDeleted';
    }
}
