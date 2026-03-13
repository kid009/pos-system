@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
        <span data-feather="check-circle" class="me-2"></span>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
        <span data-feather="x-circle" class="me-2"></span>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
        <span data-feather="alert-triangle" class="me-2"></span>
        <div>{{ session('warning') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
