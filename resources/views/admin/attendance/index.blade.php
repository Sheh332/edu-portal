@extends('layouts.app')
@section('title', 'Daily Attendance Records')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Attendance Management</h2>
        <p class="text-muted mb-0 small">Filter, modify, and manage student attendance check-ins.</p>
    </div>
    <div>
        <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Add Attendance Record
        </a>
    </div>
</div>

<!-- Student Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-funnel"></i></span>
                    <select name="user_id" class="form-select" onchange="this.form.submit()">
                        <option value="">— Show All Students —</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}" @selected(request('user_id')==$s->id)>{{ $s->name }} ({{ $s->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if(request('user_id'))
                <div class="col-md-2">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary btn-sm">Clear Filter</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($attendances as $a)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; font-size:0.8rem;">
                                {{ strtoupper(substr($a->user->name, 0, 1)) }}
                            </div>
                            <span class="fw-semibold text-dark">{{ $a->user->name }}</span>
                        </div>
                    </td>
                    <td class="text-secondary">{{ $a->date->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ ['present'=>'success','absent'=>'danger','leave'=>'info'][$a->status] }}">
                            <i class="bi bi-{{ ['present'=>'check-circle','absent'=>'x-circle','leave'=>'airplane'][$a->status] }} me-1"></i>
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.attendance.edit', $a) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.attendance.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Delete this attendance record?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted d-block mb-2"></i>
                        No attendance records found for this criteria.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $attendances->links('pagination::bootstrap-5') }}
</div>
@endsection
