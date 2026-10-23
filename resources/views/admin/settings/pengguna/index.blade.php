@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')

<style>
    .back-settings-link {
        color: #94A3B8 !important;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .back-settings-link:hover {
        color: #FF00FF !important;
    }
    .pengguna-table thead th {
        color: #94A3B8;
        background-color: rgba(255, 255, 255, 0.03);
        border-color: #334155;
        font-size: 0.82rem;
        font-weight: 600;
    }
    .pengguna-table tbody td {
        border-color: #334155;
    }
    .pengguna-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }
    .badge-anda {
        background-color: #334155;
        color: #E2E8F0;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.45rem;
        vertical-align: middle;
    }
    .badge-role-admin {
        background-color: rgba(74, 4, 78, 0.5);
        color: #e879f9;
        border: 1px solid rgba(217, 70, 239, 0.3);
        font-weight: 600;
    }
    .badge-role-mekanik {
        background-color: rgba(8, 51, 68, 0.5);
        color: #22d3ee;
        border: 1px solid rgba(6, 182, 212, 0.3);
        font-weight: 600;
    }
    .badge-status-aktif {
        background-color: rgba(2, 44, 34, 0.5);
        color: #22C55E;
        border: 1px solid rgba(16, 185, 129, 0.3);
        font-weight: 600;
    }
    .badge-status-nonaktif {
        background-color: rgba(51, 65, 85, 0.5);
        color: #94A3B8;
        border: 1px solid rgba(148, 163, 184, 0.3);
        font-weight: 600;
    }
    .btn-aksi {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #0F172A;
        border: 1px solid #334155;
        color: #94A3B8;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-aksi:hover {
        color: #FF00FF;
        border-color: #FF00FF;
        box-shadow: 0 0 10px rgba(255, 0, 255, 0.45);
    }
    .btn-aksi-danger {
        color: #f87171;
    }
    .btn-aksi-danger:hover {
        color: #ef4444;
        border-color: #ef4444;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }
    .btn-aksi-success {
        color: #4ade80;
    }
    .btn-aksi-success:hover {
        color: #22C55E;
        border-color: #22C55E;
        box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
    }
</style>

<a href="{{ route('admin.settings.index') }}" class="back-settings-link d-inline-block mb-2">
    <i class="bi bi-arrow-left"></i> Kembali ke Pengaturan
</a>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-0">Kelola Pengguna</h1>
        <p class="text-muted small mb-0">Akun Admin dan Mekanik yang bisa login ke sistem.</p>
    </div>
    <a href="{{ route('admin.settings.pengguna.create') }}" class="btn-magenta text-nowrap">
        <i class="bi bi-plus-lg"></i> Tambah Akun
    </a>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0 pengguna-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Kontak</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td class="fw-semibold">
                            {{ $u->full_name }}
                            @if ($u->id === auth()->id())
                                <span class="badge badge-anda ms-1">Anda</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $u->username }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $u->role === 'admin' ? 'badge-role-admin' : 'badge-role-mekanik' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $u->phone ?? $u->email ?? '-' }}</td>
                        <td class="text-center">
                            @if ($u->is_active)
                                <span class="badge rounded-pill badge-status-aktif">Aktif</span>
                            @else
                                <span class="badge rounded-pill badge-status-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.settings.pengguna.edit', $u) }}" class="btn-aksi" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.settings.pengguna.toggle-active', $u) }}"
                                          onsubmit="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?');">
                                        @csrf
                                        <button type="submit" class="btn-aksi {{ $u->is_active ? 'btn-aksi-danger' : 'btn-aksi-success' }}"
                                                title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi bi-{{ $u->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
