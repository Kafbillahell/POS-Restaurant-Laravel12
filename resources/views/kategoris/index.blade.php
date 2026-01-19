@extends('dashboard.home')

@section('content')

<style>
    /* KONSEP: Minimalist Monochrome (Konsisten dengan halaman User) */
    :root {
        --black: #1a1a1a;
        --gray-dark: #4a4a4a;
        --gray-light: #e5e5e5;
    }

    body {
        color: var(--black);
        font-family: sans-serif; /* Menggunakan font default sistem agar lebih cepat/bersih */
    }

    .page-title {
        font-weight: 700;
        color: var(--black);
        letter-spacing: -0.5px;
    }

    /* Kartu Flat */
    .card-minimal {
        border: 1px solid var(--gray-light);
        border-radius: 8px;
        box-shadow: none;
        background: #fff;
    }

    /* Tombol Hitam Putih */
    .btn-monochrome {
        background-color: var(--black);
        color: #fff;
        border: 1px solid var(--black);
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1.2rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-monochrome:hover {
        background-color: #fff;
        color: var(--black);
    }

    /* Tabel Minimalis */
    .table-minimal thead th {
        background-color: #fff;
        color: var(--gray-dark);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-bottom: 2px solid var(--black);
        padding: 1rem 0.75rem;
    }

    .table-minimal tbody td {
        vertical-align: middle;
        padding: 1.2rem 0.75rem;
        color: var(--black);
        border-bottom: 1px solid var(--gray-light);
    }
    
    .table-minimal tr:last-child td {
        border-bottom: none;
    }

    /* Action Links (Pengganti Tombol Warna-warni) */
    .action-link {
        color: var(--gray-dark);
        text-decoration: none;
        font-size: 0.9rem;
        margin: 0 5px;
        font-weight: 500;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
    }
    .action-link:hover {
        color: var(--black);
        text-decoration: underline;
    }
</style>

<div class="container-fluid px-4 mt-4">
    
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="page-title mb-1">Daftar Kategori</h2>
            <div style="width: 40px; height: 3px; background: #000;"></div> {{-- Aksen Garis --}}
        </div>
        <a href="{{ route('kategoris.create') }}" class="btn-monochrome">
            + Tambah Kategori
        </a>
    </div>

    {{-- Alert Minimalis --}}
    @if(session('success'))
        <div class="alert bg-white border border-dark rounded-1 mb-4 d-flex align-items-center" role="alert" style="color: #000;">
            <i class="bi bi-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card card-minimal">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-minimal w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">ID</th>
                            <th class="text-start">Nama Kategori</th>
                            <th class="text-end pe-4" style="width: 20%;">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $kategori)
                            <tr>
                                <td class="text-center text-muted">{{ $kategori->id }}</td>
                                <td class="text-start fw-bold">{{ $kategori->nama_kategori }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('kategoris.edit', $kategori->id) }}" class="action-link">
                                        Edit
                                    </a>
                                    <span class="text-muted mx-1">|</span>
                                    <button type="button" class="action-link btn-delete-kategori" 
                                        data-id="{{ $kategori->id }}"
                                        data-name="{{ $kategori->nama_kategori }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <em>Belum ada kategori yang ditambahkan.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Form untuk Delete --}}
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Scripts & Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-kategori');
        const deleteForm = document.getElementById('delete-form');

        // Style SweetAlert Monochrome
        const swalMono = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-dark px-4 py-2 rounded-1 mx-1',
                cancelButton: 'btn btn-outline-secondary px-4 py-2 rounded-1 mx-1'
            },
            buttonsStyling: false
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                swalMono.fire({
                    title: 'Hapus Kategori?',
                    text: `Kategori "${name}" akan dihapus permanen.`,
                    icon: 'warning',
                    iconColor: '#333',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteForm.action = `/kategoris/${id}`; // Pastikan rutenya sesuai
                        deleteForm.submit();
                    }
                });
            });
        });
    });
</script>

@endsection