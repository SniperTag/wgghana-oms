<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VisitLog;
use Illuminate\Support\Facades\Auth;

class HostVisitApprovals extends Component
{
    public $pendingVisits;
    public $selectedVisitLogId;
    public $declineReason = '';

    protected $rules = [
        'declineReason' => 'required_if:action,decline|string|max:255',
    ];

    public function mount()
    {
        $this->loadPendingVisits();
    }

    public function loadPendingVisits()
    {
        $this->pendingVisits = VisitLog::with('visitor')
            ->where('host_id', Auth::id())
            ->where('status', 'pending')
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function selectVisit($visitLogId)
    {
        $this->selectedVisitLogId = $visitLogId;
        $this->declineReason = '';
    }

    public function approve()
    {
        $visit = VisitLog::find($this->selectedVisitLogId);
        if (!$visit) {
            session()->flash('error', 'Visit not found');
            return;
        }

        $visit->update([
            'status' => 'checked_in',
            'approval_status' => 'approved',
            'check_in_time' => now(),
            'checked_in_by' => Auth::id(),
        ]);

        session()->flash('message', "Visitor {$visit->visitor->full_name} check-in approved.");

        $this->selectedVisitLogId = null;
        $this->loadPendingVisits();
        $this->emit('visitApproved'); // optional event
    }

    public function decline()
    {
        $this->validate();

        $visit = VisitLog::find($this->selectedVisitLogId);
        if (!$visit) {
            session()->flash('error', 'Visit not found');
            return;
        }

        $visit->update([
            'status' => 'cancelled',
            'approval_status' => 'rejected',
            'rejection_reason' => $this->declineReason,
        ]);

        session()->flash('message', "Visitor {$visit->visitor->full_name} check-in declined.");

        $this->selectedVisitLogId = null;
        $this->declineReason = '';
        $this->loadPendingVisits();
        $this->emit('visitDeclined'); // optional event
    }

    public function render()
    {
        return view('livewire.host-visit-approvals');
    }
}
