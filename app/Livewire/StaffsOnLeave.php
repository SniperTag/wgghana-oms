<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Leave;
use App\Models\Department;
use App\Models\LeaveType;

class StaffsOnLeave extends Component
{
    use WithPagination;

    public $department_id;
    public $leave_type_id;
    public $sort_days = 'asc';

    protected $updatesQueryString = ['department_id', 'leave_type_id', 'sort_days'];
    protected $listeners = ['refreshLeaves' => '$refresh'];

    public function updatingDepartmentId() { $this->resetPage(); }
    public function updatingLeaveTypeId() { $this->resetPage(); }
    public function updatingSortDays() { $this->resetPage(); }

    public function render()
    {
        $query = Leave::currentlyOnLeave()
            ->with(['user.roles', 'user.department', 'user.leaveBalance', 'leaveType']);

        if ($this->department_id) {
            $query->whereHas('user.department', fn($q) =>
                $q->where('id', $this->department_id));
        }

        if ($this->leave_type_id) {
            $query->where('leave_type_id', $this->leave_type_id);
        }

        if ($this->sort_days) {
            $query->join('leave_balances', 'leaves.user_id', '=', 'leave_balances.user_id')
                  ->orderBy('leave_balances.days_left', $this->sort_days)
                  ->select('leaves.*');
        }

        return view('livewire.staffs-on-leave', [
            'leaves' => $query->paginate(10),
            'departments' => Department::all(),
            'leaveTypes' => LeaveType::all()
        ]);
    }
}
