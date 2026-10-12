@extends('layouts.app')
@section('title', 'Leave Applications')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Leave Applications</h2>
        <p class="text-muted mb-0 small">Submit and track your leave requests and administration feedback.</p>
    </div>
    <div>
        <a href="{{ route('leaves.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Apply for Leave
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">My Applications</h6>
        <span class="text-muted small">Total: {{ $leaves->total() }} applications</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Leave Dates</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Admin Feedback</th>
                </tr>
            </thead>
            <tbody>
            @forelse($leaves as $leave)
                <tr>
                    <td class="fw-semibold text-dark">
                        {{ $leave->from_date->format('d M Y') }} &rarr; {{ $leave->to_date->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $leave->dayCount() }} day(s)</span>
                    </td>
                    <td class="text-secondary" style="max-width: 250px;">
                        {{ $leave->reason }}
                    </td>
                    <td>
                        <span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger'][$leave->status] }}">
                            <i class="bi bi-{{ ['pending'=>'hourglass-split','approved'=>'check2-circle','rejected'=>'x-circle'][$leave->status] }} me-1"></i>
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td class="text-muted">
                        {{ $leave->admin_comment ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-envelope-check fs-1 text-muted d-block mb-2"></i>
                        No leave applications requested yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $leaves->links('pagination::bootstrap-5') }}
</div>
@endsection
