<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StepOut;
use App\Models\BreakSession;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StepOutHistory extends Component
{
    public function render()
    {
        $userId = Auth::id();

        // Fetch Step Out history
        $stepOutHistory = StepOut::where('user_id', $userId)
        ->whereDate('stepped_out_at', carbon::today())
            ->orderByDesc('stepped_out_at')
            ->paginate(3);

        // Fetch Break history
        $breakHistory = BreakSession::where('user_id', $userId)
            ->orderByDesc('started_at')
            ->paginate(3);

        return view('livewire.step-out-history', [
            'stepOutHistory' => $stepOutHistory,
            'breakHistory' => $breakHistory
        ]);
    }
}
