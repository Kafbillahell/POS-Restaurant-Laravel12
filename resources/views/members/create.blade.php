@extends('dashboard.home')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h1 class="page-title mb-4">Tambah Member</h1>
    <div class="card card-brown p-4">
    <form action="{{ route('members.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="fw-bold text-dark-brown">Nama</label>
            <input type="text" name="nama" class="form-control form-control-brown rounded-3" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold text-dark-brown">Email</label>
            <input type="email" name="email" class="form-control form-control-brown rounded-3" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="fw-bold text-dark-brown">No Telepon</label>
            <input type="text" name="no_telp" class="form-control form-control-brown rounded-3" value="{{ old('no_telp') }}" required>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('members.index') }}" class="btn btn-outline-brown rounded-pill px-4">Kembali</a>
            <button type="submit" class="btn btn-brown rounded-pill px-4">Simpan</button>
        </div>
    </form>
    </div>
</div>
@endsection
