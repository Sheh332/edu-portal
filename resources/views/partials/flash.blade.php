@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert" style="background:#ecfdf5; color:#065f46; border-radius:0.75rem;">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert" style="background:#fef2f2; color:#991b1b; border-radius:0.75rem;">
        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
        <div class="flex-grow-1">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background:#fef2f2; color:#991b1b; border-radius:0.75rem;">
        <div class="d-flex align-items-center gap-2 mb-2 fw-semibold">
            <i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>
            <span>Please correct the following errors:</span>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
