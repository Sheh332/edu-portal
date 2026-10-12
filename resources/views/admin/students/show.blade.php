@extends('layouts.app')
@section('title', 'Student Profile & History')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to Student Directory
    </a>
    <div class="d-flex align-items-center gap-3">
        @if($student->profile_picture)
            <img src="{{ asset('storage/'.$student->profile_picture) }}" class="rounded-circle border shadow-sm" width="56" height="56" style="object-fit:cover;">
        @else
            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-4 border" style="width:56px; height:56px;">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <h2 class="fw-bold tracking-tight text-dark mb-0">{{ $student->name }}</h2>
            <div class="d-flex align-items-center gap-2 mt-1">
                @if($student->classroom)
                    <span class="badge bg-primary"><i class="bi bi-mortarboard me-1"></i>{{ $student->classroom->name }}</span>
                @endif
                <span class="text-muted small">{{ $student->email }} &bull; {{ $student->phone ?? 'No phone specified' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-1 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Present Days</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $present }}</h2>
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
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Approved Leave</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $leaveDays }}</h2>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-airplane-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-4 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Current Grade</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $grade?->grade ?? 'N/A' }}</h2>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-award-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card-gradient-3 h-100 p-3">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Pending Leaves</span>
                    <h2 class="fw-bold mb-0 text-white mt-1">{{ $leaves->where('status','pending')->count() }}</h2>
                </div>
                <div class="stat-icon-pill">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Attendance Table -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">Attendance History</h6>
                <span class="text-muted small">Total: {{ $attendances->total() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($attendances as $a)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $a->date->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ ['present'=>'success','absent'=>'danger','leave'=>'info'][$a->status] }}">
                                    {{ ucfirst($a->status) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $a->marked_at?->format('h:i A') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No attendance records.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $attendances->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Leaves List -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-dark mb-0">Leave Applications</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $leave->from_date->format('d M') }} &rarr; {{ $leave->to_date->format('d M Y') }}</div>
                                <div class="text-muted small">{{ Str::limit($leave->reason, 25) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger'][$leave->status] }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">No leave applications.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
