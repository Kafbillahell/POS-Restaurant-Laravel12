@extends('dashboard.home')

@section('styles')
<style>
    body, .table, .btn, h2 {
        font-family: 'Poppins', sans-serif;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-action i {
        font-size: 14px;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .card {
        border: 1px solid #e3e3e3;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-primary">📂 Daftar Kategori</h2>
        <a href="{{ route('kategoris.create') }}" class="btn btn-success rounded-pill shadow-sm px-4">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded">
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
                            <th>Nama Kategori</th>
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
