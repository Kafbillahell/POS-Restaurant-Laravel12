@extends('dashboard.home')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h2 class="page-title mb-4">➕ Tambah Kategori</h2>

    <div class="card card-brown shadow-sm p-4">
    <form action="{{ route('kategoris.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="nama_kategori" class="form-label fw-semibold text-dark-brown">Nama Kategori</label>
            <input 
                type="text" 
                class="form-control form-control-brown rounded-3 @error('nama_kategori') is-invalid @enderror" 
                id="nama_kategori" 
                name="nama_kategori" 
                value="{{ old('nama_kategori') }}"
                placeholder="Masukkan nama kategori"
                required
            >
            @error('nama_kategori')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2 justify-content-between mt-4">
            <a href="{{ route('kategoris.index') }}" class="btn btn-outline-brown rounded-pill px-4">Kembali</a>
            <button type="submit" class="btn btn-brown rounded-pill px-4">Simpan</button>
        </div>
    </form>
    </div>
</div>
@endsection
