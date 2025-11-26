@extends('dashboard.home')

@section('styles')
<style>
/* ---------------------------------------------------- */
/* A. TEMA: Minimalist Monochrome SOFT Modern */
/* ---------------------------------------------------- */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --black: #171717; /* Sedikit lebih gelap */
    --gray-dark: #333333;
    --gray-medium: #6c757d;
    --gray-light: #f5f5f5; /* Lebih lembut */
    --bg-soft: #ffffff; /* Putih bersih */
}

body {
    background-color: var(--gray-light); /* Background abu muda */
    font-family: 'Inter', sans-serif;
    color: var(--black);
}

.page-title {
    font-weight: 700; /* Sedikit dilunakkan */
    font-size: 2.1rem;
    color: var(--black);
    letter-spacing: -1px;
}

/* Modifier baru untuk area Judul agar lebih terstruktur */
.page-header-container {
    padding-bottom: 1.5rem; /* Jarak di bawah konten header */
    margin-bottom: 2rem; /* Margin yang lebih besar untuk memisahkan header dengan konten tabel */
    border-bottom: 1px solid #e0e0e0; /* Garis pemisah halus */
}


/* Card Modern: Sudut lebih bulat, shadow sangat halus */
.card-modern {
    border: 1px solid #e0e0e0;
    border-radius: 16px; /* Lebih bulat */
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04); /* Shadow lebih menyebar */
    background: var(--bg-soft);
}

/* Tombol Aksi Utama: Sudut lebih lembut */
.btn-primary-action {
    background-color: var(--black);
    color: #fff;
    border: 1px solid var(--black);
    border-radius: 12px; /* Lebih bulat */
    font-weight: 600;
    padding: 0.75rem 1.6rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary-action:hover {
    background-color: var(--gray-dark);
    color: #fff;
    border-color: var(--gray-dark);
    transform: translateY(-1px);
}

/* Alert/Toast: Sudut lebih lembut */
.alert-minimal {
    background: var(--gray-light);
    border: 1px solid #ddd;
    color: var(--black);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    font-weight: 500;
}


/* ---------------------------------------------------- */
/* B. TABEL MODERN (Soft Edges, Elevated Rows) */
/* ---------------------------------------------------- */
.table-minimal-modern {
    border-collapse: separate;
    border-spacing: 0 12px; /* Jarak antar baris ditingkatkan */
}

.table-minimal-modern thead th {
    background-color: transparent; /* Lebih bersih */
    color: var(--gray-medium);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 2px solid var(--gray-light); /* Garis pemisah halus */
    padding: 1rem 1rem;
    font-weight: 600;
}

.table-minimal-modern tbody tr {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04); /* Shadow lebih halus */
    border-radius: 14px; /* Sudut lebih bulat */
    transition: all 0.2s ease;
}

.table-minimal-modern tbody tr:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.table-minimal-modern tbody td {
    vertical-align: middle;
    padding: 1.2rem 1rem; /* Padding lebih besar */
    color: var(--black);
    border-top: none;
    border-bottom: none;
}

/* Memastikan semua sudut baris memiliki radius yang sama */
.table-minimal-modern tbody tr td:first-child {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
}
.table-minimal-modern tbody tr td:last-child {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
}

/* Avatar Huruf: Sudut bulat total, warna latar belakang lembut */
.avatar-simple {
    width: 44px; /* Sedikit lebih besar */
    height: 44px;
    background-color: var(--gray-light);
    color: var(--black);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
    border: 1px solid #e0e0e0;
}

/* Badge Monokrom Modern (Sudut lebih bulat) */
.badge-mono {
    padding: 0.4em 0.8em;
    font-weight: 600;
    font-size: 0.7rem;
    border-radius: 8px; /* Lebih bulat */
    text-transform: capitalize; 
    line-height: 1.5;
}

.badge-solid {
    background: var(--black);
    color: #fff;
}
.badge-outline {
    border: 1px solid var(--black);
    color: var(--black);
    background: transparent;
}
.badge-gray {
    background: #e9ecef;
    color: var(--gray-dark);
}

/* Action Buttons (Ikon & Link) */
.action-group {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    align-items: center;
}

.btn-action-icon {
    font-size: 1.1rem; /* Ikon sedikit lebih besar */
    transition: color 0.2s;
    background: none;
    border: none;
    color: var(--gray-medium);
    padding: 0.3rem;
}

.btn-action-icon:hover {
    color: var(--black);
    transform: scale(1.1);
}

.btn-action-icon.delete:hover {
    color: #dc3545; 
}

/* Perbaikan untuk tampilan tabel saat tidak ada data */
.table-minimal-modern tbody tr.no-data td {
    border-radius: 14px;
    box-shadow: none;
    background: var(--bg-soft);
}

</style>
@endsection


@section('content')
@if (auth()->user()->role !== 'kasir')
<div class="container-fluid px-4 pt-4 pb-5">
    
    {{-- Header & Aksi Utama (Diperbarui untuk membungkus tombol) --}}
    {{-- Menggunakan align-items-center untuk keselarasan vertikal yang lebih baik --}}
    <div class="d-flex justify-content-between align-items-center page-header-container">
        <div>
            {{-- Menambahkan ikon ke Judul Halaman --}}
            <h2 class="page-title mb-1 d-flex align-items-center">
                <i class="bi bi-people-fill me-3 fs-3 text-muted" style="line-height: 1;"></i> 
                Manajemen Pengguna
            </h2>
            <p class="text-muted mb-0 fs-6 ms-5 ps-1">Daftar lengkap akun pengguna yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary-action shadow-sm">
            <i class="bi bi-person-plus"></i> Tambah User Baru
        </a>
    </div>

    {{-- Alert Minimalis --}}
    @if(session('success'))
        <div class="alert alert-minimal alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card card-modern p-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-minimal-modern w-100 mb-0">
                    <thead class="text-start">
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th width="35%">Nama & Email</th>
                            <th class="text-center" width="20%">Role</th>
                            <th class="text-end" width="20%">Bergabung</th>
                            <th class="text-center" width="20%">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <td class="text-center text-muted fw-light">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Avatar Huruf --}}
                                    <div class="avatar-simple me-4">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $user->name }}</span>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                {{-- Logika Badge Monokrom Modern --}}
                                @if($user->role == 'admin')
                                    <span class="badge badge-mono badge-solid">Admin</span>
                                @elseif($user->role == 'pemilik')
                                    <span class="badge badge-mono badge-outline">Pemilik</span>
                                @else
                                    <span class="badge badge-mono badge-gray">Kasir</span>
                                @endif
                            </td>
                            <td class="text-end text-muted small">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <div class="action-group">
                                    <a href="{{ route('users.edit', $user) }}" class="btn-action-icon" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <button type="button" class="btn-action-icon delete btn-delete-user" 
                                        data-id="{{ $user->id }}" 
                                        data-name="{{ $user->name }}"
                                        title="Hapus Pengguna">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-data">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x-fill fs-4 d-block mb-2"></i>
                                <em>Belum ada data pengguna yang terdaftar.</em>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Form --}}
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@else
{{-- Restricted Access Minimalist --}}
<div class="container mt-5 text-center p-5">
    <div style="font-size: 4rem; color: var(--gray-medium);"><i class="bi bi-lock-fill"></i></div>
    <h3 class="fw-bold mt-3" style="letter-spacing: -1px;">AKSES DITOLAK</h3>
    <p class="text-muted fs-6">Anda tidak memiliki izin (Role Kasir) untuk mengakses halaman Manajemen Pengguna.</p>
    <a href="{{ url('/dashboard') }}" class="btn btn-primary-action mt-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-user');
        const deleteForm = document.getElementById('delete-form');

        // Style SweetAlert agar sejalan dengan tema
        const swalMono = Swal.mixin({
            customClass: {
                popup: 'rounded-3',
                title: 'fw-bold',
                confirmButton: 'btn btn-primary-action px-4 py-2 mx-2',
                cancelButton: 'btn btn-outline-secondary px-4 py-2 mx-2'
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            iconColor: '#dc3545', // Warna merah untuk ikon bahaya
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');

                swalMono.fire({
                    title: 'Hapus Pengguna?',
                    html: `<p class="text-muted">Anda yakin ingin menghapus data pengguna <strong>${userName}</strong> secara permanen?</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Atur action form dan submit
                        deleteForm.action = `/users/${userId}`;
                        deleteForm.submit();
                    }
                });
            });
        });
    });
</script>
@endsection