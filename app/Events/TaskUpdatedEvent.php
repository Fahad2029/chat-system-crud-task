<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $task;
    public $action;

    public function __construct($task, $action)
    {
        $this->task = $task;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('tasks');
    }

    public function broadcastAs()
    {
        return 'TaskUpdatedEvent';
    }
}
