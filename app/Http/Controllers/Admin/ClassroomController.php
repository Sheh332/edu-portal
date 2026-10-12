<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::withCount('students', 'teachers')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:classrooms,name'],
            'code' => ['nullable', 'string', 'max:50'],
            'section' => ['nullable', 'string', 'max:100'],
        ]);

        $classroom = Classroom::create([
            'name' => trim($data['name']),
            'code' => $data['code'] ? trim($data['code']) : strtoupper(substr(trim($data['name']), 0, 6)),
            'section' => $data['section'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        // If the teacher created it, automatically assign them to this class
        if ($request->user()->isTeacher() && !$request->user()->taughtClasses()->where('classroom_id', $classroom->id)->exists()) {
            $request->user()->taughtClasses()->attach($classroom->id);
        }

        return redirect()->route('admin.classrooms.index')->with('success', "Class '{$classroom->name}' created successfully.");
    }

    public function show(Classroom $classroom)
    {
        $classroom->load(['students' => function ($q) {
            $q->withCount([
                'attendances as present_count' => fn ($q2) => $q2->where('status', 'present'),
                'attendances as absent_count' => fn ($q2) => $q2->where('status', 'absent'),
                'leaves as leaves_count' => fn ($q2) => $q2->where('status', 'approved'),
            ]);
        }, 'teachers']);

        return view('admin.classrooms.show', compact('classroom'));
    }

    public function destroy(Classroom $classroom)
    {
        $name = $classroom->name;
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')->with('success', "Class '{$name}' deleted successfully.");
    }
}
