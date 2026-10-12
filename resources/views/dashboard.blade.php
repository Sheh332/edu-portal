@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h2 class="fw-bold tracking-tight text-dark mb-0">Welcome back, {{ $user->name }} 👋</h2>
            @if($user->classroom)
                <span class="badge bg-primary px-3 py-1 fs-6"><i class="bi bi-mortarboard-fill me-1"></i> {{ $user->classroom->name }}</span>
            @endif
        </div>
        <p class="text-muted mb-0 small">Here is your attendance record, leave summary, and latest academic tasks.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        @if($markedToday)
            <div class="badge bg-success px-3 py-2 d-flex align-items-center gap-2 fs-6">
                <i class="bi bi-check-circle-fill"></i> Marked Present Today
            </div>
        @else
            <form method="POST" action="{{ route('attendance.store') }}" class="m-0">
                @csrf
                <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-geo-alt-fill"></i> Check In Today
                </button>
            </form>
        @endif
        <a href="{{ route('leaves.create') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Request Leave
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-1 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Present Days</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['present'] }}</h2>
                    <span class="badge bg-white text-primary mt-2" style="font-size:0.75rem;">Verified Records</span>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-2 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Approved Leaves</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['leaves'] }}</h2>
                    <span class="badge bg-white text-info mt-2" style="font-size:0.75rem;">Leaves Taken</span>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-airplane-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-3 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Pending Leaves</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['pending_leaves'] }}</h2>
                    <span class="badge bg-white text-warning mt-2" style="font-size:0.75rem;">Awaiting Review</span>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-4 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Active Tasks</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['tasks'] }}</h2>
                    <span class="badge bg-white text-success mt-2" style="font-size:0.75rem;">To Complete</span>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-list-task"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Grade Banner -->
<div class="card mb-4 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%); border-left: 5px solid #4f46e5 !important;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                        <i class="bi bi-award fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Current Attendance Standing</h5>
                        <p class="text-muted small mb-0">Calculated based on <strong>{{ $presentDays }} present day(s)</strong> registered in the academic year.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="text-muted small d-block mb-1">Performance Grade</span>
                <span class="badge bg-primary px-4 py-2 fs-4 fw-bold shadow-sm">
                    {{ $grade?->grade ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<h5 class="fw-bold text-dark mb-3">Quick Services</h5>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                <div class="rounded-circle p-3 mb-3" style="background: #ecfdf5; color: #059669; width: 64px; height: 64px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Check Attendance</h5>
                <p class="text-muted small mb-3">View past attendance dates, timestamps and attendance frequency logs.</p>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-primary w-100">Browse Records</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                <div class="rounded-circle p-3 mb-3" style="background: #f0fdf4; color: #0284c7; width: 64px; height: 64px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-send-fill fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Apply for Leave</h5>
                <p class="text-muted small mb-3">Submit your sick leave or casual absence request directly for approval.</p>
                <a href="{{ route('leaves.create') }}" class="btn btn-outline-primary w-100">Submit Application</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 p-3">
            <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                <div class="rounded-circle p-3 mb-3" style="background: #eef2ff; color: #4f46e5; width: 64px; height: 64px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-journal-check fs-3"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Task Submissions</h5>
                <p class="text-muted small mb-3">Check assigned tasks, write reports and receive feedback from instructors.</p>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary w-100">Go to Tasks</a>
            </div>
        </div>
    </div>
<!-- Assigned Tasks & Homework Section at End of Student Portal -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-kanban text-primary me-2"></i> Assigned Class Tasks &amp; Homework</h5>
            <small class="text-muted">Tasks given by your teachers to your class or directly to you</small>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary">View All Tasks &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned By</th>
                    <th>Date Assigned</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($myTasks as $t)
                <tr>
                    <td>
                        <span class="fw-semibold text-dark">{{ $t->title }}</span>
                    </td>
                    <td>
                        <span class="text-secondary small">{{ $t->assigner->name ?? 'Teacher' }}</span>
                    </td>
                    <td class="text-muted small">
                        {{ $t->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge bg-{{ ['pending'=>'secondary','submitted'=>'warning','approved'=>'success','rejected'=>'danger'][$t->status] }}">
                            <i class="bi bi-{{ ['pending'=>'clock','submitted'=>'send','approved'=>'check2-circle','rejected'=>'x-circle'][$t->status] }} me-1"></i>
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('tasks.show', $t) }}" class="btn btn-sm btn-primary px-3 shadow-xs">
                            {{ in_array($t->status, ['pending','rejected']) ? 'Open & Submit' : 'View Submission' }} &rarr;
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-3 text-success d-block mb-1"></i>
                        No homework or tasks currently pending for you.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
