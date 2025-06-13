@extends('dashboard.home')

@section('content')
@if (auth()->user()->role !== 'kasir')
<style>
    .users-heading {
        font-size: 2rem;
        font-weight: 800;
        color: #284866;
        letter-spacing: 0.01em;
        margin-bottom: 2.3rem;
        display: flex;
        align-items: center;
        gap: .7rem;
    }
    .user-add-btn {
        font-weight: 600;
        font-size: 1.02rem;
        border-radius: 1.2rem;
        padding: .55rem 1.3rem;
        background: #f5f7fa;
        color: #284866 !important;
        border: 1px solid #c9d6e3;
        box-shadow: 0 1.5px 6px #c9d6e320;
        transition: background .14s, color .14s, box-shadow .13s, border .13s;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .user-add-btn:hover, .user-add-btn:focus {
        background: #e8f0fd;
        color: #16324f !important;
        border: 1px solid #b5c9de;
        text-decoration: none;
        box-shadow: 0 4px 18px #c9d6e340;
    }
    .user-table {
        border-radius: 1.2rem !important;
        overflow: hidden;
        box-shadow: 0 6px 24px #c9d6e315, 0 2px 8px #c9d6e30a;
        margin-bottom: 2rem;
        background: #f7fafc;
    }
    .user-table thead th {
        background: linear-gradient(90deg, #f7fafc 65%, #e7eef6 100%) !important;
        color: #284866 !important;
        font-weight: 800;
        font-size: 1.05rem;
        vertical-align: middle;
        letter-spacing: 0.01em;
        border-bottom: 2px solid #e3e9f0;
    }
    .user-table tbody tr {
        font-size: 1.01rem;
        background: #fff;
        transition: background .12s;
    }
    .user-table tbody tr:hover {
        background: #f0f6fb;
    }
    .user-table .badge {
        font-size: .93rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        border-radius: 1rem;
        box-shadow: 0 2px 6px #e7eef617;
        background: #e8f0fd;
        color: #284866;
        border: 1px solid #d2dbe7;
    }
    .user-table .badge.admin {
        background: #d9e6f6;
        color: #205d97;
        border: 1px solid #c3d2e8;
    }
    .user-table .badge.pemilik {
        background: #ececec;
        color: #444;
        border: 1px solid #d6d6d6;
    }
    .user-table .badge.kasir {
        background: #fdf3e9;
        color: #b86a26;
        border: 1px solid #f5d8b6;
    }

    .btn-warning {
        background: #f8f9fa;
        color: #c4861c;
        font-weight: 600;
        border-radius: 1.2rem;
        border: 1px solid #eedca9;
        box-shadow: 0 2px 8px #eedca91a;
        transition: background .13s, color .13s, border .13s;
    }
    .btn-warning:hover, .btn-warning:focus {
        background: #fffbe9;
        color: #be951a;
        border: 1px solid #e3c464;
    }
    .btn-danger {
        background: #faf2f2;
        color: #c0392b;
        font-weight: 600;
        border-radius: 1.2rem;
        border: 1px solid #eecac9;
        box-shadow: 0 2px 8px #eecac91a;
        transition: background .13s, color .13s, border .13s;
    }
    .btn-danger:hover, .btn-danger:focus {
        background: #fff3f2;
        color: #a93226;
        border: 1px solid #e3a59e;
    }
    .alert-success {
        font-size: 1.04rem;
        border-radius: 1rem;
        font-weight: 600;
        background: #e7f7ed;
        color: #2e6051;
        border: 1px solid #b9dbcc;
    }
    @media (max-width: 900px) {
        .users-heading { font-size: 1.18rem; }
        .user-table { font-size: .98rem; }
    }
</style>
<div class="container mt-4">
    <h2 class="users-heading"><i class="bi bi-people-fill"></i> Manajemen Pengguna</h2>

    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('users.create') }}" class="user-add-btn shadow-sm">
            <i class="bi bi-person-plus-fill"></i> Tambah User
        </a>
    </div>

    <div class="table-responsive user-table rounded-4">
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
                        <span class="badge px-3 py-2 text-uppercase
                            {{ $user->role === 'admin' ? 'admin' : ($user->role === 'pemilik' ? 'pemilik' : ($user->role === 'kasir' ? 'kasir' : '')) }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm me-2">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                           <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm btn-delete-user" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
    <i class="bi bi-trash3-fill"></i> Hapus
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@else
<div class="container mt-4">
    <div class="alert alert-info shadow-sm rounded-3">
        <i class="bi bi-info-circle"></i> Anda tidak memiliki akses untuk melihat data pengguna.
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-user');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Yakin hapus user?',
                    html: `User <strong>${userName}</strong> akan dihapus.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/users/${userId}`;
                        form.innerHTML = `
                            @csrf
                            @method('DELETE')
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@endsection