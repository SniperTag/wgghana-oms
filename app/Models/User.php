<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, CanResetPassword;

    protected $fillable = [
        'name', 'email','gender', 'password', 'staff_id','clockin_pin',
        'pin_changed','password_changed', 'avatar','face_image','address',
        'department_id', 'supervisor_id','sub_role_id','role',
        'phone', 'corporate_email', 'personal_email', 'date_of_birth', 'nationality', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Boot hooks to handle staff_id auto-generation
     */
   protected static function booted()
{
    // Assign staff_id on creation
    static::creating(function ($user) {
        if (empty($user->staff_id)) {
            $user->staff_id = self::generateStaffId($user->department_id);
        }
    });

    // Log staff_id history on department transfer
    static::updating(function ($user) {
        if ($user->isDirty('department_id')) {
            $oldStaffId = $user->staff_id;
            $newStaffId = self::generateStaffId($user->department_id);

            // Log the change before assigning new ID
            if ($oldStaffId && $newStaffId !== $oldStaffId) {
                StaffIdHistory::create([
                    'user_id' => $user->id,
                    'old_staff_id' => $oldStaffId,
                    'new_staff_id' => $newStaffId,
                    'reason' => 'Department Transfer',
                ]);
            }

            $user->staff_id = $newStaffId;
        }
    });
}
    /**
     * Generate unique Staff ID with Department Code
     * Format: WG-{DEPTCODE}-{XXXX}-{YEAR}
     */
    protected static function generateStaffId($departmentId = null)
    {
        $prefix = 'GEN'; // default if no department

        if ($departmentId) {
            $department = Department::find($departmentId);
            if ($department && $department->code) {
                $prefix = strtoupper($department->code);
            } elseif ($department && $department->name) {
                $prefix = strtoupper(substr($department->name, 0, 3));
            }
        }

        do {
            $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $staffId = 'WG-' . $prefix . '-' . $randomNumber . '-' . date('Y');
        } while (User::where('staff_id', $staffId)->exists());

        return $staffId;
    }



    // ----------------------
    // Your existing relations
    // ----------------------

    public function emergencyContact() { return $this->hasOne(EmergencyContact::class); }
    public function idCards() { return $this->hasOne(IdCards::class); }
    public function employmentDetail() { return $this->hasOne(EmploymentDetail::class); }
    public function nextOfKin() { return $this->hasOne(NextOfKin::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function subRole() { return $this->belongsTo(SubRole::class, 'sub_role_id'); }
    public function leaveBalances() { return $this->hasMany(LeaveBalance::class); }
    public function visitors() { return $this->hasMany(Visitor::class, 'host_id'); }
    public function attendanceRecords() { return $this->hasMany(AttendanceRecord::class); }
    public function leaves() { return $this->hasMany(Leave::class); }
    public function events() { return $this->hasMany(Event::class); }
    public function hostedVisitors() { return $this->hasMany(Visitor::class, 'host_id'); }
    public function supervisor() { return $this->belongsTo(User::class, 'supervisor_id'); }
    public function subordinates() { return $this->hasMany(User::class, 'supervisor_id'); }
    public function meetings() { return $this->hasMany(Meeting::class); }
    public function departmentHeadOf() { return $this->hasMany(Department::class, 'head_id'); }
    public function breakSessions() { return $this->hasMany(BreakSession::class); }
    public function projects() { return $this->belongsToMany(Project::class); }
    public function tasks() { return $this->hasMany(Task::class); }

    // ----------------------
    // Helper methods
    // ----------------------

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getAllUsersWithPermissions()
    {
        return User::with('roles.permissions')->get()->map(function ($user) {
            $user->permissions = $user->getAllPermissions();
            return $user;
        });
    }

    public function getFullNameAttribute()
    {
        return "{$this->name} ({$this->email})";
    }

    public function needsPasswordChange()
    {
        return $this->password_changed === false;
    }

    public function hasActiveLeave() { return $this->leaves()->where('status', 'approved')->exists(); }
    public function hasPendingLeave() { return $this->leaves()->where('status', 'pending')->exists(); }
    public function hasRejectedLeave() { return $this->leaves()->where('status', 'rejected')->exists(); }

    public function getStatusNameAttribute() { return $this->staffStatus?->name ?? 'Unknown'; }
    public function getStatusColorAttribute() { return $this->staffStatus?->color ?? 'gray'; }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('images/default-avatar.png');
    }

    public function getFaceImageUrlAttribute()
    {
        return $this->face_image ? asset('storage/' . $this->face_image) : asset('images/default-face-image.png');
    }
}
