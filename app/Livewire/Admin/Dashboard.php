<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Department;
use App\Models\AttendanceRecord;
use App\Models\Leave;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Visitor;

class Dashboard extends Component
{
    public $userCount, $departmentCount, $attendanceCount, $leaveCount;
    public $supervisorPending, $hrPending, $approved, $rejected;
    public $user, $roles, $unreadCount, $todayAttendance, $upcomingLeaveCount, $onLeaveCount,$visitorCount;

    public function mount()
    {
        $this->user = Auth::user();
        $this->userCount = User::count();
        $this->departmentCount = Department::count();
        $this->attendanceCount = AttendanceRecord::count();
        $this->leaveCount = Leave::count();
        $this->visitorCount = Visitor::where('status', 'active')->count();
        $this->roles = Role::all();
        $this->todayAttendance = AttendanceRecord::whereDate('attendance_date', today())->get();
        $this->upcomingLeaveCount = Leave::whereDate('start_date', '>', today())->count();
        $this->onLeaveCount = Leave::whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->count();

        $this->supervisorPending = Leave::where('supervisor_id', $this->user->id)
            ->where('supervisor_status', 'Pending')
            ->count();

        $this->hrPending = Leave::where('supervisor_status', 'Approved')
            ->where('status', 'Waiting HR Approval')
            ->count();

        $this->approved = Leave::where('status', 'Approved')->count();
        $this->rejected = Leave::where('status', 'Rejected')->count();
        $this->unreadCount = $this->user->unreadNotifications->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.partials.admin');
            
           
    }
}
