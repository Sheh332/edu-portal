<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user')->orderByDesc('date');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->paginate(25)->withQueryString();
        $students = User::role('student')->orderBy('name')->get();

        return view('admin.attendance.index', compact('attendances', 'students'));
    }

    public function create()
    {
        $students = User::role('student')->orderBy('name')->get();

        return view('admin.attendance.create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'status' => ['required', Rule::in(['present', 'absent', 'leave'])],
        ]);

        $exists = Attendance::where('user_id', $data['user_id'])
            ->whereDate('date', $data['date'])->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'An attendance record already exists for that user and date.');
        }

        Attendance::create($data + ['marked_at' => now()]);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record added.');
    }

    public function edit(Attendance $attendance)
    {
        $students = User::role('student')->orderBy('name')->get();

        return view('admin.attendance.edit', compact('attendance', 'students'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', Rule::in(['present', 'absent', 'leave'])],
        ]);

        $attendance->update($data);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance record updated.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return back()->with('success', 'Attendance record deleted.');
    }
}
