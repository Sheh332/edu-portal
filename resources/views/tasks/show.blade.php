@extends('layouts.app')
@section('title', 'Task Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to Tasks
    </a>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="fw-bold tracking-tight text-dark mb-0">{{ $task->title }}</h2>
            <p class="text-muted small mb-0">Assigned by <strong>{{ $task->assigner->name }}</strong> on {{ $task->created_at->format('d M Y') }}</p>
        </div>
        <div>
            <span class="badge bg-{{ ['pending'=>'secondary','submitted'=>'warning','approved'=>'success','rejected'=>'danger'][$task->status] }} px-3 py-2 fs-6">
                <i class="bi bi-{{ ['pending'=>'clock','submitted'=>'send','approved'=>'check2-circle','rejected'=>'x-circle'][$task->status] }} me-1"></i>
                Status: {{ ucfirst($task->status) }}
            </span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <h6 class="fw-bold text-dark mb-0">Assignment Instructions</h6>
    </div>
    <div class="card-body p-4 text-secondary">
        {!! $task->description !!}
    </div>
</div>

@if($task->feedback && in_array($task->status, ['approved','rejected']))
    <div class="card border-0 shadow-sm mb-4 border-start border-4 border-{{ $task->status === 'approved' ? 'success' : 'danger' }}">
        <div class="card-body p-4">
            <h6 class="fw-bold text-{{ $task->status === 'approved' ? 'success' : 'danger' }} mb-2">
                <i class="bi bi-chat-quote-fill me-1"></i> Instructor Evaluation &amp; Feedback:
            </h6>
            <p class="text-dark mb-0">{{ $task->feedback }}</p>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom border-light py-3 px-4">
        <h6 class="fw-bold text-dark mb-0">Your Work Submission</h6>
    </div>
    <div class="card-body p-4">
        @if($task->status === 'pending' || $task->status === 'rejected')
            <form method="POST" action="{{ route('tasks.submit', $task) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Your Response / Project Write-up</label>
                    <textarea name="response" id="editor">{{ old('response', $task->response) }}</textarea>
                </div>
                <button class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-send-fill"></i> Submit Task for Evaluation
                </button>
            </form>
        @else
            <div class="p-3 bg-light rounded-3 text-secondary mb-3">
                {!! $task->response !!}
            </div>
            @if($task->status === 'submitted')
                <div class="alert alert-warning border-0 d-flex align-items-center gap-2 mb-0" style="background: #fffbeb; color: #b45309;">
                    <i class="bi bi-hourglass-split fs-5"></i>
                    <span>Submitted on <strong>{{ $task->submitted_at?->format('d M Y h:i A') }}</strong> &mdash; currently in queue for instructor grading.</span>
                </div>
            @endif
        @endif
    </div>
</div>

@if($task->status === 'pending' || $task->status === 'rejected')
@push('head')<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>@endpush
@push('scripts')
<script>
    ClassicEditor.create(document.querySelector('#editor')).catch(console.error);
</script>
@endpush
@endif
@endsection
