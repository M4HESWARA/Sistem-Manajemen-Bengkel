@if (session('success'))
    <div class="alert alert-dismissible fade show border d-flex align-items-center justify-content-between p-3 mb-4" 
         style="background-color: var(--bg-panel); border-color: var(--magenta) !important; border-radius: 12px; color: var(--text-primary); box-shadow: 0 0 15px rgba(255, 0, 255, 0.2);" 
         role="alert">
        
        <!-- Bagian Kiri: Ikon & Pesan -->
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                 style="width: 32px; height: 32px; background: rgba(255, 0, 255, 0.15); color: var(--magenta);">
                <i class="bi bi-check-lg fw-bold"></i>
            </div>
            <div style="font-size: 0.95rem; font-weight: 500;">
                {{ session('success') }}
            </div>
        </div>

        <!-- Tombol Close (X) dengan Kotak & Efek Hover Magenta -->
        <button type="button" class="btn p-0 d-flex align-items-center justify-content-center flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"
                style="width: 30px; height: 30px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border); color: var(--text-secondary); transition: all 0.2s ease;"
                onmouseover="this.style.color='var(--magenta)'; this.style.borderColor='var(--magenta)'; this.style.background='rgba(255, 0, 255, 0.1)';"
                onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border)'; this.style.background='rgba(255, 255, 255, 0.05)';">
            <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
        </button>
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

        <button type="button" class="btn p-0 d-flex align-items-center justify-content-center flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"
                style="width: 30px; height: 30px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border); color: var(--text-secondary); transition: all 0.2s ease;"
                onmouseover="this.style.color='#ef4444'; this.style.borderColor='#ef4444'; this.style.background='rgba(239, 68, 68, 0.1)';;"
                onmouseout="this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border)'; this.style.background='rgba(255, 255, 255, 0.05)';">
            <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
        </button>
    </div>
@endif