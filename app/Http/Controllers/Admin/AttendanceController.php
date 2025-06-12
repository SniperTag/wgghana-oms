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
    public function handleAttendance(Request $request, FaceVerificationService $faceService, NotificationService $notificationService)
    {
        Log::info('📥 Attendance request started', $request->all());

        $validated = $request->validate([
            'staff_id' => 'required|string',
            'pin' => 'nullable|string',
            'action' => 'required|in:check_in,check_out,step_out,returned_time,start_break,end_break',
            'face_snapshot' => 'required_if:action,check_in|string|nullable',
            'notes' => 'nullable|string',
        ]);

        $validated['staff_id'] = trim($validated['staff_id']);
        $user = User::where('staff_id', $validated['staff_id'])->first();

        Log::info("🔍 Staff lookup for ID: {$validated['staff_id']}");

        if (!$user) {
            Log::warning("❌ Invalid Staff ID: {$validated['staff_id']}");
            toastr()->error('❌ Invalid Staff ID');
            return back();
        }

        // Require PIN for check_out
        if ($validated['action'] === 'check_out') {
            if (empty($validated['pin']) || !Hash::check($validated['pin'], $user->clockin_pin)) {
                Log::warning("❌ Invalid PIN for Staff ID: {$validated['staff_id']}");
                toastr()->error('❌ Invalid PIN');
                return back();
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
                    toastr()->error('❌ Already checked in today.');
                    return back();
                }

                if (!$user->face_image) {
                    toastr()->error('🧠 No face enrolled. Contact Admin.');
                    return back();
                }

                $match = $faceService->verifyFace($validated['face_snapshot'], $user->face_image);
                if (!$match) {
                    Log::warning("❌ Face mismatch for {$user->staff_id}");
                    toastr()->error('❌ Face did not match. Try again.');
                    return back();
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

                // ✅ Send clock-in notification
                $notificationService->sendClockInAlert($user, $checkInTime);

                Log::info("🟢 Checked in: {$user->name} at {$checkInTime}, IP: $ip, Device: $deviceInfo");
                toastr()->success('🟢 Checked in successfully.');
                break;

            case 'check_out':
                if (!$attendance->check_in_time) {
                    toastr()->error('❌ You must check in before checking out.');
                    return back();
                }

                if ($attendance->check_out_time) {
                    toastr()->error('❌ Already checked out today.');
                    return back();
                }

                $checkOutTime = now();
                $cutoffTime = now()->setTime(17, 30);

                if ($checkOutTime->lt($cutoffTime)) {
                    if (empty($validated['notes'])) {
                        toastr()->error('❌ Please provide a notes explaining early clock-out.');
                        return back();
                    }

                    Log::info("⏰ Early clock-out by {$user->name}. Notes: {$validated['notes']}");
                    $notificationService->sendEarlyCheckoutAlert($user, $checkOutTime->format('H:i:s'));
                }
                $attendance->update([
                    'check_out_time' => $checkOutTime->format('H:i:s'),
                    'notes' => $validated['notes'] ?? $attendance->notes,
                ]);

                // ✅ Send clock-out notification
                $notificationService->sendClockOutAlert($user, $checkOutTime->format('H:i:s'));

                Log::info("🔚 Checked out: {$user->name} at {$checkOutTime->format('H:i:s')}");
                toastr()->success('🔚 Checked out successfully.');
                break;

            case 'step_out':
                $attendance->update([
                    'step_out_time' => now()->format('H:i:s'),
                    'notes' => $validated['notes'] ?? $attendance->notes,
                ]);

                $this->broadcastStatus($user, "{$user->name} stepped out at " . now()->format('H:i'));
                toastr()->success('🚶 Stepped out recorded.');
                break;

            case 'returned_time':
                $attendance->update([
                    'returned_time' => now()->format('H:i:s'),
                    'notes' => $validated['notes'] ?? $attendance->notes,
                ]);

                $this->broadcastStatus($user, "{$user->name} returned at " . now()->format('H:i'));
                toastr()->success('✅ Returned recorded.');
                break;

            case 'start_break':
                $attendance->update([
                    'start_break_time' => now()->format('H:i:s'),
                    'notes' => $validated['notes'] ?? $attendance->notes,
                ]);

                $this->broadcastStatus($user, "{$user->name} started break at " . now()->format('H:i'));
                toastr()->success('🍔 Break started.');
                break;

            case 'end_break':
                $attendance->update([
                    'end_break_time' => now()->format('H:i:s'),
                    'notes' => $validated['notes'] ?? $attendance->notes,
                ]);

                $this->broadcastStatus($user, "{$user->name} ended break at " . now()->format('H:i'));
                toastr()->success('☕ Break ended.');
                break;

            default:
                toastr()->error('❌ Invalid action.');
                Log::error("❌ Unknown action attempted: {$validated['action']}");
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
