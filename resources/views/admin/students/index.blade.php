@extends('layouts.app')
@section('title', 'Enrolled Students & Users')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Students &amp; Users Directory</h2>
        <p class="text-muted mb-0 small">Manage registered users, their roles, and individual attendance profiles.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Mark Attendance
        </a>
    </div>
</div>

</div>

<!-- Class Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-mortarboard"></i></span>
                    <select name="classroom_id" class="form-select" onchange="this.form.submit()">
                        <option value="">— Show Students From All Classes —</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>
                                {{ $c->name }} {{ $c->section ? "({$c->section})" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if(request('classroom_id'))
                <div class="col-md-3">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">Clear Class Filter</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">Enrolled Students Roster</h6>
        <span class="text-muted small">Total: {{ $students->total() }} student(s)</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>User / Student</th>
                    <th>Class / Grade</th>
                    <th>Email Address</th>
                    <th>Contact Phone</th>
                    <th>Assigned Roles</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($students as $student)
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
                    <td>
                        @if($student->classroom)
                            <span class="badge bg-primary">{{ $student->classroom->name }}</span>
                        @elseif($student->taughtClasses->count())
                            <span class="badge bg-info text-dark">Teaches {{ $student->taughtClasses->count() }} class(es)</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-secondary">{{ $student->email }}</td>
                    <td class="text-secondary">{{ $student->phone ?? '—' }}</td>
                    <td>
                        @foreach($student->roles as $role)
                            <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'secondary' }} me-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                            <i class="bi bi-file-earmark-person"></i> View Record
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                        No student or user records found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $students->links('pagination::bootstrap-5') }}
</div>
@endsection
