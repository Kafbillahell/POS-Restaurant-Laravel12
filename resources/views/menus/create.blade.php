@extends('dashboard.home')

@section('content')
<div class="container-fluid">
    <h1 class="page-title mb-4">Tambah Menu</h1>
    <div class="card card-brown p-4 mb-4">
    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label class="fw-bold text-dark-brown">Kategori</label>
            <select name="kategori_id" class="form-control form-control-brown rounded-3" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label class="fw-bold text-dark-brown">Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control form-control-brown rounded-3" required value="{{ old('nama_menu') }}">
        </div>

        <div class="form-group mb-3">
            <label class="fw-bold text-dark-brown">Deskripsi</label>
            <textarea name="deskripsi" class="form-control form-control-brown rounded-3">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label class="fw-bold text-dark-brown">Harga</label>
            <input type="number" name="harga" class="form-control form-control-brown rounded-3" required step="0.01" value="{{ old('harga') }}">
        </div>

        <div class="form-group mb-3">
            <label class="fw-bold text-dark-brown">Gambar</label>
            <input type="file" name="gambar" class="form-control form-control-brown rounded-3" accept="image/*">
        </div>

        <div class="form-group mb-3">
            <label class="fw-bold text-dark-brown">Stok</label>
            <input type="number" name="stok" class="form-control form-control-brown rounded-3" required min="0" value="{{ old('stok') }}">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('menus.index') }}" class="btn btn-outline-brown rounded-pill px-4"><i class="bi bi-arrow-left"></i> Kembali</a>
            <button class="btn btn-brown rounded-pill px-4"><i class="bi bi-save"></i> Simpan</button>
        </div>
    </form>
    </div>
</div>
@endsection
