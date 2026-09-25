@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<a href="{{ route('admin.settings.index') }}" class="text-decoration-none text-muted d-inline-block mb-2">
    <i class="bi bi-arrow-left"></i> Kembali ke Pengaturan
</a>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h4 fw-bold mb-0">Kelola Pengguna</h1>
        <p class="text-muted small mb-0">Akun Admin dan Mekanik yang bisa login ke sistem.</p>
    </div>
    <a href="{{ route('admin.settings.pengguna.create') }}" class="btn btn-bengkel">
        <i class="bi bi-plus-lg"></i> Tambah Akun
    </a>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
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
                                <span class="badge bg-light text-muted border">Anda</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $u->username }}</td>
                        <td>
                            <span class="badge {{ $u->role === 'admin' ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info-emphasis' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $u->phone ?? $u->email ?? '-' }}</td>
                        <td class="text-center">
                            @if ($u->is_active)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.settings.pengguna.edit', $u) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.settings.pengguna.toggle-active', $u) }}"
                                          onsubmit="return confirm('{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $u->is_active ? 'danger' : 'success' }}">
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
