@extends('layouts.app')
@section('title', $classroom->name . ' - Students & Performance')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to All Classes
    </a>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary fs-6">{{ $classroom->code ?? 'CLASS' }}</span>
                <h2 class="fw-bold tracking-tight text-dark mb-0">{{ $classroom->name }}</h2>
            </div>
            <p class="text-muted small mb-0 mt-1">Section/Group: <strong>{{ $classroom->section ?: 'Standard' }}</strong> &bull; {{ $classroom->students->count() }} enrolled student(s)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-check2-square"></i> Mark Class Attendance
            </a>
        </div>
    </div>
</div>

<!-- Class Enrolled Students Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">Enrolled Students &amp; Performance Metrics</h6>
        <span class="text-muted small">Total: {{ $classroom->students->count() }} students</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email Address</th>
                    <th>Contact Phone</th>
                    <th class="text-center">Present Days</th>
                    <th class="text-center">Absences</th>
                    <th class="text-center">Leaves</th>
                    <th class="text-end">Student Profile</th>
                </tr>
            </thead>
            <tbody>
            @forelse($classroom->students as $student)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($student->profile_picture)
                                <img src="{{ asset('storage/'.$student->profile_picture) }}" class="rounded-circle border" width="36" height="36" style="object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:36px; height:36px; font-size:0.85rem;">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="fw-semibold text-dark">{{ $student->name }}</span>
                        </div>
                    </td>
                    <td class="text-secondary small">{{ $student->email }}</td>
                    <td class="text-secondary small">{{ $student->phone ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge bg-success px-3 py-1 fw-bold">{{ $student->present_count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-danger px-3 py-1 fw-bold">{{ $student->absent_count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info px-3 py-1 fw-bold">{{ $student->leaves_count }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-person-lines-fill"></i> View Performance &rarr;
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                        No students currently registered under {{ $classroom->name }}.
                        <p class="small text-muted mt-1">When students register and select this class, they will automatically appear here!</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
