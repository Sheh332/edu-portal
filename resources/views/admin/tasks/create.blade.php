@extends('layouts.app')
@section('title', 'Assign New Task')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Back to Tasks
    </a>
    <h2 class="fw-bold tracking-tight text-dark mb-1">Assign Task &amp; Project Homework</h2>
    <p class="text-muted mb-0 small">Select target class, choose whether to assign to the entire class or specific students, and provide instructions.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tasks.store') }}">
            @csrf
            
            <!-- Assignment Mode Selection -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small text-uppercase tracking-wider">How do you want to assign this task?</label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="radio" class="btn-check" name="assignment_mode" id="modeClass" value="class" {{ old('assignment_mode', 'class') === 'class' ? 'checked' : '' }} onchange="toggleAssignmentMode('class')">
                        <label class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center gap-3" for="modeClass">
                            <i class="bi bi-people-fill fs-3"></i>
                            <div>
                                <div class="fw-bold">Assign to Entire Class</div>
                                <small class="text-muted" style="font-size:0.75rem;">Every student enrolled in the selected class receives this task</small>
                            </div>
                        </label>
                    </div>

                    <div class="col-md-6">
                        <input type="radio" class="btn-check" name="assignment_mode" id="modeIndividual" value="individual" {{ old('assignment_mode') === 'individual' ? 'checked' : '' }} onchange="toggleAssignmentMode('individual')">
                        <label class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center gap-3" for="modeIndividual">
                            <i class="bi bi-person-fill fs-3"></i>
                            <div>
                                <div class="fw-bold">Assign to Individual Student</div>
                                <small class="text-muted" style="font-size:0.75rem;">Filter students by class and assign to a specific student</small>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Class Selection Dropdown -->
            <div class="row">
                <div class="col-md-6 mb-3" id="classSelectDiv">
                    <label class="form-label fw-semibold text-secondary small">Target Class / Grade *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-mortarboard"></i></span>
                        <select name="classroom_id" id="classroomSelect" class="form-select" onchange="filterStudentsByClass(this.value)">
                            <option value="">— Select Target Class —</option>
                            @foreach($classrooms as $cls)
                                <option value="{{ $cls->id }}" @selected(old('classroom_id') == $cls->id)>
                                    {{ $cls->name }} ({{ $cls->students->count() }} enrolled)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-muted" id="classStudentCountHint">Choose class to view students or assign to all.</small>
                </div>

                <!-- Individual Student Dropdown (Toggled based on mode) -->
                <div class="col-md-6 mb-3" id="studentSelectDiv" style="display:none;">
                    <label class="form-label fw-semibold text-secondary small">Select Student *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                        <select name="assigned_to" id="assignedToSelect" class="form-select">
                            <option value="">— Select Student —</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" data-class-id="{{ $s->classroom_id ?? '' }}" @selected(old('assigned_to')==$s->id)>
                                    {{ $s->name }} ({{ $s->classroom ? $s->classroom->name : 'No Class' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-secondary small">Task Title *</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-card-heading"></i></span>
                    <input name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Chapter 4 Numerical Problems / Lab Report" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary small">Task Description &amp; Requirements *</label>
                <textarea name="description" id="editor">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-send-check-fill"></i> <span id="submitTaskBtnText">Assign to Class</span>
                </button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('head')<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>@endpush
@push('scripts')
<script>
    ClassicEditor.create(document.querySelector('#editor')).catch(console.error);

    function toggleAssignmentMode(mode) {
        const studentDiv = document.getElementById('studentSelectDiv');
        const submitBtnText = document.getElementById('submitTaskBtnText');
        const studentSelect = document.getElementById('assignedToSelect');
        const classSelect = document.getElementById('classroomSelect');

        if (mode === 'individual') {
            studentDiv.style.display = 'block';
            submitBtnText.textContent = 'Assign to Selected Student';
            studentSelect.required = true;
            classSelect.required = false;
        } else {
            studentDiv.style.display = 'none';
            submitBtnText.textContent = 'Assign to All Students in Class';
            studentSelect.required = false;
            classSelect.required = true;
        }
    }

    function filterStudentsByClass(classId) {
        const studentSelect = document.getElementById('assignedToSelect');
        const options = studentSelect.querySelectorAll('option');

        let visibleCount = 0;
        options.forEach(opt => {
            if (!opt.value) return; // Keep placeholder
            const optClass = opt.getAttribute('data-class-id');
            if (!classId || optClass === classId) {
                opt.style.display = 'block';
                visibleCount++;
            } else {
                opt.style.display = 'none';
            }
        });

        // Reset value if hidden
        const selectedOpt = studentSelect.options[studentSelect.selectedIndex];
        if (selectedOpt && selectedOpt.style.display === 'none') {
            studentSelect.value = '';
        }

        const hint = document.getElementById('classStudentCountHint');
        if (classId) {
            hint.textContent = `Found ${visibleCount} enrolled student(s) in this class.`;
        } else {
            hint.textContent = 'Choose class to view students or assign to all.';
        }
    }

    // Initialize state
    const currentMode = document.querySelector('input[name="assignment_mode"]:checked')?.value || 'class';
    toggleAssignmentMode(currentMode);
    const initialClass = document.getElementById('classroomSelect').value;
    if (initialClass) filterStudentsByClass(initialClass);
</script>
@endpush
@endsection
