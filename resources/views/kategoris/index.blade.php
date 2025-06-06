@extends('dashboard.home')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    body, .table, .btn, h2, .card {
        font-family: 'Poppins', sans-serif;
    }

    /* --- Table Header --- */
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border: none;
        padding: 1rem 1.2rem;
        border-radius: 8px;
    }

    /* --- Table Body --- */
    .table tbody tr {
        background: #fff;
        box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
        border-radius: 8px;
        transition: transform 0.2s ease;
    }
    .table tbody tr:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 14px rgb(0 0 0 / 0.15);
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1.2rem;
    }

    /* --- Button Style --- */
    .btn {
        font-weight: 600;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-action i {
        font-size: 1rem;
    }

    .btn-success {
        background-color: #198754;
        border-color: #198754;
        color: #fff;
    }
    .btn-success:hover {
        background-color: #157347cc;
        border-color: #157347cc;
        color: #fff;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    .btn-warning:hover {
        background-color: #e0a800cc;
        border-color: #e0a800cc;
        color: #212529;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }
    .btn-danger:hover {
        background-color: #bb2d3bcc;
        border-color: #bb2d3bcc;
        color: #fff;
    }

    /* --- Card --- */
    .card {
        border: 1px solid #e3e3e3;
        border-radius: 12px;
    }

    /* --- Toast Success --- */
    #successToast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 2000;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    #successToast.show {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-primary">📂 Daftar Kategori</h2>
        <a href="{{ route('kategoris.create') }}" class="btn btn-success rounded-pill shadow-sm px-4 btn-action">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div id="successToast" class="alert alert-success shadow-sm rounded">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center m-0">
                    <thead>
                        <tr>
                            <th style="width: 10%;">ID</th>
                            <th class="text-start">Nama Kategori</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $kategori)
                            <tr>
                                <td>{{ $kategori->id }}</td>
                                <td class="text-start">{{ $kategori->nama_kategori }}</td>
                                <td>
                                    <a href="{{ route('kategoris.edit', $kategori->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 btn-action">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('kategoris.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="btn btn-danger btn-sm rounded-pill px-3 btn-action" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                        >
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }
    });
</script>
@endsection
