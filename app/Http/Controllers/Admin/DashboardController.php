<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Visitor;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function index()
    {
        $userCount = User::count();
        $departmentCount = Department::count();
        $attendanceCount = AttendanceRecord::count();
        //get all attendance Records for the day
        $todayAttendance = AttendanceRecord::whereDate('attendance_date', today())->get();
        $users = User::with('department')->get();
        $roles = Role::all();
        $leaveCount = Leave::count();
        $today = Carbon::today();

        $onLeaveCount = Leave::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $upcomingLeaveCount = Leave::whereDate('start_date', '>', $today)->count();

        $user = Auth::user();

        // Filter based on roles
        $supervisorPending = Leave::where('supervisor_id', $user->id)
            ->where('supervisor_status', 'Pending')
            ->count();

        $hrPending = Leave::where('supervisor_status', 'Approved')
            ->where('status', 'Waiting HR Approval')
            ->count();

            $visitorCount = Visitor::where('status', 'active')->count();

        $approved = Leave::where('status', 'Approved')->count();
        $rejected = Leave::where('status', 'Rejected')->count();
        if (Auth::check()) {
            $unreadCount = Auth::user()->unreadNotifications->count();
        }
        return view('admin.dashboard', compact(
            'userCount',
            'user',
            'roles',
            'departmentCount',
            'attendanceCount',
            'leaveCount',
            'supervisorPending',
            'hrPending',
            'approved',
            'rejected',
            'unreadCount',
            'todayAttendance',
            'upcomingLeaveCount',
            'onLeaveCount',
            'visitorCount'
        ));
    }

    public function createattendance()
    {
        return view('admin.attendance.create');
    }

    public function adminAttendance(Request $request)
    {
        $user = Auth::user();

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

        // paginate the filtered query
        $records = $query->latest('attendance_date')->paginate(10);

        $hasFilter = $request->has('filter') || ($request->has('from') && $request->has('to'));

        return view('admin.attendance.record', compact('records', 'user', 'hasFilter'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
