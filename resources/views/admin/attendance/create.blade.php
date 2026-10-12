@extends('layouts.app')
@section('title', 'Add Attendance')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to Attendance
    </a>
    <h2 class="fw-bold tracking-tight text-dark mb-1">Add Attendance Record</h2>
    <p class="text-muted mb-0 small">Manually create or backdate an attendance entry for a student.</p>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.attendance.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Student</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                            <select name="user_id" class="form-select" required>
                                <option value="">— Select Student —</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}" @selected(old('user_id')==$s->id)>{{ $s->name }} ({{ $s->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Record Date</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar"></i></span>
                            <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">Attendance Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['present'=>'Present (In Attendance)','absent'=>'Absent (Unexcused)','leave'=>'Leave (Excused Absence)'] as $st => $label)
                                <option value="{{ $st }}" @selected(old('status')==$st)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-check2"></i> Save Record
                        </button>
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
