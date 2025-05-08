@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Kategori</h1>

    <form action="{{ route('kategoris.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kategoris.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
