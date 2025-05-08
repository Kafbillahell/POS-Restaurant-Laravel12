@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Member</h1>

    <form action="{{ route('members.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama">Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="no_telp">No. Telp</label>
            <input type="text" name="no_telp" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
    