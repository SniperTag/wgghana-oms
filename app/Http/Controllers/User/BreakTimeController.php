<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BreakTime;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Visitors;

class BreakTimeController extends Controller
{
   public function start(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        toastr()->error('User not authenticated.');
        return back();
    }

    // Check if there's an ongoing break
    $existingBreak = BreakTime::where('user_id', $user->id)
        ->whereNull('break_end')
        ->latest()
        ->first();

    if ($existingBreak) {
        toastr()->warning('You already have an active break.');
        return back();
    }

    $attendance = AttendanceRecord::where('user_id', $user->id)
        ->whereDate('created_at', today())
        ->latest()
        ->first();

    $break = BreakTime::create([
        'user_id' => $user->id,
        'attendance_id' => $attendance->id ?? null,
        'break_start' => now(),
        'break_reason' => $request->break_reason,
    ]);

    toastr()->success('Break started successfully.');
    return back();
}

}
