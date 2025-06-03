@extends('dashboard.home')

@section('content')
@if (auth()->user()->role !== 'kasir')
<div class="container mt-4">
    <h2 class="mb-4 fw-semibold">👥 Manajemen Pengguna</h2>

    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-success rounded-pill shadow-sm">
            + Tambah User
        </a>
    </div>

    <div class="table-responsive shadow-sm rounded-4">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">
                    <span class="badge rounded-pill px-3 py-2 fw-semibold text-uppercase"
                        style="background-color: {{ 
                            $user->role === 'admin' ? '#0d6efd' : 
                            ($user->role === 'pemilik' ? '#212529' : '#6c757d') }};
                            color: #fff;">
                        {{ $user->role }}
                    </span>

                    </td>
                    <td class="text-center">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm rounded-pill px-3">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada pengguna yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="container mt-4">
    <div class="alert alert-info shadow-sm rounded-3">
        ⚠️ Anda tidak memiliki akses untuk melihat data pengguna.
    </div>
</div>
@endif
@endsection
