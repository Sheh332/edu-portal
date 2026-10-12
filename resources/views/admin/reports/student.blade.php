@extends('layouts.app')
@section('title', 'Student Attendance Report')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">Student Attendance Report</h2>
    <p class="text-muted mb-0 small">Generate comprehensive attendance breakdowns and metrics for individual students.</p>
</div>

<!-- Filter card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">Select Student</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                    <select name="user_id" class="form-select" required>
                        <option value="">— Choose Student —</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}" @selected(request('user_id')==$s->id)>{{ $s->name }} ({{ $s->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary small">To Date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-file-earmark-bar-graph"></i> Generate
                </button>
            </div>
        </form>
    </div>
</div>

@if($report)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold text-dark mb-0">{{ $report['student']->name }}</h5>
            <small class="text-muted">{{ $report['from'] }} &rarr; {{ $report['to'] }} &bull; {{ $report['student']->email }}</small>
        </div>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print Report
        </button>
    </div>
    <div class="card-body p-4">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3">
                <div class="p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4">
                    <div class="display-6 fw-bold text-success mb-1">{{ $report['present'] }}</div>
                    <span class="text-secondary fw-semibold small text-uppercase">Present Days</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-4 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4">
                    <div class="display-6 fw-bold text-danger mb-1">{{ $report['absent'] }}</div>
                    <span class="text-secondary fw-semibold small text-uppercase">Absent Days</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-4 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-4">
                    <div class="display-6 fw-bold text-info mb-1">{{ $report['leave'] }}</div>
                    <span class="text-secondary fw-semibold small text-uppercase">Approved Leaves</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4">
                    <div class="display-6 fw-bold text-primary mb-1">{{ $report['grade'] }}</div>
                    <span class="text-secondary fw-semibold small text-uppercase">Attendance Grade</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
