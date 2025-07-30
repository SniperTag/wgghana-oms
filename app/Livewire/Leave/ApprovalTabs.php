<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\Leave;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ApprovalTabs extends Component
{
    use WithPagination;

    public $activeTab = 'pending';

    protected $paginationTheme = 'tailwind';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function approve($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);
        $user = Auth::user();

        if ($leave->user_id == $user->id) {
            session()->flash('error', 'You cannot approve your own leave.');
            return;
        }

        if ($leave->supervisor_required && $leave->supervisor_status === 'pending') {
            if ($user->id !== $leave->supervisor_id) {
                session()->flash('error', 'Only assigned supervisor can approve.');
                return;
            }

            $leave->update([
                'supervisor_status' => 'approved',
                'supervisor_approved_at' => now(),
            ]);

            session()->flash('success', 'Approved as Supervisor.');
        } elseif ($user->hasRole(['admin', 'hr'])) {
            if ($leave->supervisor_required && $leave->supervisor_status !== 'approved') {
                session()->flash('warning', 'Supervisor must approve first.');
                return;
            }

            $leave->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            session()->flash('success', 'Leave approved by HR/Admin.');
        } else {
            session()->flash('error', 'Unauthorized to approve.');
        }
    }

    public function render()
    {
        return view('livewire.leave.approval-tabs', [
            'pendingLeaves' => Leave::where('status', 'pending')
                ->with('user', 'leaveType')
                ->latest()->paginate(5),
            'approvedLeaves' => Leave::where('status', 'approved')
                ->with('user', 'leaveType')
                ->latest()->paginate(5),
        ]);
    }
}

