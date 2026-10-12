<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\User;

class StudentController extends Controller
{
    /** List all registered students (excludes teachers/admins unless superadmin filters them). */
    public function index(\Illuminate\Http\Request $request)
    {
        $classrooms = \App\Models\Classroom::orderBy('name')->get();
        $user = $request->user();

        // Base query: Only show actual students (exclude teachers and admins)
        $query = User::with(['roles', 'classroom'])
            ->where(function ($q) {
                $q->where('role', 'student')
                  ->orWhere(function ($q2) {
                      $q2->whereHas('roles', fn ($r) => $r->where('name', 'student'))
                         ->whereDoesntHave('roles', fn ($r2) => $r2->whereIn('name', ['teacher', 'admin']));
                  });
            })
            ->where('role', '!=', 'teacher')
            ->where('role', '!=', 'admin')
            ->orderBy('name');

        // Filter by classroom if requested
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        $students = $query->paginate(20)->withQueryString();

        return view('admin.students.index', compact('students', 'classrooms'));
    }

    /** A single student's attendance summary. */
    public function show(User $student)
    {
        $present = $student->attendances()->where('status', 'present')->count();
        $leaveDays = $student->attendances()->where('status', 'leave')->count();
        $grade = GradeLevel::forDays($present);

        $attendances = $student->attendances()->orderByDesc('date')->paginate(15);
        $leaves = $student->leaves()->orderByDesc('created_at')->get();

        return view('admin.students.show', compact('student', 'present', 'leaveDays', 'grade', 'attendances', 'leaves'));
    }
}
