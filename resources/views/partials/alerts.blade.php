@if (session('success'))
    <div class="ksr-alert ksr-alert-success alert alert-dismissible fade show" role="alert">
        <div class="ksr-alert-content">
            <i class="bi bi-check-circle-fill ksr-alert-icon" aria-hidden="true"></i>
            <div class="ksr-alert-body">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close ksr-alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@if (session('error'))
    <div class="ksr-alert ksr-alert-danger alert alert-dismissible fade show" role="alert">
        <div class="ksr-alert-content">
            <i class="bi bi-exclamation-circle-fill ksr-alert-icon" aria-hidden="true"></i>
            <div class="ksr-alert-body">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close ksr-alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

@if ($errors->any())
    <div class="ksr-alert ksr-alert-danger alert alert-dismissible fade show" role="alert">
        <div class="ksr-alert-content">
            <i class="bi bi-exclamation-circle-fill ksr-alert-icon" aria-hidden="true"></i>
            <div class="ksr-alert-body">
                @if ($errors->count() > 1)
                    <strong class="d-block mb-1">Ada isian yang belum lengkap:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $errors->first() }}
                @endif
            </div>
        </div>
        <button type="button" class="btn-close ksr-alert-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
