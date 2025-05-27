<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\User\BreakTimeController;
use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\User\StaffController;
use App\Http\Controllers\User\LeaveController;
use App\Http\Controllers\User\SupervisorController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\LeaveBalanceController;

Route::get('/', function () {
    return view('welcome');
});


// ====================== PUBLIC ATTENDANCE ======================staff
Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('staff.checkout');
Route::post('/attendance', [AttendanceController::class, 'handleAttendance'])->name('staff.checkin');
Route::get('admin/attendance/verify/{staff_id}', [AttendanceController::class, 'lookupStaff'])->name('verify.staff');


// Authenticated Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ====================== USER ROUTES ======================
Route::middleware(['auth'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')->name('dashboard');

    Route::get('staff/staff-dashboard', [StaffController::class, 'staff'])
        ->middleware('role:staff')->name('staff.dashboard');

    Route::get('/manager/manager-dashboard', fn() => view('manager.dasboard'))
        ->middleware('role:manager')->name('manager.dashboard');

    Route::get('/finance/finance-dashboard', fn() => view('finance.dasboard'))
        ->middleware('role:finance')->name('finance.dashboard');

    Route::get('/supervisor/dashboard', [SupervisorController::class, 'supervisor'])
        ->middleware('role:supervisor')->name('supervisor.dashboard');
});



// ====================== ADMIN ROUTES ======================
Route::prefix('admin')->middleware(['auth', 'role:admin|hr'])->group(function () {
    // Dashboard

    // User Management
    Route::get('/users', [UserController::class, 'indexUser'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users/store', [UserController::class, 'storeUser'])->name('admin.store');
    Route::get('/users/edit/{user}', [UserController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('admin.update');
    Route::delete('/users/destroy/{user}', [UserController::class, 'destroyUser'])->name('admin.destroy');

    // User Invitation
    Route::get('/users/invite', [UserController::class, 'invite'])->name('admin.invite.user');
    Route::post('/users/invite', [UserController::class, 'inviteStore'])->name('admin.invite.store');

    // Registration via Invite
    Route::get('/register/{token}', [UserController::class, 'showRegistrationForm'])->name('admin.users.register.form');
    Route::post('/register', [UserController::class, 'register'])->name('admin.register.store');

    // Performance
    Route::get('/users/{id}/performance', [UserController::class, 'getUserPerformance'])->name('admin.users.performance');

    // Role Management
    Route::resource('roles', RoleController::class);

    // Assign Permissions to Role
    Route::get('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
    Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissionsStore'])->name('roles.assignPermissions.store');

    // Assign Users to Role
    Route::get('roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('roles.assignUsers');
    Route::post('roles/{role}/assign-users', [RoleController::class, 'assignUsersStore'])->name('roles.assignUsers.store');

    //edit Roles
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    //create Roles
    Route::get('/roles/index', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    //Rooute show
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');


    // Permission Management
    Route::get('/permissions/index', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/edit/{permission}', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/destroy/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    // View Permissions
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::get('users/{user}/permissions', [RoleController::class, 'userPermissions'])->name('users.permissions');
    Route::get('access/partials/roles', [RoleController::class, 'index'])->name('access.roles');


    // Direct User Access Control
    Route::get('access/management', [UserAccessController::class, 'index'])->name('access.management');
    Route::post('access/{user}/give-permission', [UserAccessController::class, 'givePermission'])->name('access.givePermission');
    Route::delete('access/{user}/revoke-permission/{permission}', [UserAccessController::class, 'revokePermission'])->name('access.revokePermission');

    //Leave Balance Management
    Route::get('users/{user}/leave_balances/create', [LeaveBalanceController::class, 'create'])->name('leave_balances.create');
    Route::post('users/{user}/leave_balances', [LeaveBalanceController::class, 'store'])->name('leave_balances.store');

    Route::get('/admin/leave-balances/{id}/edit-modal', [LeaveBalanceController::class, 'editModal'])->name('leave_balances.edit-modal');
    Route::put('leave_balances/{leaveBalance}', [LeaveBalanceController::class, 'update'])->name('leave_balances.update');

    // Attendance (for admin view)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/adminattendance', [DashboardController::class, 'adminAttendance'])->name('admin.attendance');

    // Break Time
    Route::post('/break/start', [BreakTimeController::class, 'start'])->name('staff.break.start');
    Route::post('/break/end', [BreakTimeController::class, 'end'])->name('staff.break.end');

    // Leave Management
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves/store', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::get('/leaves/{id}/show', [LeaveController::class, 'show'])->name('leaves.show');
    Route::post('/leave/hr-approve/{id}', [LeaveController::class, 'hrApprove'])->name('leave.hr-approve');

    Route::post('/leave/hr-reject/{id}', [LeaveController::class, 'hrReject'])->name('leave.hr-reject');
});

// ====================== SUPERVISOR ROUTES ======================
Route::prefix('supervisor')->middleware(['auth', 'role:supervisor'])->group(function () {
    // Dashboard
    Route::get('/attendance', [SupervisorController::class, 'attendance'])->name('supervisor.self.attendance');
    // Profile
    Route::get('/profile', [SupervisorController::class, 'profile'])->name('supervisor.profile');
    // Leave Management
    Route::get('/leaves', [SupervisorController::class, 'index'])->name('supervisor.leaves.index');
    Route::get('/leaves/create', [SupervisorController::class, 'create'])->name('supervisor.leaves.create');
    Route::post('/leaves/storesupervisorleave', [LeaveController::class, 'storesupervisorleave'])->name('supervisor.leaves.store');
    Route::get('/supervisor/leaves/{id}', [SupervisorController::class, 'show'])->name('supervisor.leaves.show');
    Route::get('/subordinates', [SupervisorController::class, 'subordinatesIndex'])->name('supervisor.subordinates.index');
    Route::get('/subordinates{id}', [SupervisorController::class, 'subordinatesShow'])->name('supervisor.subordinates.show');
    Route::post('/subordinates{id}/approve', [SupervisorController::class, 'approve'])->name('supervisor.subordinates.approve');
    Route::post('/subordinates{id}/reject', [SupervisorController::class, 'reject'])->name('supervisor.subordinates.reject');
});


// ====================== STAFF ROUTES ======================
Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function () {
    //Attendance
    Route::get('/attendance', [StaffController::class, 'attendance'])->name('self.attendance');
    // Break Time
    Route::post('/break/start', [BreakTimeController::class, 'start'])->name('staff.break.start');
    Route::post('/break/end', [BreakTimeController::class, 'end'])->name('staff.break.end');
    // Leave Management
    Route::get('/leaves/apply', [StaffController::class, 'apply'])->name('staff.leave.apply');
    Route::get('/leaves', [StaffController::class, 'index'])->name('staff.leaves.index');
    Route::post('/leaves/store', [LeaveController::class, 'store'])->name('leaves.store');
});




// ====================== AUTH ROUTES ======================
require __DIR__ . '/auth.php';
