@extends('layouts.app')
@section('title', 'System Attendance Report')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">System-wide Attendance Analytics</h2>
    <p class="text-muted mb-0 small">Cross-student attendance, absence and leave metrics across custom date intervals.</p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">From Date</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar"></i></span>
                    <input type="date" name="from" value="{{ $range['from'] ?? '' }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary small">To Date</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-check"></i></span>
                    <input type="date" name="to" value="{{ $range['to'] ?? '' }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-filter"></i> Run System Report
                </button>
            </div>
        </form>
    </div>
</div>

@if($rows)
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">Attendance Summary Table</h6>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print Summary
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th class="text-center">Present Days</th>
                    <th class="text-center">Absent Days</th>
                    <th class="text-center">Leaves</th>
                    <th class="text-center">Overall Grade</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; font-size:0.8rem;">
                                {{ strtoupper(substr($row['student']->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="fw-semibold text-dark">{{ $row['student']->name }}</span>
                                <div class="text-muted small">{{ $row['student']->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-success px-3 py-2 fw-bold">{{ $row['present'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-danger px-3 py-2 fw-bold">{{ $row['absent'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info px-3 py-2 fw-bold">{{ $row['leave'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary px-3 py-2 fw-bold fs-6">{{ $row['grade'] }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">No students found matching this criteria.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
