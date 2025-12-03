<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;
    public $from; // "Admin" or "User"

    public function __construct($message, $from)
    {
        $this->message = $message;
        $this->from = $from;
    }

    // Broadcast to public chat-room
    public function broadcastOn()
    {
        return new Channel('chat-room');
    }

    // Use simple name for JS
    public function broadcastAs()
    {
        return 'ChatMessageEvent';
    }
}
