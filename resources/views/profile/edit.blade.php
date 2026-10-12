@extends('layouts.app')
@section('title', 'Profile Settings')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">Account &amp; Profile Settings</h2>
    <p class="text-muted mb-0 small">Update your personal information and profile picture.</p>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                
                <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom">
                    <div class="position-relative">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/'.$user->profile_picture) }}" class="rounded-circle shadow-sm border" width="100" height="100" style="object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fs-1 fw-bold border" style="width:100px; height:100px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-2">{{ $user->email }}</p>
                        <span class="badge bg-primary">
                            {{ $user->isAdmin() ? 'Administrator' : 'Student Account' }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" value="{{ $user->email }}" class="form-control bg-light" disabled>
                        </div>
                        <small class="text-muted">Email address is managed by the organization administrator.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Phone Number (WhatsApp Alerts)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="+1234567890">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">Upload New Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control" accept="image/*">
                        <small class="text-muted">PNG, JPG, or WebP up to 2MB.</small>
                    </div>

                    <button class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check2"></i> Save Profile Changes
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
