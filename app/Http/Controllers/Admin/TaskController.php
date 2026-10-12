<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['assignee', 'assigner'])->orderByDesc('created_at')->paginate(20);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create(Request $request)
    {
        $classrooms = \App\Models\Classroom::with('students')->orderBy('name')->get();
        $user = $request->user();

        // If teacher has assigned classes, prioritize them, else all
        $teacherClasses = $user->isTeacher() && $user->taughtClasses()->exists()
            ? $user->taughtClasses()->with('students')->get()
            : $classrooms;

        $students = User::role('student')->with('classroom')->orderBy('name')->get();

        return view('admin.tasks.create', compact('classrooms', 'teacherClasses', 'students'));
    }

    /** Assign a task (to individual student or entire class). */
    public function store(Request $request, WhatsAppService $whatsapp)
    {
        $data = $request->validate([
            'assignment_mode' => ['required', 'in:individual,class'],
            'classroom_id' => ['nullable', 'required_if:assignment_mode,class', 'exists:classrooms,id'],
            'assigned_to' => ['nullable', 'required_if:assignment_mode,individual', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $createdCount = 0;

        if ($data['assignment_mode'] === 'class') {
            $class = \App\Models\Classroom::with('students')->findOrFail($data['classroom_id']);
            $studentsToAssign = $class->students;

            if ($studentsToAssign->isEmpty()) {
                return back()->with('error', "No students are currently enrolled in {$class->name}.");
            }

            foreach ($studentsToAssign as $student) {
                $task = Task::create([
                    'assigned_to' => $student->id,
                    'assigned_by' => $request->user()->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'status' => 'pending',
                ]);
                $createdCount++;

                try {
                    $whatsapp->sendToUser($student, "Hi {$student->name}, a new class task has been assigned for {$class->name}: {$task->title}");
                } catch (\Throwable $e) {}
            }

            return redirect()->route('admin.tasks.index')->with('success', "Task successfully assigned to all {$createdCount} student(s) of {$class->name}.");
        } else {
            $student = User::findOrFail($data['assigned_to']);
            $task = Task::create([
                'assigned_to' => $student->id,
                'assigned_by' => $request->user()->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => 'pending',
            ]);

            try {
                $whatsapp->sendToUser($student, "Hi {$student->name}, a new task has been assigned to you: {$task->title}");
            } catch (\Throwable $e) {}

            return redirect()->route('admin.tasks.index')->with('success', "Task successfully assigned to {$student->name}.");
        }
    }

    public function show(Task $task)
    {
        $task->load(['assignee', 'assigner']);

        return view('admin.tasks.show', compact('task'));
    }

    /** Approve or reject a submitted task with feedback. */
    public function review(Request $request, Task $task, WhatsAppService $whatsapp)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $task->update([
            'status' => $data['status'],
            'feedback' => $data['feedback'] ?? null,
            'reviewed_at' => now(),
        ]);

        $whatsapp->sendToUser(
            $task->assignee,
            "Hi {$task->assignee->name}, your task \"{$task->title}\" has been {$data['status']}."
            .($data['feedback'] ? " Feedback: {$data['feedback']}" : '')
        );

        return back()->with('success', "Task {$data['status']}.");
    }
}
