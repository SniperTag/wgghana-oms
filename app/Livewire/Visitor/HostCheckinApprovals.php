<?php

namespace App\Livewire\Visitor;

use Livewire\Component;
use App\Models\VisitLog;
use Illuminate\Support\Facades\Auth;

class HostCheckinApprovals extends Component
{
    public $approvalNote = '';
    public $rejectReason = '';
    public $visitLogs;

    public function mount()
    {
        $this->loadVisitLogs();
    }

    public function loadVisitLogs()
    {
        $this->visitLogs = VisitLog::where('host_id', Auth::id())
            ->where('approval_status', 'pending')
            ->with('visitor')
            ->latest()
            ->get();
    }

    public function approve($logId)
    {
        $log = VisitLog::findOrFail($logId);

        $log->update([
            'approval_status' => 'approved',
            'status' => 'checked_in',
        ]);

        $this->visitLogs = $this->visitLogs->filter(fn($v) => $v->id !== $logId);

        // Use Livewire dispatch
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Visitor approved successfully.'
        ]);
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

        $this->visitLogs = $this->visitLogs->filter(fn($v) => $v->id !== $logId);

        $this->dispatch('toast', [
            'type' => 'error',
            'message' => 'Visitor rejected.'
        ]);
    }

    public function render()
    {
        return view('livewire.visitor.host-checkin-approvals');
    }
}
