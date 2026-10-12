@extends('layouts.app')
@section('title', 'Tasks & Assignments')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Tasks &amp; Assignments</h2>
        <p class="text-muted mb-0 small">Assign work to students, track submission statuses, and provide feedback.</p>
    </div>
    <div>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Create Task
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned Student</th>
                    <th>Status</th>
                    <th>Assigned Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>
                        <span class="fw-semibold text-dark">{{ $task->title }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:30px; height:30px; font-size:0.75rem;">
                                {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                            </div>
                            <span class="text-secondary small">{{ $task->assignee->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ ['pending'=>'secondary','submitted'=>'warning','approved'=>'success','rejected'=>'danger'][$task->status] }}">
                            <i class="bi bi-{{ ['pending'=>'clock','submitted'=>'send','approved'=>'check2-circle','rejected'=>'x-circle'][$task->status] }} me-1"></i>
                            {{ ucfirst($task->status) }}
                        </span>
                    </td>
                    <td class="text-muted small">
                        {{ $task->created_at->format('d M Y') }}
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-folder2-open"></i> Review
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-journal-x fs-1 text-muted d-block mb-2"></i>
                        No tasks created yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $tasks->links('pagination::bootstrap-5') }}
</div>
@endsection
