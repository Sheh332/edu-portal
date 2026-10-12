@extends('layouts.app')
@section('title', 'Create Account')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-3" style="background: radial-gradient(circle at 10% 20%, rgb(240, 244, 255) 0%, rgb(248, 250, 252) 90.2%);">
    <div class="row w-100 justify-content-center my-4">
        <div class="col-12 col-sm-10 col-md-9 col-lg-7 col-xl-6">
            
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle shadow-sm mb-3" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color: white; width: 68px; height: 68px;">
                    <i class="bi bi-mortarboard-fill fs-2"></i>
                </div>
                <h3 class="fw-bold tracking-tight text-dark mb-1">Join EduPortal</h3>
                <p class="text-muted small">Register as a Student or Teacher to access classes, attendance &amp; academic tasks</p>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: 1.25rem; backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-4 p-sm-5">
                    
                    @include('partials.flash')

                    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                        @csrf
                        
                        <!-- Account Role Selector: Student vs Teacher -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase tracking-wider">I am registering as:</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role_type" id="roleStudent" value="student" {{ old('role_type', 'student') === 'student' ? 'checked' : '' }} onchange="toggleRoleFields('student')">
                                    <label class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center gap-2" for="roleStudent">
                                        <i class="bi bi-person-workspace fs-4"></i>
                                        <div>
                                            <div class="fw-bold">Student</div>
                                            <small class="text-muted d-none d-sm-inline" style="font-size:0.75rem;">Enroll in a class</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role_type" id="roleTeacher" value="teacher" {{ old('role_type') === 'teacher' ? 'checked' : '' }} onchange="toggleRoleFields('teacher')">
                                    <label class="btn btn-outline-primary w-100 py-3 text-start d-flex align-items-center gap-2" for="roleTeacher">
                                        <i class="bi bi-person-video3 fs-4"></i>
                                        <div>
                                            <div class="fw-bold">Teacher / Faculty</div>
                                            <small class="text-muted d-none d-sm-inline" style="font-size:0.75rem;">Teach &amp; manage classes</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" 
                                       class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                       placeholder="e.g. Shehryar Khan" required autofocus
                                       style="border-radius: 0 0.65rem 0.65rem 0;">
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email & Phone in 2 cols -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-secondary">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email') }}" 
                                           class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                           placeholder="user@edu.test" required
                                           style="border-radius: 0 0.65rem 0.65rem 0;">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-secondary">WhatsApp Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" 
                                           class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" 
                                           placeholder="+923001234567"
                                           style="border-radius: 0 0.65rem 0.65rem 0;">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Student Class Selection Box -->
                        <div id="studentClassSection" class="p-3 mb-3 bg-light rounded-3 border">
                            <label class="form-label fw-bold text-dark small mb-1">
                                <i class="bi bi-journal-bookmark-fill text-primary me-1"></i> Which class are you studying in?
                            </label>
                            <p class="text-muted small mb-2" style="font-size:0.8rem;">Select from the existing list or type your custom class (e.g., Class 9, Class 10, Semester 3, etc.):</p>
                            
                            <select name="classroom_id" id="studentClassSelect" class="form-select mb-2" onchange="toggleCustomClassInput(this)">
                                <option value="">— Select Enrolled Class —</option>
                                @foreach($classrooms as $cls)
                                    <option value="{{ $cls->id }}" @selected(old('classroom_id') == $cls->id)>
                                        {{ $cls->name }} {{ $cls->section ? "({$cls->section})" : '' }}
                                    </option>
                                @endforeach
                                <option value="other" @selected(old('classroom_id') == 'other')>➕ Type a new / different class...</option>
                            </select>

                            <div id="customClassInputDiv" style="display: {{ old('classroom_id') == 'other' ? 'block' : 'none' }};">
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="bi bi-pencil"></i></span>
                                    <input type="text" name="new_class_name" value="{{ old('new_class_name') }}" class="form-control" placeholder="Enter Class Name (e.g. Class 11 Pre-Medical, 3rd Semester...)">
                                </div>
                            </div>
                            @error('classroom_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teacher Classes Selection Box -->
                        <div id="teacherClassSection" class="p-3 mb-3 bg-light rounded-3 border" style="display:none;">
                            <label class="form-label fw-bold text-dark small mb-1">
                                <i class="bi bi-easel-fill text-primary me-1"></i> Which class(es) do you teach?
                            </label>
                            <p class="text-muted small mb-2" style="font-size:0.8rem;">Select one or multiple classes you teach, or add a new one right now:</p>
                            
                            <div class="row g-2 mb-2" style="max-height: 140px; overflow-y: auto;">
                                @foreach($classrooms as $cls)
                                    <div class="col-sm-6">
                                        <div class="form-check p-2 bg-white rounded border">
                                            <input class="form-check-input ms-1" type="checkbox" name="teacher_class_ids[]" value="{{ $cls->id }}" id="tcls-{{ $cls->id }}"
                                                @checked(is_array(old('teacher_class_ids')) && in_array($cls->id, old('teacher_class_ids')))>
                                            <label class="form-check-label small ms-2 fw-medium text-dark" for="tcls-{{ $cls->id }}">
                                                {{ $cls->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <label class="form-label fw-semibold text-secondary small mb-1">Add Another Class (Optional):</label>
                                <input type="text" name="teacher_new_class" value="{{ old('teacher_new_class') }}" class="form-control form-control-sm" placeholder="e.g. Class 9 Physics, 4th Semester...">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" id="regPassword" name="password" 
                                       class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" 
                                       placeholder="Create strong password" required>
                                <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" id="toggleRegPassword" style="border-radius: 0 0.65rem 0.65rem 0;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            <!-- Real-time Password Strength Meter -->
                            <div class="mt-2">
                                <div class="progress" style="height: 6px; border-radius: 3px; background-color: #e2e8f0;">
                                    <div id="passwordStrengthBar" class="progress-bar transition-all" role="progressbar" style="width: 0%;"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="small text-muted" style="font-size:0.75rem;">Password Strength:</span>
                                    <span id="passwordStrengthText" class="small fw-semibold" style="font-size:0.75rem; color:#64748b;">Too short</span>
                                </div>
                            </div>

                            <!-- Password Requirement Checklist -->
                            <div class="p-3 mt-2 bg-light rounded-3 border">
                                <div class="small fw-bold text-secondary mb-2" style="font-size:0.75rem;">Password Requirements:</div>
                                <div class="row g-1 text-muted" style="font-size: 0.78rem;">
                                    <div class="col-6 req-item" id="req-length"><i class="bi bi-circle me-1"></i> Min 8 characters</div>
                                    <div class="col-6 req-item" id="req-uppercase"><i class="bi bi-circle me-1"></i> Upper &amp; lowercase</div>
                                    <div class="col-6 req-item" id="req-number"><i class="bi bi-circle me-1"></i> At least 1 number</div>
                                    <div class="col-6 req-item" id="req-symbol"><i class="bi bi-circle me-1"></i> Special symbol (!@#$)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-secondary">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" id="regConfirmPassword" name="password_confirmation" 
                                       class="form-control border-start-0 border-end-0 ps-0 @error('password_confirmation') is-invalid @enderror" 
                                       placeholder="Re-enter password" required>
                                <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" id="toggleRegConfirmPassword" style="border-radius: 0 0.65rem 0.65rem 0;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="matchNotice" class="small mt-1" style="font-size:0.75rem; display:none;"></div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <span id="submitBtnText">Complete Registration</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Already have an account?</span>
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold small text-primary ms-1">Sign In instead</a>
                    </div>

                </div>
            </div>

            <p class="text-center text-muted small mt-4 mb-0">
                &copy; {{ date('Y') }} EduPortal &bull; Teacher &amp; Student Portal
            </p>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRoleFields(role) {
        const studentSec = document.getElementById('studentClassSection');
        const teacherSec = document.getElementById('teacherClassSection');
        const submitBtnText = document.getElementById('submitBtnText');

        if (role === 'teacher') {
            studentSec.style.display = 'none';
            teacherSec.style.display = 'block';
            submitBtnText.textContent = 'Register as Teacher';
        } else {
            studentSec.style.display = 'block';
            teacherSec.style.display = 'none';
            submitBtnText.textContent = 'Register as Student';
        }
    }

    function toggleCustomClassInput(select) {
        const customDiv = document.getElementById('customClassInputDiv');
        if (select.value === 'other') {
            customDiv.style.display = 'block';
            customDiv.querySelector('input').focus();
        } else {
            customDiv.style.display = 'none';
        }
    }

    // Initialize initial state based on old input
    const initialRole = document.querySelector('input[name="role_type"]:checked')?.value || 'student';
    toggleRoleFields(initialRole);

    // Password toggles
    function setupPasswordToggle(buttonId, inputId) {
        document.getElementById(buttonId)?.addEventListener('click', function() {
            const input = document.getElementById(inputId);
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    }

    setupPasswordToggle('toggleRegPassword', 'regPassword');
    setupPasswordToggle('toggleRegConfirmPassword', 'regConfirmPassword');

    // Real-time Password Rules & Validation
    const pwdInput = document.getElementById('regPassword');
    const confInput = document.getElementById('regConfirmPassword');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    const reqLength = document.getElementById('req-length');
    const reqUpper = document.getElementById('req-uppercase');
    const reqNum = document.getElementById('req-number');
    const reqSym = document.getElementById('req-symbol');
    const matchNotice = document.getElementById('matchNotice');

    function updateReq(el, valid) {
        const icon = el.querySelector('i');
        if (valid) {
            el.classList.remove('text-muted', 'text-danger');
            el.classList.add('text-success', 'fw-medium');
            icon.className = 'bi bi-check-circle-fill me-1 text-success';
        } else {
            el.classList.remove('text-success', 'fw-medium');
            el.classList.add('text-muted');
            icon.className = 'bi bi-circle me-1';
        }
    }

    pwdInput?.addEventListener('input', function() {
        const val = this.value;
        const hasLength = val.length >= 8;
        const hasMixed = /[a-z]/.test(val) && /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSymbol = /[^A-Za-z0-9]/.test(val);

        updateReq(reqLength, hasLength);
        updateReq(reqUpper, hasMixed);
        updateReq(reqNum, hasNumber);
        updateReq(reqSym, hasSymbol);

        let score = 0;
        if (hasLength) score += 25;
        if (hasMixed) score += 25;
        if (hasNumber) score += 25;
        if (hasSymbol) score += 25;

        strengthBar.style.width = score + '%';
        if (score === 0) {
            strengthBar.className = 'progress-bar bg-secondary';
            strengthText.textContent = 'Too short';
            strengthText.style.color = '#64748b';
        } else if (score <= 50) {
            strengthBar.className = 'progress-bar bg-danger';
            strengthText.textContent = 'Weak';
            strengthText.style.color = '#dc2626';
        } else if (score === 75) {
            strengthBar.className = 'progress-bar bg-warning';
            strengthText.textContent = 'Moderate';
            strengthText.style.color = '#d97706';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            strengthText.textContent = 'Strong & Secure';
            strengthText.style.color = '#059669';
        }

        checkMatch();
    });

    confInput?.addEventListener('input', checkMatch);

    function checkMatch() {
        if (!confInput.value) {
            matchNotice.style.display = 'none';
            return;
        }
        matchNotice.style.display = 'block';
        if (pwdInput.value === confInput.value) {
            matchNotice.innerHTML = '<span class="text-success"><i class="bi bi-check2"></i> Passwords match!</span>';
        } else {
            matchNotice.innerHTML = '<span class="text-danger"><i class="bi bi-x"></i> Passwords do not match</span>';
        }
    }
</script>
@endpush
@endsection
