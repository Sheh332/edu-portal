<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Models\User;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /** Manage grade levels + show each student's computed grade. */
    public function index()
    {
        $grades = GradeLevel::orderByDesc('min_days')->get();

        $summary = User::role('student')->orderBy('name')->get()->map(function ($student) {
            $present = $student->attendances()->where('status', 'present')->count();
            $grade = GradeLevel::forDays($present);

            return [
                'student' => $student,
                'present' => $present,
                'grade' => $grade?->grade ?? '-',
            ];
        });

        return view('admin.grades.index', compact('grades', 'summary'));
    }

    public function store(Request $request)
    {
        $data = $this->validateGrade($request);
        GradeLevel::create($data);

        return redirect()->route('admin.grades.index')->with('success', 'Grade level added successfully.');
    }

    public function update(Request $request, GradeLevel $grade)
    {
        $data = $this->validateGrade($request);
        $grade->update($data);

        return redirect()->route('admin.grades.index')->with('success', 'Grade level updated successfully.');
    }

    public function destroy(GradeLevel $grade)
    {
        $grade->delete();

        return redirect()->route('admin.grades.index')->with('success', 'Grade level deleted.');
    }

    protected function validateGrade(Request $request): array
    {
        return $request->validate([
            'grade' => ['required', 'string', 'max:10'],
            'min_days' => ['required', 'integer', 'min:0'],
            'max_days' => ['required', 'integer', 'gte:min_days'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
