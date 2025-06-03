@extends('dashboard.home')

@section('content')
<div class="container-fluid">
    <h4>Edit Menu</h4>
    <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $kategori->id == $menu->kategori_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" required value="{{ old('nama_menu', $menu->nama_menu) }}">
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required step="0.01" value="{{ old('harga', $menu->harga) }}">
        </div>

        <div class="form-group">
            <label>Gambar</label>
            @if($menu->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Gambar Menu" style="max-height: 150px;">
                </div>
            @endif
            <input type="file" name="gambar" class="form-control" accept="image/*">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required min="0" value="{{ old('stok', $menu->stok) }}">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
