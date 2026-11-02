@if (session('success'))
    <div class="alert alert-dismissible fade show border d-flex align-items-center justify-content-between p-3 mb-4" 
         style="background-color: var(--bg-panel); border-color: var(--magenta) !important; border-radius: 12px; color: var(--text-primary); box-shadow: 0 0 15px rgba(255, 0, 255, 0.2);" 
         role="alert">
        
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                 style="width: 32px; height: 32px; background: rgba(255, 0, 255, 0.15); color: var(--magenta);">
                <i class="bi bi-check-lg fw-bold"></i>
            </div>
            <div style="font-size: 0.95rem; font-weight: 500;">
                {{ session('success') }}
            </div>
        </div>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="filter: drop-shadow(0 0 2px rgba(255,255,255,0.5));"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-dismissible fade show border d-flex align-items-center justify-content-between p-3 mb-4" 
         style="background-color: var(--bg-panel); border-color: #ef4444 !important; border-radius: 12px; color: var(--text-primary); box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);" 
         role="alert">
        
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                 style="width: 32px; height: 32px; background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                <i class="bi bi-exclamation-lg fw-bold"></i>
            </div>
            <div style="font-size: 0.95rem; font-weight: 500;">
                {{ session('error') }}
            </div>
        </div>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif