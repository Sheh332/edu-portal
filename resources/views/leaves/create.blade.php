@extends('layouts.app')
@section('title', 'Apply for Leave')

@section('content')
<div class="mb-4">
    <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to Leaves
    </a>
    <h2 class="fw-bold tracking-tight text-dark mb-1">Apply for Leave</h2>
    <p class="text-muted mb-0 small">Send a formal leave request to your administration with reasons.</p>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('leaves.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary small">From Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="from_date" value="{{ old('from_date', now()->toDateString()) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-secondary small">To Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-event-fill"></i></span>
                                <input type="date" name="to_date" value="{{ old('to_date', now()->toDateString()) }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">Reason for Leave</label>
                        <textarea name="reason" rows="4" class="form-control" placeholder="Specify reasons (e.g., Medical illness, urgent personal work, examination preparation...)" required>{{ old('reason') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-primary px-4 d-flex align-items-center gap-2">
                            <i class="bi bi-send-fill"></i> Submit Leave Application
                        </button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="card border-0 bg-primary bg-opacity-10 shadow-none p-3">
            <div class="card-body">
                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle-fill me-1"></i> Policy & Guidelines</h6>
                <ul class="text-secondary small mb-0 ps-3">
                    <li class="mb-2">Leave applications should be submitted at least 24 hours in advance whenever possible.</li>
                    <li class="mb-2">Admin will review your request and approve or reject with comments.</li>
                    <li class="mb-2">Approved leaves will automatically mark your attendance as <strong>Leave</strong> rather than Absent.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
