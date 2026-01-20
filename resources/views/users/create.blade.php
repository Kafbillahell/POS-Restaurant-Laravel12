@extends('dashboard.home')

@section('content')
<div class="container mt-4">
    <h2 class="page-title mb-4"><i class="bi bi-person-plus-fill"></i> Tambah Pengguna Baru</h2>

    <div class="card card-brown shadow-sm rounded-4">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold text-dark-brown">Nama</label>
                    <input type="text" name="name" id="name" class="form-control form-control-brown rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-dark-brown">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-brown rounded-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold text-dark-brown">Password</label>
                    <input type="password" name="password" id="password" class="form-control form-control-brown rounded-3 @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold text-dark-brown">Role</label>
                    <select name="role" id="role" class="form-control form-control-brown form-select rounded-3 @error('role') is-invalid @enderror" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-brown rounded-pill px-4"><i class="bi bi-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-brown rounded-pill px-4"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
