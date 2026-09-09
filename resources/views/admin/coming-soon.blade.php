@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center text-center py-5">
    <i class="bi bi-tools" style="font-size: 3rem; color: var(--bengkel-primary);"></i>
    <h2 class="fw-bold mt-3">{{ $title }}</h2>
    <p class="text-muted">Halaman ini sedang dalam pengembangan, akan segera hadir 🚧</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-bengkel mt-2">Kembali ke Dashboard</a>
</div>
@endsection
