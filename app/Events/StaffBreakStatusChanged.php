<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class StaffBreakStatusChanged implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;
    public $status; // 'started' or 'ended'

    public function __construct(User $user, string $status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('staff-break-notifications');
    }

    public function broadcastAs(): string
    {
        return 'break.status';
    }

    public function broadcastWith(): array
    {
        return [
            'name' => $this->user->name,
            'status' => $this->status,
        ];
    }
}
