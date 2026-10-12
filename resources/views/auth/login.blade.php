@extends('layouts.app')
@section('title', 'Sign In')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-3" style="background: radial-gradient(circle at 10% 20%, rgb(240, 244, 255) 0%, rgb(248, 250, 252) 90.2%);">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
            
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle shadow-sm mb-3" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color: white; width: 68px; height: 68px;">
                    <i class="bi bi-calendar2-check-fill fs-2"></i>
                </div>
                <h3 class="fw-bold tracking-tight text-dark mb-1">Welcome back</h3>
                <p class="text-muted small">Sign in to your Teacher &amp; Student Portal</p>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: 1.25rem; backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-4 p-sm-5">
                    
                    @include('partials.flash')

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Work / Student Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" 
                                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       placeholder="you@example.com" required autofocus
                                       style="border-radius: 0 0.65rem 0.65rem 0;">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-semibold small text-secondary mb-0">Password</label>
                            </div>
                            <div class="input-group mt-1">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 0.65rem 0 0 0.65rem;">
                                    <i class="bi bi-shield-lock"></i>
                                </span>
                                <input type="password" id="loginPassword" name="password" 
                                       class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" 
                                       placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" id="toggleLoginPassword" style="border-radius: 0 0.65rem 0.65rem 0;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember & Options -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-secondary" for="remember">Keep me logged in</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                            <span>Sign In</span> <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold small text-primary ms-1">Create an account</a>
                    </div>

                    <!-- Demo Credentials Helper -->
                    <div class="mt-4 p-3 rounded-3 bg-light border border-dashed">
                        <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold small">
                            <i class="bi bi-info-circle-fill text-primary"></i> Quick Demo Access
                        </div>
                        <div class="d-flex flex-column gap-1 small text-muted">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong class="text-dark">Admin:</strong> admin@ams.test</span>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:0.75rem;" onclick="fillLogin('admin@ams.test','password')">Use</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong class="text-dark">Teacher:</strong> teacher@ams.test</span>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:0.75rem;" onclick="fillLogin('teacher@ams.test','password')">Use</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong class="text-dark">Student:</strong> student@ams.test</span>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:0.75rem;" onclick="fillLogin('student@ams.test','password')">Use</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <p class="text-center text-muted small mt-4 mb-0">
                &copy; {{ date('Y') }} EduPortal &bull; Teacher &amp; Student Academic Management
            </p>

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('toggleLoginPassword')?.addEventListener('click', function() {
        const input = document.getElementById('loginPassword');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });

    function fillLogin(email, password) {
        document.querySelector('input[name="email"]').value = email;
        document.getElementById('loginPassword').value = password;
    }
</script>
@endpush
@endsection
