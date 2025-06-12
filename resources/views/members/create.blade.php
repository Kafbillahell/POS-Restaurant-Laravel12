@extends('dashboard.home')

@section('content')
<div class="container">
    <h1>Tambah Member</h1>

    <form action="{{ route('members.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
