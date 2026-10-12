<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = $request->user();

        $studentCount = User::where(function ($q) {
            $q->whereHas('roles', fn ($r) => $r->where('name', 'student'))
              ->orWhereNotNull('classroom_id')
              ->orWhereDoesntHave('roles');
        })->whereDoesntHave('roles', fn ($r) => $r->whereIn('name', ['teacher', 'admin']))->count();

        $stats = [
            'students' => $studentCount,
            'classrooms' => \App\Models\Classroom::count(),
            'present_today' => \App\Models\Attendance::whereDate('date', now()->toDateString())
                ->where('status', 'present')->count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'pending_tasks' => Task::where('status', 'submitted')->count(),
        ];

        $myClasses = $user->isTeacher() && $user->taughtClasses()->exists()
            ? $user->taughtClasses()->withCount('students')->get()
            : \App\Models\Classroom::withCount('students')->take(6)->get();

        $recentLeaves = Leave::with('user')->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentLeaves', 'myClasses'));
    }
}
