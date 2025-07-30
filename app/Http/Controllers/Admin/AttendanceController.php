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
use App\Services\FaceVerificationService;
use App\Notifications\StaffStatusAlert;
use App\Services\NotificationService;
use App\Models\BreakSession;
use App\livewire\StepOutManager;



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

    // Determine if any filters were applied
    $hasFilter = $request->filled('name') || $request->filled('filter') || ($request->filled('from') && $request->filled('to'));

    // Name search
    if ($request->filled('name')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%');
        });
    }

    // Date filter (Today, This Week, This Month)
    if ($request->filled('filter')) {
        $now = now();

        switch ($request->filter) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;

            case 'this_week':
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                break;

            case 'this_month':
                $query->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()]);
                break;
        }
    }

    // Custom date range
    if ($request->filled('from') && $request->filled('to') && !$request->filled('filter')) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    // Role-based visibility
    if ($user->hasRole('admin')) {
        // View all
    } elseif ($user->hasRole('hr')) {
        $query->whereHas('user', function ($q) use ($user) {
            $q->where('department_id', $user->department_id);
        });
    } else {
        $query->where('user_id', $user->id);
    }

    $attendanceRecords = $query->latest()->paginate(10)->appends($request->query());

    $onBreak = BreakSession::where('user_id', $user->id)
        ->whereNull('ended_at')
        ->exists();

    return view('admin.attendance.index', compact(
        'attendanceRecords',
        'userCount',
        'user',
        'hasFilter',
        'onBreak'
    ));
}


    /**
     * View logged-in user's attendance based on role
     */
    public function myAttendance(Request $request)
{
    $user = Auth::user();
    $userCount = User::count();

    $query = AttendanceRecord::with(['user.department']);

    // Date filter
    if ($request->filled('filter')) {
        $now = now();

        switch ($request->filter) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;

            case 'this_week':
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                break;

            case 'this_month':
                $query->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()]);
                break;
        }
    }

    // Custom date range
    if ($request->filled('from') && $request->filled('to') && !$request->filled('filter')) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    // Role-based filtering
    if ($user->hasRole('admin')) {
        // all
    } elseif ($user->hasRole('hr')) {
        $query->whereHas('user', function ($q) use ($user) {
            $q->where('department_id', $user->department_id);
        });
    } else {
        $query->where('user_id', $user->id);
    }

    $attendanceRecords = $query->latest('attendance_date')->paginate(10)->appends($request->query());

    // Additional checks
    $onBreak = BreakSession::where('user_id', $user->id)->whereNull('ended_at')->exists();

    $currentlySteppedOut = StepOutManager::where('user_id', $user->id)->whereNull('returned_at')->exists();

    $hasClockedIn = AttendanceRecord::where('user_id', $user->id)
        ->whereDate('attendance_date', today())
        ->whereNotNull('check_in_time')
        ->exists();

    return view('admin.attendance.record', compact(
        'attendanceRecords',
        'user',
        'userCount',
        'onBreak',
        'currentlySteppedOut',
        'hasClockedIn'
    ));
}


    /**
     * Handle check-in/check-out/step-out/return actions
     */
    public function handleAttendance(Request $request, FaceVerificationService $faceService, NotificationService $notificationService)
    {
        Log::info('Attendance Request Payload:', $request->all());

        $validated = $request->validate([
            'staff_id' => 'required|string',
            'clockin_pin' => 'nullable|string',
            'action' => 'required|in:check_in,check_out,step_out_time,returned_time,start_break,end_break',
            'face_snapshot' => 'required_if:action,check_in|string|nullable',
            'notes' => 'nullable|string',
        ]);

        $validated['staff_id'] = trim($validated['staff_id']);
        $user = User::where('staff_id', $validated['staff_id'])->first();

        if (!$user) {
            Log::warning("❌ Invalid Staff ID: {$validated['staff_id']}");
            return $this->response($request, false, '❌ Invalid Staff ID');
        }

        // Require PIN for check_out
        if ($validated['action'] === 'check_out') {
            if (empty($validated['clockin_pin']) || !Hash::check($validated['clockin_pin'], $user->clockin_pin)) {
                Log::warning("❌ Invalid PIN for Staff ID: {$validated['staff_id']}");
                return $this->response($request, false, '❌ Invalid PIN');
            }
        }

        $today = now()->toDateString();
        $attendance = AttendanceRecord::firstOrCreate(
            ['user_id' => $user->id, 'attendance_date' => $today],
            ['status' => 'Not Checked In']
        );

        switch ($validated['action']) {
            case 'check_in':
                if ($attendance->check_in_time) {
                    return $this->response($request, false, '❌ Already checked in today.');
                }

                if (!$user->face_image) {
                    return $this->response($request, false, '🧠 No face enrolled. Contact Admin.');
                }

                $match = $faceService->verifyFace($validated['face_snapshot'], $user->face_image);
                if (!$match) {
                    Log::warning("❌ Face mismatch for {$user->staff_id}");
                    return $this->response($request, false, '❌ Face did not match. Try again.');
                }

                $ip = $request->ip();
                $agent = new Agent();
                $deviceInfo = $agent->device() . ' - ' . $agent->platform() . ' - ' . $agent->browser();

                $checkInTime = now()->format('H:i:s');
                $status = match (true) {
                    $checkInTime <= '09:00:00' => 'On Time',
                    $checkInTime <= '09:30:00' => 'Late',
                    default => 'Very Late',
                };

                $attendance->update([
                    'check_in_time' => $checkInTime,
                    'status' => $status,
                    'ip_address' => $ip,
                    'device_info' => $deviceInfo,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $notificationService->sendClockInAlert($user, $checkInTime);
                Log::info("🟢 Checked in: {$user->name} at {$checkInTime}, IP: $ip, Device: $deviceInfo");

                return $this->response($request, true, '🟢 Welcome To Work, ' . auth::user()->name . '. Enjoy your day!.');

            case 'check_out':
                try {
                    if (!$attendance->check_in_time) {
                        return $this->response($request, false, '❌ You must check in before checking out.');
                    }

                    if ($attendance->check_out_time) {
                        return $this->response($request, false, '❌ Already checked out today.');
                    }

                    $checkOutTime = now();
                    $cutoffTime = now()->setTime(17, 30);

                    if ($checkOutTime->lt($cutoffTime)) {
                        if (empty($validated['notes'])) {
                            return $this->response($request, false, '❌ Please provide a note explaining early clock-out.');
                        }
                        Log::info("⏰ Early clock-out by {$user->name}. Notes: {$validated['notes']}");
                        $notificationService->sendEarlyCheckoutAlert($user, $checkOutTime->format('H:i:s'));
                    }

                    $attendance->update([
                        'check_out_time' => $checkOutTime->format('H:i:s'),
                        'notes' => $validated['notes'] ?? $attendance->notes,
                    ]);

                    $notificationService->sendClockOutAlert($user, $checkOutTime->format('H:i:s'));
                    Log::info("🔚 Checked out: {$user->name} at {$checkOutTime->format('H:i:s')}");

                    return $this->response($request, true, '🔚 Done for the Day,' . auth::user()->name . '.Have a Good night.');

                } catch (\Exception $e) {
                    Log::error("Checkout error for {$user->staff_id}: " . $e->getMessage());
                    return $this->response($request, false, '❌ Unexpected error occurred. ' . $e->getMessage());
                }


            // Add other cases here...

            default:
                return $this->response($request, false, '❌ Invalid action.');
        }
    }

    // Helper method to unify JSON or redirect response with flash
    protected function response(Request $request, bool $success, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }

        // Use toastr or session flash for normal requests
        if ($success) {
            toastr()->success($message);
        } else {
            toastr()->error($message);
        }

        return back();
    }



    /**
     * Send real-time notification using Laravel Notifications
     */
    protected function broadcastStatus(User $user, string $message): void
    {
        User::where('id', '!=', $user->id)->get()->each(function ($recipient) use ($message) {
            $recipient->notify(new StaffStatusAlert($message));
        });

        Log::info("📢 Status update broadcast: {$message}");
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
