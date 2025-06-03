@extends('dashboard.home')

@section('content')
<div class="container-fluid">
    <h4>Tambah Menu</h4>
    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" required value="{{ old('nama_menu') }}">
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required step="0.01" value="{{ old('harga') }}">
        </div>

        <div class="form-group">
            <label>Gambar</label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required min="0" value="{{ old('stok') }}">
        </div>

        <button class="btn btn-success mt-3">Simpan</button>
    </form>
</div>
@endsection
