<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Events\StaffStepEvent;
use App\Notifications\StaffStatusAlert;

class AttendanceController extends Controller
{
    /**
     * Show attendance records filtered by role and search
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userCount = User::count();

        $query = AttendanceRecord::with(['user.department']);

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($user->hasRole('admin')) {
            // All records
        } elseif ($user->hasRole('hr')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        $attendanceRecords = $query->latest()->paginate(10);

        return view('admin.attendance.index', compact('attendanceRecords', 'userCount', 'user'));
    }

    /**
     * View logged-in user's attendance based on role
     */
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
            $attendanceRecords = AttendanceRecord::with('user.department')
                ->where('user_id', $user->id)
                ->latest('attendance_date')
                ->paginate(10);
        }

        return view('admin.attendance.myAtten', compact('attendanceRecords', 'user', 'userCount'));
    }

    /**
     * Handle check-in/check-out/step-out/return actions
     */
    public function handleAttendance(Request $request)
    {
        Log::info('📥 Attendance request started', $request->all());

        $validated = $request->validate([
            'staff_id' => 'required|string',
            'pin' => 'required|string',
            'action' => 'required|in:check_in,check_out,step_out,returned_time,start_break,end_break',
            'face_snapshot' => 'nullable|string', // only used for check-in
            'notes' => 'nullable|string',
        ]);

        $user = User::where('staff_id', $validated['staff_id'])->first();

        if (!$user || !Hash::check($validated['pin'], $user->clockin_pin)) {
            Log::warning("❌ Invalid credentials for Staff ID: {$validated['staff_id']}");
            toastr()->error('❌ Invalid Staff ID or PIN');
            return back();
        }

        $today = now()->toDateString();
        $attendance = AttendanceRecord::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

      switch ($validated['action']) {
    case 'step_out':
        if (!$attendance) {
            toastr()->error('❌ Check-in required before stepping out.');
            return back();
        }

        $attendance->update([
            'step_out_time' => now()->format('H:i:s'),
            'notes' => $validated['notes'] ?? $attendance->notes,
        ]);

        $message = "{$user->name} stepped out at " . now()->format('H:i');
        User::where('id', '!=', $user->id)->get()->each(function ($recipient) use ($message) {
            $recipient->notify(new StaffStatusAlert($message));
        });

        Log::info("🚶 Step-out recorded and alert sent for user ID {$user->id}");
        toastr()->success('🚶 Stepped out recorded.');
        break;

    case 'returned_time':
        if (!$attendance) {
            toastr()->error('❌ Check-in required before returning.');
            return back();
        }

        $attendance->update([
            'returned_time' => now()->format('H:i:s'),
            'notes' => $validated['notes'] ?? $attendance->notes,
        ]);

        $message = "{$user->name} returned at " . now()->format('H:i');
        User::where('id', '!=', $user->id)->get()->each(function ($recipient) use ($message) {
            $recipient->notify(new StaffStatusAlert($message));
        });

        Log::info("✅ Return recorded and alert sent for user ID {$user->id}");
        toastr()->success('✅ Returned successfully.');
        break;

    case 'start_break':
        if (!$attendance) {
            toastr()->error('❌ Check-in required before starting break.');
            return back();
        }

        $attendance->update([
            'start_break_time' => now()->format('H:i:s'),
            'notes' => $validated['notes'] ?? $attendance->notes,
        ]);

        $message = "{$user->name} started break at " . now()->format('H:i');
        User::where('id', '!=', $user->id)->get()->each(function ($recipient) use ($message) {
            $recipient->notify(new StaffStatusAlert($message));
        });

        Log::info("🍔 Break started and alert sent for user ID {$user->id}");
        toastr()->success('🍔 Break started.');
        break;

    case 'end_break':
        if (!$attendance) {
            toastr()->error('❌ Check-in required before ending break.');
            return back();
        }

        $attendance->update([
            'end_break_time' => now()->format('H:i:s'),
            'notes' => $validated['notes'] ?? $attendance->notes,
        ]);

        $message = "{$user->name} ended break at " . now()->format('H:i');
        User::where('id', '!=', $user->id)->get()->each(function ($recipient) use ($message) {
            $recipient->notify(new StaffStatusAlert($message));
        });

        Log::info("☕ Break ended and alert sent for user ID {$user->id}");
        toastr()->success('☕ Break ended.');
        break;
}

        return back();
    }


    /**
     * Lookup staff by Staff ID
     */
    public function lookupStaff($staff_id)
    {
        $staff_id = trim(strtolower($staff_id));
        Log::info("Lookup requested for Staff ID: $staff_id");

        $user = User::whereRaw('LOWER(staff_id) = ?', [$staff_id])
            ->with('department')
            ->first();

        if (!$user) {
            Log::warning("❌ Staff ID not found: $staff_id");
            return response()->json(['success' => false, 'message' => 'Staff ID not found'], 404);
        }

        return response()->json([
            'success' => true,
            'name' => $user->name,
            'department' => $user->department->name ?? 'N/A',
            'message' => "Name: {$user->name}\nDept: " . ($user->department->name ?? 'N/A'),
        ]);
    }

    /**
     * Process base64 face snapshot and save to storage
     */
    private function processFaceSnapshot(?string $image, int $userId): ?string
    {
        if (!$image || !Str::startsWith($image, 'data:image')) {
            Log::warning('⚠️ Invalid or missing face snapshot');
            return null;
        }

        try {
            list($type, $data) = explode(';', $image);
            list(, $data) = explode(',', $data);
            $decoded = base64_decode($data);

            $fileName = "face_snapshot_{$userId}_" . time() . '.png';
            $path = "face_snapshots/{$fileName}";
            Storage::disk('public')->put($path, $decoded);

            Log::info("✅ Face snapshot saved for user ID $userId");
            return $path;
        } catch (\Exception $e) {
            Log::error("❌ Failed to save face snapshot: " . $e->getMessage());
            return null;
        }
    }
}
