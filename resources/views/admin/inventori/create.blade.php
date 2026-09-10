@extends('layouts.admin')

@section('title', 'Tambah Sparepart')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Tambah Sparepart</h1>
    <p class="text-muted small mb-0">Tambahkan data sparepart baru ke inventaris.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ada isian yang belum lengkap:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card p-4">
    <form method="POST" action="{{ route('admin.inventori.store') }}">
        @csrf
        @include('admin.inventori._form')

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.inventori') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-bengkel px-4"><i class="bi bi-check-circle"></i> Simpan</button>
        </div>
    </form>
</div>

@endsection
