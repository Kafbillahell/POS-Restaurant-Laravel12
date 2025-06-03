@extends('dashboard.home')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    body, .table, .btn, h2, .card {
        font-family: 'Poppins', sans-serif;
    }

    /* --- Common Button Style --- */
    .btn {
        transition: background-color 0.3s ease, color 0.3s ease;
        font-weight: 600;
    }

    /* --- Admin Table --- */
    .table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table thead th {
        background-color: #f1f5f9;
        color: #0d6efd;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: 1rem 1.2rem;
    }

    .table tbody tr {
        background: #fff;
        box-shadow: 0 3px 8px rgb(0 0 0 / 0.1);
        border-radius: 12px;
        transition: transform 0.2s ease;
    }
    .table tbody tr:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 18px rgb(0 0 0 / 0.15);
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1.2rem;
    }

    /* Tombol dengan icon */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-action i {
        font-size: 1.1rem;
    }

    /* Image kecil di tabel */
    td img {
        border-radius: 10px;
        object-fit: cover;
        height: 50px;
        width: 50px;
        box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
        transition: transform 0.3s ease;
    }
    td img:hover {
        transform: scale(1.15);
        box-shadow: 0 6px 14px rgb(0 0 0 / 0.25);
    }

    /* --- User Cards --- */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgb(0 0 0 / 0.1);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        cursor: pointer;
        will-change: transform;
        background: #fff;
    }
    .card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 35px rgb(0 0 0 / 0.2);
        z-index: 10;
    }
    .card-img-top {
        border-radius: 16px 16px 0 0;
        object-fit: cover;
    }
    .card-body h5 {
        font-weight: 700;
        color: #0d6efd;
        transition: color 0.3s ease;
    }
    .card:hover .card-body h5 {
        color: #0953b3;
    }
    .card-body p {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .card-body .price {
        font-weight: 700;
        color: #198754;
        font-size: 1.1rem;
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
    <h2 class="mb-4 fw-semibold text-primary">📋 Daftar Menu</h2>

    {{-- Toast Success --}}
    @if (session('success') && auth()->user()->role != 'user')
        <div id="successToast" class="alert alert-success shadow-sm rounded">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (auth()->user()->role != 'user')
        {{-- Admin/Kasir/Pemilik --}}
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('menus.create') }}" class="btn btn-success rounded-pill shadow-sm px-4 btn-action">
                <i class="bi bi-plus-circle"></i> Tambah Menu
            </a>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Nama Menu</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $menu->nama_menu }}</td>
                            <td>{{ Str::limit($menu->deskripsi, 40, '...') }}</td>
                            <td class="text-end">Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $menu->stok }}</td>
                            <td class="text-center">
                                @if ($menu->gambar)
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Menu Image" />
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-warning btn-sm rounded-pill px-3 btn-action">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm rounded-pill px-3 btn-action" onclick="return confirm('Hapus menu ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    @else
        {{-- Untuk User --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="menuCards">
            @forelse ($menus as $menu)
                <div class="col">
                    <div class="card h-100 shadow-sm rounded-4 fade-in">
                        @if ($menu->gambar)
                            <img src="{{ asset('storage/' . $menu->gambar) }}" class="card-img-top rounded-top-4" style="height: 200px;" alt="Menu Image">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $menu->nama_menu }}</h5>
                            <p class="text-muted mb-2">{{ $menu->kategori->nama_kategori ?? '-' }}</p>
                            <p class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info text-center w-100">Belum ada menu tersedia.</div>
                </div>
            @endforelse
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Toast muncul dan hilang otomatis
        const toast = document.getElementById('successToast');
        if (toast) {
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        // Animasi fade-in kartu menu user (delay stagger)
        const cards = document.querySelectorAll('#menuCards .card');
        cards.forEach((card, i) => {
            card.style.opacity = 0;
            card.style.transform = 'translateY(15px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = 1;
                card.style.transform = 'translateY(0)';
            }, i * 150);
        });
    });
</script>
@endsection
