@extends('layouts.app')
@section('title', 'Grade Criteria & Performance')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">Grading System &amp; Criteria</h2>
    <p class="text-muted mb-0 small">Define performance tiers based on present days and monitor student achievements.</p>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">Grade Scale Configuration</h6>
                <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#addGrade">
                    <i class="bi bi-plus-lg"></i> Add Tier
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Days Range</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($grades as $g)
                        <tr>
                            <td>
                                <span class="badge bg-primary px-3 py-2 fs-6 fw-bold shadow-xs">{{ $g->grade }}</span>
                            </td>
                            <td class="fw-semibold text-dark">{{ $g->min_days }} &ndash; {{ $g->max_days }} days</td>
                            <td class="text-secondary small">{{ $g->description ?? '—' }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editGrade-{{ $g->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.grades.destroy', $g) }}" class="d-inline" onsubmit="return confirm('Delete this grade scale?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No grade scale levels defined.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-dark mb-0">Current Student Grade Standings</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th class="text-center">Total Present</th>
                            <th class="text-center">Assigned Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($summary as $row)
                        <tr>
                            <td>
                                <span class="fw-semibold text-dark">{{ $row['student']->name }}</span>
                                <div class="text-muted small">{{ $row['student']->email }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $row['present'] }} days</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary px-3 py-1 fw-bold fs-6">{{ $row['grade'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No student records to grade.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Modal: Add Grade -->
<div class="modal fade" id="addGrade" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.grades.store') }}" class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addGradeModalLabel">Create Grade Tier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Grade Label</label>
                    <input name="grade" class="form-control" placeholder="e.g. A+, A, B, Good, Distinction" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold text-secondary small">Min Present Days</label>
                        <input type="number" name="min_days" class="form-control" placeholder="e.g. 20" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold text-secondary small">Max Present Days</label>
                        <input type="number" name="max_days" class="form-control" placeholder="e.g. 30" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Description / Comment</label>
                    <input name="description" class="form-control" placeholder="e.g. Excellent attendance consistency">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Create Grade</button>
            </div>
        </form>
    </div>
</div>

<!-- Modals: Edit Grade -->
@foreach($grades as $g)
<div class="modal fade" id="editGrade-{{ $g->id }}" tabindex="-1" aria-labelledby="editGradeLabel-{{ $g->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.grades.update', $g) }}" class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
            @csrf @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="editGradeLabel-{{ $g->id }}">Edit Grade &mdash; {{ $g->grade }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Grade Label</label>
                    <input name="grade" value="{{ $g->grade }}" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold text-secondary small">Min Present Days</label>
                        <input type="number" name="min_days" value="{{ $g->min_days }}" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold text-secondary small">Max Present Days</label>
                        <input type="number" name="max_days" value="{{ $g->max_days }}" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Description</label>
                    <input name="description" value="{{ $g->description }}" class="form-control">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endpush
@endsection
