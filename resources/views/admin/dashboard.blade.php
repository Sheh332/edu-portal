@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Administrative Overview 🚀</h2>
        <p class="text-muted mb-0 small">Real-time attendance rates, student metrics, and pending requests.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
            <i class="bi bi-mortarboard"></i> Manage Classes
        </a>
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Mark Attendance
        </a>
    </div>
</div>

<!-- Quick Teaching Classes Banner -->
@if(isset($myClasses) && $myClasses->count())
<div class="card border-0 shadow-sm mb-4" style="border-radius:1rem; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
    <div class="card-body p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="bi bi-easel-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Active Classes / Taught Sections</h6>
                    <small class="text-muted">Quick access to student rosters and performance by class:</small>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($myClasses as $cls)
                    <a href="{{ route('admin.classrooms.show', $cls) }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-2">
                        <span class="fw-semibold text-dark">{{ $cls->name }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $cls->students_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none">
            <div class="card stat-card-gradient-1 h-100 p-3">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Total Students</span>
                        <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['students'] }}</h2>
                        <span class="badge bg-white text-primary mt-2" style="font-size:0.75rem;">Across All Classes</span>
                    </div>
                    <div class="stat-icon-pill">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.attendance.index') }}" class="text-decoration-none">
            <div class="card stat-card-gradient-4 h-100 p-3">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Present Today</span>
                        <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['present_today'] }}</h2>
                        <span class="badge bg-white text-success mt-2" style="font-size:0.75rem;">Recorded Today</span>
                    </div>
                    <div class="stat-icon-pill">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.leaves.index') }}" class="text-decoration-none">
            <div class="card stat-card-gradient-3 h-100 p-3">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Pending Leaves</span>
                        <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['pending_leaves'] }}</h2>
                        <span class="badge bg-white text-warning mt-2" style="font-size:0.75rem;">Requires Decision</span>
                    </div>
                    <div class="stat-icon-pill">
                        <i class="bi bi-envelope-exclamation-fill"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none">
            <div class="card stat-card-gradient-2 h-100 p-3">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white text-opacity-75 small fw-semibold text-uppercase tracking-wider">Review Tasks</span>
                        <h2 class="fw-bold mb-0 text-white mt-1">{{ $stats['pending_tasks'] }}</h2>
                        <span class="badge bg-white text-info mt-2" style="font-size:0.75rem;">Submissions In</span>
                    </div>
                    <div class="stat-icon-pill">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Visual Attendance Summary & Pending Leaves -->
<div class="row g-4">
    <!-- Chart Overview -->
    <div class="col-lg-5">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Today's Attendance Ratio</h5>
                <span class="badge bg-light text-secondary border">Live snapshot</span>
            </div>
            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                <div style="max-height: 230px; position: relative;">
                    <canvas id="todayAttendanceChart"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-4 mt-4 w-100 text-center">
                    <div>
                        <div class="text-success fw-bold fs-5">{{ $stats['present_today'] }}</div>
                        <div class="small text-muted">Present</div>
                    </div>
                    <div class="border-end"></div>
                    <div>
                        <div class="text-danger fw-bold fs-5">{{ max(0, $stats['students'] - $stats['present_today']) }}</div>
                        <div class="small text-muted">Absent/Other</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leaves Table -->
    <div class="col-lg-7">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0">Pending Leave Requests</h5>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-link text-decoration-none text-primary fw-semibold p-0">View all &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Period</th>
                            <th>Reason</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentLeaves as $leave)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:34px; height:34px; font-size:0.85rem;">
                                        {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $leave->user->name }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $leave->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $leave->from_date->format('d M') }} &rarr; {{ $leave->to_date->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ Str::limit($leave->reason, 35) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.leaves.index') }}" class="btn btn-sm btn-primary px-3">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-check-circle text-success fs-2 d-block mb-2"></i>
                                No pending leave requests at this time.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('todayAttendanceChart');
    if (ctx) {
        const presentCount = {{ $stats['present_today'] }};
        const totalStudents = {{ $stats['students'] }};
        const absentCount = Math.max(0, totalStudents - presentCount);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present Today', 'Absent / Pending'],
                datasets: [{
                    data: [presentCount, absentCount],
                    backgroundColor: ['#10b981', '#f1f5f9'],
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 3,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: { family: "'Plus Jakarta Sans', sans-serif", weight: '500' }
                        }
                    }
                },
                cutout: '72%'
            }
        });
    }
</script>
@endpush
@endsection
