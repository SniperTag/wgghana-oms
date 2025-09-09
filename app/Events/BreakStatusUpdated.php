<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class BreakStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $staff_id;
    public $name;
    public $on_break;

    public function __construct($user, $onBreak)
    {
        $this->staff_id = $user->id;
        $this->name = $user->name;
        $this->on_break = $onBreak;
    }

    public function broadcastOn()
    {
        return new Channel('break-status');
    }
}
