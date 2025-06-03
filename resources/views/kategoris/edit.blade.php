@extends('dashboard.home')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h2 class="mb-4 fw-semibold text-primary">✏️ Edit Kategori</h2>

    <form action="{{ route('kategoris.update', $kategori->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_kategori" class="form-label fw-semibold">Nama Kategori</label>
            <input 
                type="text" 
                class="form-control @error('nama_kategori') is-invalid @enderror" 
                id="nama_kategori" 
                name="nama_kategori" 
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                placeholder="Masukkan nama kategori"
                required
            >
            @error('nama_kategori')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary rounded-pill px-4">Update</button>
            <a href="{{ route('kategoris.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
        </div>
    </form>
</div>
@endsection
