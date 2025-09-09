<?php

use Illuminate\Support\Facades\Route;
use App\Models\SubRole;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    RoleController,
    UserController,
    DashboardController,
    AttendanceController,
    PermissionController,
    LeaveBalanceController,
    UserAccessController,
    SuperAdminController,
    AssessmentController,
    ProjectController,

};
use App\Http\Controllers\User\{
    StaffController,
    LeaveController,
    BreakTimeController,
    SupervisorController,
    FaceEnrollmentController
};
use App\Livewire\Visitor\{
    VisitorsDashboard,
    Registration,
    BookAppointment,
    HostAppointments,
    ManageVisitors
};

use App\Livewire\Admin\{
    Dashboard,

};

use App\Livewire\SuperAdmin\{
    Page,
};

// use App\Livewire\{
//     FaceEnrollment,
//     HostVisitApprovals,
//     StaffsOnLoeave,
//     StepOutHistory,
// };

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

// Route::get('/text', fn () => view('text'));
// Public Attendance Routes
Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('staff.checkout');
Route::post('/attendance', [AttendanceController::class, 'handleAttendance'])->name('attendance.handle');
Route::get('admin/attendance/verify/{staff_id}', [AttendanceController::class, 'lookupStaff'])->name('verify.staff');

// Invite Registration
Route::get('auth/invite-register/{token}', [UserController::class, 'showRegistrationForm'])->name('invite.register');
Route::post('auth/invite-register', [UserController::class, 'processRegistration'])->name('invite.register.submit');

// Visitor Public Booking
Route::get('/book-appointment', BookAppointment::class)->name('book.appointment');


Route::get('/subroles-by-department/{department}', [UserController::class, 'byDepartment'])
    ->name('subroles.byDepartment');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Logged-in Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Face Enrollment
    Route::get('/user/face-enrollment', [FaceEnrollmentController::class, 'show'])->name('face.enroll');
    Route::post('/face-enrollment/save', [FaceEnrollmentController::class, 'saveFaceImage'])->name('face.enroll.save');

    // Staff Leave Routes
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // Host Appointments
    Route::get('visitor/host-appointments', HostAppointments::class)->name('my.appointments');
});

/*
|--------------------------------------------------------------------------
| Visitor Routes (Admin & Receptionist)
|--------------------------------------------------------------------------
*/
Route::prefix('visitor')->middleware(['auth', 'role:super_admin|admin|receptionist'])->group(function () {
    Route::get('/dashboard', VisitorsDashboard::class)->name('visitor.dashboard');
    Route::get('/register', Registration::class)->name('visitor.registration');
    Route::get('/manage', ManageVisitors::class)->name('manage.visitors');
});



/*
|--------------------------------------------------------------------------
| Admin, HR, and Manager Routes
|--------------------------------------------------------------------------
*/



Route::middleware(['auth', 'role:super_admin|admin|manager'])->group(function () {
    // Admin Dashboard
    Route::get('admin/dashboard', Dashboard::class)->name('dashboard');
     Route::get('super-admin/page', Page::class)->name('super_admin.page');
    // Users
    Route::get('admin/users', [UserController::class, 'indexUser'])->name('all.staffs');
    Route::get('admin/users/create', [UserController::class, 'createUser'])->name('admin.create_users');
    Route::post('admin/users/store', [UserController::class, 'storeUser'])->name('admin.users.store');
    Route::get('admin/users/{id}/id-card', [UserController::class, 'downloadIdCard'])->name('staff.id-card');
    Route::get('admin/users/{id}/preview', [UserController::class, 'previewStaffID'])->name('admin.users.preview');
    Route::get('admin/users/edit/{user}', [UserController::class, 'editUser'])->name('admin.users.edit');
    Route::put('admin/users/{id}', [UserController::class, 'updateUser'])->name('admin.update_user');
    Route::delete('admin/users/destroy/{user}', [UserController::class, 'destroyUser'])->name('admin.destroy_user');


    // Role & Permissions
    Route::resource('admin/roles', RoleController::class);
    Route::get('admin/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
    Route::post('admin/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissionsStore'])->name('roles.assignPermissions.store');
    Route::get('admin/roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('roles.assignUsers');
    Route::post('admin/roles/{role}/assign-users', [RoleController::class, 'assignUsersStore'])->name('roles.assignUsers.store');

    // Permissions
    Route::resource('admin/permissions', PermissionController::class)->except(['show']);

    // Access Control
    Route::get('admin/access/management', [UserAccessController::class, 'index'])->name('access.management');
    Route::post('admin/access/{user}/give-permission', [UserAccessController::class, 'givePermission'])->name('access.givePermission');
    Route::delete('admin/access/{user}/revoke-permission/{permission}', [UserAccessController::class, 'revokePermission'])->name('access.revokePermission');
    Route::post('admin/access/revoke-multiple', [UserAccessController::class, 'revokeMultiplePermissions'])->name('access.revokeMultiplePermissions');
    Route::delete('admin/access/{user}/revoke-all', [UserAccessController::class, 'revokeAllPermissions'])->name('access.revokeAllPermissions');


    // Leave Management (HR/Admin)
    Route::get('leaves/hr-pending', [LeaveController::class, 'hrPending'])->name('leaves.hr.pending');
    Route::post('leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::get('leaves/leave-status', [LeaveController::class, 'approvedLeaveStatus'])->name('leaves.status');

    // Leave Balance
    Route::get('admin/leave_balances', [LeaveBalanceController::class, 'index'])->name('leave_balances.index');
    Route::get('admin/leave_balances/create', [LeaveBalanceController::class, 'create'])->name('leave_balances.create');
    Route::post('admin/leave_balances', [LeaveBalanceController::class, 'store'])->name('leave_balances.store');
    Route::put('admin/leave_balances/{leaveBalance}', [LeaveBalanceController::class, 'update'])->name('leave_balances.update');

    // Attendance Overview
    Route::get('admin/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('admin/attendance/record', [DashboardController::class, 'adminAttendance'])->name('admin.attendance');

    // Break Time
    Route::post('admin/break/start', [BreakTimeController::class, 'start'])->name('staff.break.start');
    Route::post('admin/break/end', [BreakTimeController::class, 'end'])->name('staff.break.end');
});

/*
|--------------------------------------------------------------------------
| Supervisor Routes
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')->middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::get('/attendance', [SupervisorController::class, 'attendance'])->name('supervisor.self.attendance');
    Route::get('subordinates', [SupervisorController::class, 'subordinatesIndex'])->name('subordinates.index');
    Route::get('subordinates/{id}', [SupervisorController::class, 'subordinatesShow'])->name('subordinates.show');
    Route::get('/leaves/pending', [LeaveController::class, 'supervisorPending'])->name('leaves.supervisor.pending');
    Route::post('/leaves/{id}/approve', [SupervisorController::class, 'approve'])->name('supervisor.approve');
    Route::post('/leaves/{id}/reject', [SupervisorController::class, 'reject'])->name('supervisor.reject');
});

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'staff'])->name('staff.dashboard');
     Route::get('/attendance', [StaffController::class, 'attendance'])->name('self.attendance');
    Route::get('/leaves', [StaffController::class, 'index'])->name('staff.leaves');
    Route::post('/break/start', [BreakTimeController::class, 'start'])->name('staff.break.start');
    Route::post('/break/end', [BreakTimeController::class, 'end'])->name('staff.break.end');
});







Route::middleware(['auth'])->group(function () {
    Route::get('/projects/dashboard', [ProjectController::class, 'dashboard'])->name('project.dashboard');
Route::get('/projects/index',[ProjectController::class, 'create'])->name('create.project');
Route::post('/projects/store',[ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/tasks-index', [ProjectController::class, 'tasksIndex'])->name('projects.index');
Route::post('/tasks/store',[ProjectController::class, 'storeTask'])->name('tasks.store');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/assessment/dashboard',[AssessmentController::class, 'asdashboard'])->name('assessment.dashboard');
    Route::post('assessment/store', [AssessmentController::class, 'storeAss'])->name('assessments.store');
    Route::get('assessment/self-assessment', [AssessmentController::class, 'selfAss'])->name('self.assess');
    Route::post('selfAssess/store',[AssessmentController::class, 'storeSelf'])->name('assessments.store');
});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
