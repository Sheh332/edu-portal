@extends('layouts.app')
@section('title', 'Academic Classes & Sections')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold tracking-tight text-dark mb-1">Classes &amp; Academic Sections 🏫</h2>
        <p class="text-muted mb-0 small">Create and manage classes (e.g. Class 9, Class 10, Intermediate, Semesters) and view student enrollments.</p>
    </div>
    <div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <i class="bi bi-plus-lg"></i> Add New Class
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Class Cards -->
    <div class="col-12">
        <div class="row g-3">
            @forelse($classrooms as $cls)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm p-3 position-relative" style="border-radius:1rem; border-top: 4px solid #4f46e5 !important;">
                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary text-uppercase">{{ $cls->code ?? 'CLASS' }}</span>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 p-1" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li>
                                                <a class="dropdown-item small text-primary" href="{{ route('admin.classrooms.show', $cls) }}">
                                                    <i class="bi bi-people me-1"></i> View Students
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.classrooms.destroy', $cls) }}" onsubmit="return confirm('Delete this class? Enrolled students will be unassigned.')">
                                                    @csrf @method('DELETE')
                                                    <button class="dropdown-item small text-danger">
                                                        <i class="bi bi-trash me-1"></i> Delete Class
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $cls->name }}</h5>
                                <p class="text-muted small mb-3">{{ $cls->section ?: 'Standard Curriculum Section' }}</p>
                            </div>

                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold fs-5 text-dark">{{ $cls->students_count }}</span>
                                    <span class="text-muted small">Students</span>
                                </div>
                                <a href="{{ route('admin.classrooms.show', $cls) }}" class="btn btn-sm btn-outline-primary px-3">
                                    Manage Class &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-mortarboard fs-1 text-muted d-block mb-2"></i>
                    <h5 class="text-dark">No classes created yet</h5>
                    <p class="text-muted small">Click "Add New Class" to create your first class (e.g., Class 9, Class 10, etc.).</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end">
    {{ $classrooms->links('pagination::bootstrap-5') }}
</div>

@push('modals')
<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.classrooms.store') }}" class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addClassModalLabel">Create Academic Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Class / Grade Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Class 9, Class 10 (Matric), Semester 3" required>
                    <small class="text-muted">Students will see this exact class name during registration.</small>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold text-secondary small">Class Code (Optional)</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. SSC-9, CL-10">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-semibold text-secondary small">Section / Group</label>
                        <input type="text" name="section" class="form-control" placeholder="e.g. Science, Pre-Med">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Create Class</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
