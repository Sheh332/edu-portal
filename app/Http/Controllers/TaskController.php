<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /** Tasks assigned to the current user. */
    public function index()
    {
        $tasks = Auth::user()->tasks()->with('assigner')->orderByDesc('created_at')->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $this->authorizeOwner($task);

        return view('tasks.show', compact('task'));
    }

    /** Submit a response to a task. */
    public function submit(Request $request, Task $task, WhatsAppService $whatsapp)
    {
        $this->authorizeOwner($task);

        $data = $request->validate([
            'response' => ['required', 'string'],
        ]);

        $task->update([
            'response' => $data['response'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $whatsapp->sendToUser($task->assigner, "{$task->assignee->name} submitted task: {$task->title}");

        return redirect()->route('tasks.show', $task)->with('success', 'Task submitted for review.');
    }

    protected function authorizeOwner(Task $task): void
    {
        abort_unless($task->assigned_to === Auth::id(), 403);
    }
}
