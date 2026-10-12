<?php

namespace App\Http\Controllers;

use App\Models\GradeLevel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $markedToday = $user->attendances()->whereDate('date', $today)->exists();
        $presentDays = $user->presentDays();
        $grade = GradeLevel::forDays($presentDays);

        $stats = [
            'present' => $presentDays,
            'leaves' => $user->leaves()->where('status', 'approved')->count(),
            'pending_leaves' => $user->leaves()->where('status', 'pending')->count(),
            'tasks' => $user->tasks()->whereIn('status', ['pending', 'rejected'])->count(),
        ];

        // Latest assigned tasks for quick access directly on student dashboard
        $myTasks = $user->tasks()->with('assigner')->latest()->take(5)->get();

        return view('dashboard', compact('user', 'markedToday', 'presentDays', 'grade', 'stats', 'myTasks'));
    }
}
