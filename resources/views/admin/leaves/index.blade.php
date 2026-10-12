@extends('layouts.app')
@section('title', 'Review Leave Requests')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">Leave Requests Management</h2>
    <p class="text-muted mb-0 small">Approve, reject, or comment on student leave applications.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Requested Period</th>
                    <th>Reason</th>
                    <th>Total Approved</th>
                    <th>Status</th>
                    <th class="text-end">Decision</th>
                </tr>
            </thead>
            <tbody>
            @forelse($leaves as $leave)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width:34px; height:34px; font-size:0.85rem;">
                                {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">{{ $leave->user->name }}</div>
                                <div class="text-muted small">{{ $leave->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $leave->from_date->format('d M') }} &rarr; {{ $leave->to_date->format('d M Y') }}</div>
                        <span class="badge bg-secondary">{{ $leave->dayCount() }} day(s)</span>
                    </td>
                    <td style="max-width: 250px;">
                        <span class="text-secondary small">{{ $leave->reason }}</span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $counts[$leave->user_id] ?? 0 }} leaves</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger'][$leave->status] }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                        @if($leave->admin_comment)
                            <div class="text-muted small mt-1"><i class="bi bi-chat-quote me-1"></i>{{ $leave->admin_comment }}</div>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1 align-items-center">
                            @if($leave->status === 'pending')
                                <form method="POST" action="{{ route('admin.leaves.update', $leave) }}" class="d-inline" onsubmit="return confirm('Approve leave for {{ $leave->user->name }}?')">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success px-2 py-1 shadow-xs" title="Quick Approve">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-outline-danger px-2 py-1" data-bs-toggle="modal" data-bs-target="#review-{{ $leave->id }}" title="Reject or Add Comment">
                                    <i class="bi bi-x-lg"></i> Reject / Note
                                </button>
                            @else
                                <button class="btn btn-sm btn-outline-secondary px-2 py-1" data-bs-toggle="modal" data-bs-target="#review-{{ $leave->id }}" title="Change Decision">
                                    <i class="bi bi-pencil"></i> Edit Note
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-check-all fs-1 text-success d-block mb-2"></i>
                        No leave requests submitted.
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

@push('modals')
@foreach($leaves as $leave)
<div class="modal fade" id="review-{{ $leave->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $leave->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.leaves.update', $leave) }}" class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalLabel-{{ $leave->id }}">Review Leave &mdash; {{ $leave->user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 bg-light rounded-3 mb-3">
                    <div class="small text-muted mb-1">Requested Dates:</div>
                    <div class="fw-bold text-dark">{{ $leave->from_date->format('d M Y') }} &rarr; {{ $leave->to_date->format('d M Y') }} ({{ $leave->dayCount() }} days)</div>
                    <div class="small text-muted mt-2 mb-1">Student Reason:</div>
                    <div class="text-secondary small">{{ $leave->reason }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Decision</label>
                    <select name="status" class="form-select" required>
                        <option value="approved" @selected($leave->status==='approved')>Approve Application</option>
                        <option value="rejected" @selected($leave->status==='rejected')>Reject Application</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Teacher / Administrator Comment (Optional)</label>
                    <textarea name="admin_comment" rows="3" class="form-control" placeholder="Optional notes or feedback for the student...">{{ old('admin_comment', $leave->admin_comment) }}</textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Decision</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endpush
@endsection
