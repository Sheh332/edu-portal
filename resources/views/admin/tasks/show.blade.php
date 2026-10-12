@extends('layouts.app')
@section('title', 'Review Student Task')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to Tasks
    </a>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold tracking-tight text-dark mb-0">{{ $task->title }}</h2>
            <p class="text-muted small mb-0">
                Assigned to <strong>{{ $task->assignee->name }}</strong> &bull; By {{ $task->assigner->name }} on {{ $task->created_at->format('d M Y') }}
            </p>
        </div>
        <div>
            <span class="badge bg-{{ ['pending'=>'secondary','submitted'=>'warning','approved'=>'success','rejected'=>'danger'][$task->status] }} px-3 py-2 fs-6">
                Status: {{ ucfirst($task->status) }}
            </span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <h6 class="fw-bold text-dark mb-0">Original Task Instructions</h6>
    </div>
    <div class="card-body p-4 text-secondary">
        {!! $task->description !!}
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <h6 class="fw-bold text-dark mb-0">Student's Submission</h6>
    </div>
    <div class="card-body p-4">
        @if($task->response)
            <div class="p-3 bg-light rounded-3 text-secondary mb-2">
                {!! $task->response !!}
            </div>
            <div class="text-muted small">
                <i class="bi bi-clock me-1"></i> Submitted at {{ $task->submitted_at?->format('d M Y h:i A') }}
            </div>
        @else
            <div class="text-muted py-3 text-center">
                <i class="bi bi-hourglass text-muted fs-3 d-block mb-1"></i>
                Student has not submitted any work for this task yet.
            </div>
        @endif
    </div>
</div>

@if($task->feedback)
    <div class="card border-0 shadow-sm mb-4 border-start border-4 border-{{ $task->status === 'approved' ? 'success' : 'secondary' }}">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-1">Current Feedback:</h6>
            <p class="text-secondary mb-0">{{ $task->feedback }}</p>
        </div>
    </div>
@endif

@if($task->status === 'submitted')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <h6 class="fw-bold text-dark mb-0">Grade &amp; Provide Feedback</h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tasks.review', $task) }}">
            @csrf @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold text-secondary small">Decision</label>
                    <select name="status" class="form-select" required>
                        <option value="approved">Approve &amp; Mark Complete</option>
                        <option value="rejected">Request Revisions (Reject)</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary small">Feedback / Critique for Student</label>
                <textarea name="feedback" rows="3" class="form-control" placeholder="Well done, good analysis, or please revise section 2...">{{ old('feedback', $task->feedback) }}</textarea>
            </div>

            <button class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-check2-circle"></i> Save Review &amp; Notify Student
            </button>
        </form>
    </div>
</div>
@endif
@endsection
