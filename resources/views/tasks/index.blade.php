@extends('layouts.app')
@section('title', 'My Tasks')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">My Tasks &amp; Assignments</h2>
    <p class="text-muted mb-0 small">Review assignments, submit project deliverables, and see admin evaluation.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned By</th>
                    <th>Current Status</th>
                    <th>Date Assigned</th>
                    <th class="text-end">Action</th>
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
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:28px; height:28px; font-size:0.75rem;">
                                {{ strtoupper(substr($task->assigner->name, 0, 1)) }}
                            </div>
                            <span class="text-secondary small">{{ $task->assigner->name }}</span>
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
                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-primary px-3 shadow-xs">
                            View Task &rarr;
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-journal-check fs-1 text-success d-block mb-2"></i>
                        No assignments pending right now! Great job.
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
