@extends('layouts.admin')

@section('title', 'Edit Sparepart')

@section('content')

<div class="mb-4">
    <h1 class="h4 fw-bold mb-0">Edit Sparepart</h1>
    <p class="text-muted small mb-0">{{ $sparepart->name }} ({{ $sparepart->sku }})</p>
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
    <form method="POST" action="{{ route('admin.inventori.update', $sparepart) }}">
        @csrf
        @method('PUT')
        @include('admin.inventori._form')

        <div class="d-flex justify-content-between align-items-center mt-4">
            <p class="text-muted small mb-0">
                Stok saat ini: <strong>{{ $sparepart->stok }} {{ $sparepart->satuan }}</strong>
                — untuk menambah stok, gunakan tombol "Tambah Stok" di halaman daftar inventori.
            </p>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.inventori') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-bengkel px-4"><i class="bi bi-check-circle"></i> Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>

@endsection
