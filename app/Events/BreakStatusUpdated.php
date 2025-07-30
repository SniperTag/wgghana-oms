<?php

// app/Events/BreakStatusUpdated.php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BreakStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;
    public $onBreak;

    public function __construct(User $user, bool $onBreak)
    {
        $this->user = $user;
        $this->onBreak = $onBreak;
    }

    public function broadcastOn()
    {
        return new Channel('break-status');
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'staff_id' => $this->user->staff_id,
            'on_break' => $this->onBreak,
        ];
    }
}
