@extends('layouts.app')
@section('title', 'My Attendance')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Attendance History</h2>
        <p class="text-muted mb-0 small">Track your daily clock-ins, presence status, and timestamps.</p>
    </div>
    <div>
        @unless($markedToday)
            <form method="POST" action="{{ route('attendance.store') }}" class="m-0">
                @csrf
                <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-geo-alt-fill"></i> Check In Today
                </button>
            </form>
        @else
            <div class="badge bg-success px-3 py-2 d-flex align-items-center gap-2 fs-6">
                <i class="bi bi-check-circle-fill"></i> Checked in today
            </div>
        @endunless
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">Attendance Log</h6>
        <span class="text-muted small">Total: {{ $attendances->total() }} records</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Status</th>
                    <th>Recorded Timestamp</th>
                </tr>
            </thead>
            <tbody>
            @forelse($attendances as $a)
                <tr>
                    <td class="fw-semibold text-dark">{{ $a->date->format('d M Y') }}</td>
                    <td class="text-secondary">{{ $a->date->format('l') }}</td>
                    <td>
                        <span class="badge bg-{{ ['present'=>'success','absent'=>'danger','leave'=>'info'][$a->status] }}">
                            <i class="bi bi-{{ ['present'=>'check-circle','absent'=>'x-circle','leave'=>'airplane'][$a->status] }} me-1"></i>
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                    <td class="text-muted">
                        <i class="bi bi-clock me-1"></i> {{ $a->marked_at?->format('h:i:s A') ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted d-block mb-2"></i>
                        No attendance records logged yet. Check in to start tracking!
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
