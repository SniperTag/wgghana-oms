<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use App\Models\VisitLog;
use Illuminate\Support\Facades\Auth;

class HostCheckinApprovals extends Component
{
    public $approvalNote = '';
    public $rejectReason = '';

    public function render()
    {
        $logs = VisitLog::where('host_id', Auth::id())
            ->where('approval_status', 'pending')
            ->with('visitor')
            ->get();

        return view('livewire.host-approvals', compact('logs'));
    }

    public function approve($logId)
    {
        $log = VisitLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'approved',
            'status' => 'checked_in',
        ]);

        session()->flash('success', 'Visitor approved successfully.');
    }

    public function reject($logId)
    {
        $log = VisitLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'rejected',
            'status' => 'cancelled',
            'rejection_reason' => $this->rejectReason,
        ]);

        $this->rejectReason = '';

        session()->flash('error', 'Visitor rejected.');
    }
}
