<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /** Per-student report: user + date range. */
    public function student(Request $request)
    {
        $students = User::role('student')->orderBy('name')->get();
        $report = null;

        if ($request->filled(['user_id', 'from', 'to'])) {
            $data = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'from' => ['required', 'date'],
                'to' => ['required', 'date', 'after_or_equal:from'],
            ]);

            $student = User::findOrFail($data['user_id']);
            $report = $this->buildReport($student, $data['from'], $data['to']);
        }

        return view('admin.reports.student', compact('students', 'report'));
    }

    /** System-wide report between two dates. */
    public function system(Request $request)
    {
        $rows = null;

        if ($request->filled(['from', 'to'])) {
            $data = $request->validate([
                'from' => ['required', 'date'],
                'to' => ['required', 'date', 'after_or_equal:from'],
            ]);

            $rows = User::role('student')->orderBy('name')->get()->map(function ($student) use ($data) {
                return $this->buildReport($student, $data['from'], $data['to']);
            });
        }

        return view('admin.reports.system', compact('rows'))->with('range', $request->only('from', 'to'));
    }

    protected function buildReport(User $student, string $from, string $to): array
    {
        $base = Attendance::where('user_id', $student->id)
            ->whereBetween('date', [$from, $to]);

        $present = (clone $base)->where('status', 'present')->count();
        $absent = (clone $base)->where('status', 'absent')->count();
        $leave = (clone $base)->where('status', 'leave')->count();

        $grade = \App\Models\GradeLevel::forDays($present);

        return [
            'student' => $student,
            'from' => $from,
            'to' => $to,
            'present' => $present,
            'absent' => $absent,
            'leave' => $leave,
            'grade' => $grade?->grade ?? '-',
        ];
    }
}
