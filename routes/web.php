<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    RoleController,
    UserController,
    DashboardController,
    AttendanceController,
    PermissionController,
    LeaveBalanceController,
    UserAccessController
};
use App\Http\Controllers\User\{
    StaffController,
    LeaveController,
    BreakTimeController,
    SupervisorController,
    FaceEnrollmentController
};
use App\Livewire\Admin\Dashboard;
use App\Livewire\Visitor\Registration;
use App\Livewire\Visitor\VisitorsDashboard;
use App\Livewire\Visitor\AppointmentBooking;
use App\Livewire\Visitor\AppointmentCheckin;



/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

// Public Attendance Routes
Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('staff.checkout');
Route::post('/attendance', [AttendanceController::class, 'handleAttendance'])->name('attendance.handle');
Route::get('admin/attendance/verify/{staff_id}', [AttendanceController::class, 'lookupStaff'])->name('verify.staff');

// Invite Registration
Route::get('auth/invite-register/{token}', [UserController::class, 'showRegistrationForm'])->name('invite.register');
Route::post('auth/invite-register', [UserController::class, 'processRegistration'])->name('invite.register.submit');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Face Enrollment
    Route::get('/user/face-enrollment', [FaceEnrollmentController::class, 'show'])->name('face.enroll');
    Route::post('/face-enrollment/save', [FaceEnrollmentController::class, 'saveFaceImage'])->name('face.enroll.save');

    // General Staff Leave Access
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
});

/*
|--------------------------------------------------------------------------
| Admin, HR, AND Receiptionist Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|admin|receptionist'])->group(function () {
    Route::get('/visitor/register', Registration::class)->name('visitor.register');
    Route::get('/visitor/visitors-dashboard', VisitorsDashboard::class)->name('visitors.dashboard');
Route::get('livewire/appointment-booking', AppointmentBooking::class)->name('appointment.booking');

Route::get('appointments/checkin/{appointment}', AppointmentCheckin::class)->name('appointments.checkin');
});

/*
|--------------------------------------------------------------------------
| HR & Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|hr|receptionist'])->group(function () {
    Route::get('/leaves/hr-pending', [LeaveController::class, 'hrPending'])->name('leaves.hr.pending');
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::get('/leaves/leave-status', [LeaveController::class, 'approvedLeaveStatus'])->name('leaves.status');
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Role-Based)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->group(function () {
    Route::get('admin/dashboard', Dashboard::class)->name('dashboard');

    Route::get('hr/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:hr')->name('hr.dashboard');

    Route::get('receptionist/dashboard', [DashboardController::class, 'receptionist'])
        ->middleware('role:receptionist')->name('receptionist.dashboard');

    Route::get('staff/staff-dashboard', [StaffController::class, 'staff'])
        ->middleware('role:staff')->name('staff.dashboard');

    Route::get('manager/manager-dashboard', fn () => view('manager.dashboard'))
        ->middleware('role:manager')->name('manager.dashboard');

    Route::get('finance/finance-dashboard', fn () => view('finance.dashboard'))
        ->middleware('role:finance')->name('finance.dashboard');

    Route::get('supervisor/dashboard', [SupervisorController::class, 'supervisor'])
        ->middleware('role:supervisor')->name('supervisor.dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:admin|hr'])->group(function () {

    // User Management
    Route::get('/users', [UserController::class, 'indexUser'])->name('admin.users_index');
    Route::get('/users/create', [UserController::class, 'createUser'])->name('admin.create_users');
    Route::post('/users/store', [UserController::class, 'storeUser'])->name('admin.store');
    Route::get('/users/{id}/id-card', [UserController::class, 'downloadIdCard'])->name('staff.id-card');
    Route::get('/admin/users/{id}/preview', [UserController::class, 'previewStaffID'])->name('admin.users.preview');
    Route::get('/users/edit/{user}', [UserController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('admin.update');
    Route::delete('/users/destroy/{user}', [UserController::class, 'destroyUser'])->name('admin.destroy_user');

    // User Invitation
    Route::get('/users/invite', [UserController::class, 'invite'])->name('admin.invite_user');
    Route::post('/users/invite', [UserController::class, 'inviteStore'])->name('admin.invite.store');
    Route::get('/users/{id}/performance', [UserController::class, 'getUserPerformance'])->name('admin.users.performance');

    // Role Management
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
    Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissionsStore'])->name('roles.assignPermissions.store');
    Route::get('roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('roles.assignUsers');
    Route::post('roles/{role}/assign-users', [RoleController::class, 'assignUsersStore'])->name('roles.assignUsers.store');

    // View/Edit Permissions
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/roles/index', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');

    // Permissions
    Route::get('/permissions/index', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/edit/{permission}', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/destroy/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Access Control
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::get('users/{user}/permissions', [RoleController::class, 'userPermissions'])->name('users.permissions');
    Route::get('access/partials/roles', [RoleController::class, 'index'])->name('access.roles');
    Route::get('access/management', [UserAccessController::class, 'index'])->name('access.management');
    Route::post('access/{user}/give-permission', [UserAccessController::class, 'givePermission'])->name('access.givePermission');
    Route::delete('access/{user}/revoke-permission/{permission}', [UserAccessController::class, 'revokePermission'])->name('access.revokePermission');

    // Leave Balances
    Route::get('/{user}/leave_balances/index', [LeaveBalanceController::class, 'index'])->name('leave_balances.index');
    Route::get('/leave_balances/create', [LeaveBalanceController::class, 'create'])->name('leave_balances.create');
    Route::post('/leave_balances', [LeaveBalanceController::class, 'store'])->name('leave_balances.store');
    Route::get('/{id}/edit-modal', [LeaveBalanceController::class, 'editModal'])->name('leave_balances.edit-modal');
    Route::put('/{leaveBalance}', [LeaveBalanceController::class, 'update'])->name('leave_balances.update');

    // Admin Attendance View
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/record', [DashboardController::class, 'adminAttendance'])->name('admin.attendance');

    // Break Time
    Route::post('/break/start', [BreakTimeController::class, 'start'])->name('staff.break.start');
    Route::post('/break/end', [BreakTimeController::class, 'end'])->name('staff.break.end');
});

/*
|--------------------------------------------------------------------------
| Supervisor-Only Routes
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')->middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/attendance', [SupervisorController::class, 'attendance'])->name('supervisor.self.attendance');
    Route::get('/profile', [SupervisorController::class, 'profile'])->name('supervisor.profile');
    Route::get('/leaves/supervisor-pending', [LeaveController::class, 'supervisorPending'])->name('leaves.supervisor.pending');
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::get('/leaves/supervisor/on-leave', [LeaveController::class, 'supervisorOnLeave'])->name('leaves.supervisor.onleave');
});

/*
|--------------------------------------------------------------------------
| Staff-Only Routes
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/attendance', [StaffController::class, 'attendance'])->name('self.attendance');
    Route::post('/break/start', [BreakTimeController::class, 'start'])->name('staff.break.start');
    Route::post('/break/end', [BreakTimeController::class, 'end'])->name('staff.break.end');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
