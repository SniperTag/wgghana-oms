<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\AttendanceRecord;
use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $userCount = user::count();
        $query = AttendanceRecord::with(['user.department']);

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $attendanceRecords = $query->latest()->paginate(10);
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $attendanceRecords = $query->latest()->paginate(10);
        } elseif ($user->hasRole('hr')) {
            $attendanceRecords = $query->whereHas('user', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            })->latest()->paginate(10);
        } else {
            $attendanceRecords = $query->where('user_id', $user->id)->latest()->paginate(10);
        }
        return view('admin.attendance.index', compact('attendanceRecords', 'userCount', 'user'));
    }

    public function myAttendance()
    {


        $user = Auth::user();
        $userCount = User::count();
        if ($user->hasRole('admin')) {
            $attendanceRecords = AttendanceRecord::with(['user.department'])->latest()->paginate(10);
        } elseif ($user->hasRole('hr')) {
            $attendanceRecords = AttendanceRecord::whereHas('user', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            })->latest()->paginate(10);
        } else {
        $attendanceRecords = AttendanceRecord::with('user.department')->where('user_id', $user->id)->orderBy('attendance_date', 'desc')->paginate(10);
        }
        return view('admin.attendance.myAtten',  compact('attendanceRecords', 'user', 'userCount'));
    }



    public function handleAttendance(Request $request)
    {
        // Step 1: Log request start
        Log::info('Attendance request started', $request->all());

        // Step 2: Validate request
        $validated = $request->validate([
            'staff_id' => 'required|string',
            'pin' => 'required|string',
            'action' => 'required|in:check_in,check_out',
        ]);

        // Step 3: Attempt to find the user
        $user = User::where('staff_id', $validated['staff_id'])->first();

        if (!$user) {
            Log::warning('Staff ID not found: ' . $validated['staff_id']);
            toastr()->error('❌ Invalid Staff ID or PIN');
            return back();
        }

        // Step 4: Verify clock-in PIN
        if (!Hash::check($validated['pin'], $user->clockin_pin)) {
            Log::warning("PIN verification failed for staff ID: " . $validated['staff_id']);
            toastr()->error('❌ Invalid Staff ID or PIN');
            return back();
        }

        $today = now()->toDateString();
        $now = now();
        $ip = $request->ip();

        $agent = new Agent();
        $platform = $agent->platform(); // e.g. Windows
        $browser = $agent->browser();   // e.g. Chrome
        $deviceInfo = "{$platform} - {$browser}";

        Log::info("User verified: {$user->name} ({$user->id}), IP: $ip, Device: $deviceInfo");

        // Step 5: Handle check-in
        if ($validated['action'] === 'check_in') {
            $existing = AttendanceRecord::where('user_id', $user->id)
                ->where('attendance_date', $today)
                ->first();

            if ($existing) {
                Log::info("Duplicate check-in attempt by user ID: {$user->id} on $today");
                toastr()->warning('⚠️ You have already checked in for the today.');
                return back();
            }

            // Time declarations
            $checkInTime = $now->format('H:i:s');
            $onTime = $now->copy()->setTime(8, 0, 0);
            $graceTime = $now->copy()->setTime(8, 30, 0);

            // Determine punctuality status
            if ($now->lessThanOrEqualTo($onTime)) {
                $punctuality = 'On Time'; // Green
            } elseif ($now->lessThanOrEqualTo($graceTime)) {
                $punctuality = 'Late'; // Amber
            } else {
                $punctuality = 'Very Late'; // Red
            }

            AttendanceRecord::create([
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'attendance_date' => $today,
                'check_in_time' => $checkInTime,
                'status' => $punctuality,
                'ip_address' => $ip,
                'device_info' => $deviceInfo,
            ]);
            session([
                'clocked_in_user_id' => $user->id,
                'clocked_in_staff_id' => $user->staff_id,
            ]); // Save to session // This is only applicable when you dont want to authenticate users before they clock ou
            Log::info("User checked in: {$user->id} at $checkInTime, Status: $punctuality");
            toastr()->success('✅ Checked in successfully!');
            return back();
        }

        // Step 6: Handle check-out
        if ($validated['action'] === 'check_out') {
            $attendance = AttendanceRecord::where('user_id', $user->id)
                ->where('attendance_date', $today)
                ->first();

            if (!$attendance) {
                Log::warning("Check-out attempted without check-in by user ID: {$user->id}");
                toastr()->error('⚠️ No check-in record found for today.');
                return back();
            }

            if ($attendance->check_out_time) {
                Log::info("Duplicate check-out attempt by user ID: {$user->id}");
                toastr()->warning('⚠️ You have already checked out for the today.');
                return back();
            }

            $checkOutTime = now()->format('H:i:s');

            $attendance->update([
                'check_out_time' => $checkOutTime,
                // 'status' => 'Checked Out',
            ]);

            Log::info("User checked out: {$user->id} at $checkOutTime");
            toastr()->success('✅ Checked out for the today successfully!');
            return back();
        }

        // Invalid action fallback
        Log::error("Invalid attendance action received: " . $validated['action']);
        toastr()->error('⚠️ Invalid attendance action.');
        return back();
    }

    public function lookupStaff($staff_id)
    {
        $staff_id = trim(strtolower($staff_id)); // normalize input
        Log::info("Lookup requested for Staff ID: $staff_id");

        $user = User::whereRaw('LOWER(staff_id) = ?', [$staff_id])
            ->with('department')
            ->first();

        if (!$user) {
            Log::warning("Staff lookup failed - not found: $staff_id");
            return response()->json([
                'success' => false,
                'message' => 'Staff ID not found'
            ], 404);
        }

        Log::info("Found user: {$user->name} ({$user->id})");

        return response()->json([
            'success' => true,
            'message' => "Name: {$user->name}\nDept: " . ($user->department->name ?? 'N/A'),
            'name' => $user->name,
            'department' => $user->department->name ?? 'N/A',
        ]);
    }

    // If you want to clock-out without been verified then activate this section

    // public function checkOut(Request $request)
    // {
    //     $userId = session('clocked_in_user_id');

    //     if (!$userId) {
    //         toastr()->error('⚠️ You must check in first before checking out.');
    //         return back();
    //     }

    //     $user = User::find($userId);
    //     if (!$user) {
    //         toastr()->error('⚠️ User not found.');
    //         return back();
    //     }

    //     $today = now()->toDateString();

    //     $attendance = AttendanceRecord::where('user_id', $user->id)
    //         ->where('attendance_date', $today)
    //         ->first();

    //     if (!$attendance) {
    //         toastr()->error('⚠️ No check-in record found for today.');
    //         return back();
    //     }

    //     if ($attendance->check_out_time) {
    //         toastr()->warning('⚠️ You have already checked out.');
    //         return back();
    //     }

    //     $attendance->update([
    //         'check_out_time' => now()->format('H:i:s'),
    //         'status' => 'Checked Out',
    //     ]);

    //     // Optionally clear the session so they must check-in again tomorrow
    //     session()->forget('clocked_in_user_id');

    //     toastr()->success('✅ Checked out successfully!');
    //     return back();
    // }

    // public function checkOut(Request $request)
    // {
    //     $request->validate([
    //         'pin' => 'required|string',
    //     ]);

    //     $userId = session('clocked_in_user_id');
    //     $staffId = session('clocked_in_staff_id');

    //     if (!$userId || !$staffId) {
    //         toastr()->error('⚠️ You must check in before checking out.');
    //         return back();
    //     }

    //     $user = User::where('id', $userId)
    //         ->where('staff_id', $staffId)
    //         ->first();

    //     if (!$user) {
    //         toastr()->error('⚠️ User session not valid.');
    //         return back();
    //     }

    //     if (!Hash::check($request->pin, $user->clockin_pin)) {
    //         toastr()->error('❌ Invalid PIN.');
    //         return back();
    //     }

    //     $today = now()->toDateString();
    //     $attendance = AttendanceRecord::where('user_id', $user->id)
    //         ->where('attendance_date', $today)
    //         ->first();

    //     if (!$attendance) {
    //         toastr()->error('⚠️ No check-in record found for today.');
    //         return back();
    //     }

    //     if ($attendance->check_out_time) {
    //         toastr()->warning('⚠️ You have already checked out.');
    //         return back();
    //     }
    //     //  this is to enforce 5 PM restriction
    //     $currentTime = now();
    //     $requiredTime = now()->setTime(17, 0, 0); // 5:00 PM

    //     if ($currentTime->lessThan($requiredTime)) {
    //         toastr()->warning('🕔 Sorry! You can only check out after 5:00 PM.');
    //         return back();
    //     }

    //     $attendance->update([
    //         'check_out_time' => now()->format('H:i:s'),
    //         // 'status' => 'Checked Out',
    //     ]);

    //     session()->forget(['clocked_in_user_id', 'clocked_in_staff_id']);

    //     toastr()->success('✅ Checked out successfully!');
    //     return back();
    // }

    public function checkOut(Request $request)
{
    $request->validate([
        'pin' => 'required|string',
        'check_out_reason' => 'nullable|string|min:5', // Reason is optional here, but enforced below
    ]);

    $userId = session('clocked_in_user_id');
    $staffId = session('clocked_in_staff_id');

    if (!$userId || !$staffId) {
        toastr()->error('⚠️ You must check in before checking out.');
        return back();
    }

    $user = User::where('id', $userId)->where('staff_id', $staffId)->first();

    if (!$user || !Hash::check($request->pin, $user->clockin_pin)) {
        toastr()->error('❌ Invalid session or PIN.');
        return back();
    }

    $today = now()->toDateString();
    $attendance = AttendanceRecord::where('user_id', $user->id)
        ->where('attendance_date', $today)
        ->first();

    if (!$attendance) {
        toastr()->error('⚠️ No check-in record found for today.');
        return back();
    }

    if ($attendance->check_out_time) {
        toastr()->warning('⚠️ You have already checked out.');
        return back();
    }

    $currentTime = now();
    $requiredTime = now()->setTime(17, 0, 0); // 5:00 PM

    if ($currentTime->lessThan($requiredTime)) {
        if (!$request->filled('check_out_reason')) {
            toastr()->warning('📝 You must provide a reason to check out before 5:00 PM.');
            return back();
        }

        $attendance->update([
            'check_out_time' => now()->format('H:i:s'),
            'check_out_reason' => $request->check_out_reason,
        ]);
    } else {
        $attendance->update([
            'check_out_time' => now()->format('H:i:s'),
        ]);
    }

    session()->forget(['clocked_in_user_id', 'clocked_in_staff_id']);

    toastr()->success('✅ Checked out successfully!');
    return back();
}

}
