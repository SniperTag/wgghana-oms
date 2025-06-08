<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $userCount = User::count();
        $departmentCount = Department::count();
        $attendanceCount = AttendanceRecord::count();
        $users = User::with('department')->get();
        $roles = Role::all();
        $leaveCount = Leave::count();
        $user = Auth::user();

        // Filter based on roles
        $supervisorPending = Leave::where('supervisor_id', $user->id)
            ->where('supervisor_status', 'Pending')
            ->count();

        $hrPending = Leave::where('supervisor_status', 'Approved')
            ->where('status', 'Waiting HR Approval')
            ->count();

        $approved = Leave::where('status', 'Approved')->count();
        $rejected = Leave::where('status', 'Rejected')->count();
        if (Auth::check()){
            $unreadCount = Auth::user()->unreadNotifications->count();
        }
        return view('admin.dashboard', compact('userCount', 'user', 'roles', 'departmentCount', 'attendanceCount', 'leaveCount', 'supervisorPending', 'hrPending', 'approved', 'rejected','unreadCount'));
    }

    public function createattendance()
    {
        return view('admin.attendance.create');
    }

    public function adminAttendance(Request $request)
    {
        $user = Auth::user(); // current admin
        $query = AttendanceRecord::where('user_id', $user->id);

        // Handle quick filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('attendance_date', today());
                    break;
                case 'this_week':
                    $query->whereBetween('attendance_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('attendance_date', now()->month)
                        ->whereYear('attendance_date', now()->year);
                    break;
            }
        }

        // Handle manual date range
        if ($request->has(['from', 'to']) && !$request->has('filter')) {
            $query->whereBetween('attendance_date', [$request->from, $request->to]);
        }
        $attendanceRecords = AttendanceRecord::get();
        $hasFilter = $request->has('filter') || ($request->has('from') && $request->has('to'));

        $records = $query->latest('attendance_date')->paginate(10);

        return view('admin.attendance.adminattendance', compact('records', 'user', 'attendanceRecords', 'hasFilter'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
