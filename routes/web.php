<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Guest (authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated user panel
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Attendance
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // Leave
    Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Tasks (user view/submit)
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::post('tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');
});

/*
|--------------------------------------------------------------------------
| Admin panel (role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|teacher'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Classroom / Classes management
    Route::get('classrooms', [Admin\ClassroomController::class, 'index'])->name('classrooms.index');
    Route::post('classrooms', [Admin\ClassroomController::class, 'store'])->name('classrooms.store');
    Route::get('classrooms/{classroom}', [Admin\ClassroomController::class, 'show'])->name('classrooms.show');
    Route::delete('classrooms/{classroom}', [Admin\ClassroomController::class, 'destroy'])->name('classrooms.destroy');

    // Student management
    Route::get('students', [Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [Admin\StudentController::class, 'show'])->name('students.show');

    // Attendance records CRUD
    Route::get('attendance', [Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [Admin\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [Admin\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('attendance/{attendance}/edit', [Admin\AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('attendance/{attendance}', [Admin\AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{attendance}', [Admin\AttendanceController::class, 'destroy'])->name('attendance.destroy');

    // Leave approval
    Route::get('leaves', [Admin\LeaveController::class, 'index'])->name('leaves.index');
    Route::put('leaves/{leave}', [Admin\LeaveController::class, 'update'])->name('leaves.update');

    // Reports
    Route::get('reports/student', [Admin\ReportController::class, 'student'])->name('reports.student');
    Route::get('reports/system', [Admin\ReportController::class, 'system'])->name('reports.system');

    // Grading
    Route::get('grades', [Admin\GradeController::class, 'index'])->name('grades.index');
    Route::post('grades', [Admin\GradeController::class, 'store'])->name('grades.store');
    Route::put('grades/{grade}', [Admin\GradeController::class, 'update'])->name('grades.update');
    Route::delete('grades/{grade}', [Admin\GradeController::class, 'destroy'])->name('grades.destroy');

    // Task management
    Route::get('tasks', [Admin\TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/create', [Admin\TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [Admin\TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [Admin\TaskController::class, 'show'])->name('tasks.show');
    Route::put('tasks/{task}/review', [Admin\TaskController::class, 'review'])->name('tasks.review');

    // Roles & permissions
    Route::get('roles', [Admin\RoleController::class, 'index'])->name('roles.index');
    Route::post('roles', [Admin\RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{role}', [Admin\RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [Admin\RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('roles/assign', [Admin\RoleController::class, 'assign'])->name('roles.assign');
});
