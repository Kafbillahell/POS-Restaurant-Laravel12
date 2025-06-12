@extends('dashboard.home')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        body,
        .table,
        .btn,
        h2,
        .card {
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
    <div class="container pt-1 pb-4">


        <h2 class="fw-semibold text-primary mb-1">📋 Daftar Menu</h2>
        <p class="text-muted mb-4 fs-6 d-flex align-items-center gap-4 flex-wrap">
            {{-- Total Menu --}}
            <span>
                <i class="bi bi-grid-fill text-primary me-1"></i>
                Total menu:
                <span
                    class="badge bg-light text-primary border border-primary fw-semibold px-3 py-1 rounded-pill shadow-sm">
                    {{ $menus->count() }}
                </span>
            </span>

            {{-- Ready Stock --}}
            <span>
                <i class="bi bi-check-circle-fill text-success me-1"></i>
                Ready stock:
                <span
                    class="badge bg-light text-success border border-success fw-semibold px-3 py-1 rounded-pill shadow-sm">
                    {{ $menus->where('stok', '>', 0)->count() }}
                </span>
            </span>

            {{-- Stok Kosong (klik untuk detail) --}}
            <span>
                <i class="bi bi-x-circle-fill text-danger me-1"></i>
                Stok kosong:
                <button
                    class="badge bg-light text-danger border border-danger fw-semibold px-3 py-1 rounded-pill shadow-sm position-relative"
                    data-bs-toggle="modal" data-bs-target="#stokKosongModal">
                    {{ $menus->where('stok', '<=', 0)->count() }}

                    @if($menus->where('stok', '<=', 0)->count() > 0)
                                <span style="
                            position: absolute;
                            top: -2px;      /* dekat pojok atas */
                            right: -2px;    /* dekat pojok kanan */
                            width: 8px;
                            height: 8px;
                            background-color: #dc3545; /* merah bootstrap */
                            border-radius: 50%;
                            box-shadow: 0 0 5px rgba(220, 53, 69, 0.7);
                        "></span>
                    @endif
                </button>


            </span>
        </p>

        <!-- Modal -->
       <div class="modal fade" id="stokKosongModal" tabindex="-1" aria-labelledby="stokKosongLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title" id="stokKosongLabel"><i class="bi bi-exclamation-circle me-2"></i>Menu Stok Kosong</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $kosongMenus = $menus->where('stok', '<=', 0);
                @endphp

                @if($kosongMenus->isEmpty())
                    <p class="text-muted text-center">Semua menu tersedia 🎉</p>
                @else
           <ul class="list-group list-group-flush">
    @foreach($kosongMenus as $menu)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $menu->nama_menu }}

            <a href="{{ route('menus.edit', $menu->id) }}" 
               class="btn btn-outline-danger btn-sm rounded-pill d-flex align-items-center gap-1"
               title="Tambah Stok">
                <i class="bi bi-plus-circle"></i>
                <span class="d-none d-sm-inline">Perlu Ditambah</span>
            </a>
        </li>
    @endforeach
</ul>



                @endif
            </div>
        </div>
    </div>
</div>






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
                            <th class="text-start">Nama Menu</th> <!-- header jadi rata kiri -->
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Gambar</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group menus per kategori (nama kategori atau 'Tanpa Kategori')
                            $groupedMenus = $menus->groupBy(fn($menu) => $menu->kategori->nama_kategori ?? 'Tanpa Kategori');
                        @endphp

                        @foreach ($groupedMenus as $kategori => $menusByKategori)
                            {{-- Judul kategori --}}
                            <tr>
                                <td colspan="8" class="fw-semibold text-primary bg-light" style="border-top: 2px solid #0d6efd;">
                                    {{ $kategori }}
                                </td>
                            </tr>

                            {{-- Loop menu per kategori --}}
                            @foreach ($menusByKategori as $menu)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="text-start">{{ $menu->nama_menu }}</td>
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
                                        @if ($menu->stok <= 0)
                                            <a href="{{ route('menus.edit', $menu->id) }}"
                                                class="btn btn-secondary btn-sm rounded-pill px-3 btn-action">
                                                <i class="bi bi-box-seam"></i> + Stok
                                            </a>
                                        @else
                                            <a href="{{ route('menus.edit', $menu->id) }}"
                                                class="btn btn-warning btn-sm rounded-pill px-3 btn-action">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                          <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="delete-form d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 btn-action delete-button">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form> 




                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Spasi antar kategori --}}
                            <tr>
                                <td colspan="8" style="padding-top: 1rem;"></td>
                            </tr>
                        @endforeach
                    </tbody>


            </div>

        @else
            {{-- Untuk User --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="menuCards">
                @forelse ($menus as $menu)
                    <div class="col">
                        <div class="card h-100 shadow-sm rounded-4 fade-in">
                            @if ($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" class="card-img-top rounded-top-4"
                                    style="height: 200px;" alt="Menu Image">
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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ✅ Toast muncul otomatis lalu menghilang
            const toast = document.getElementById('successToast');
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            }

            // ✅ Animasi fade-in kartu menu (khusus user view)
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

            // ✅ SweetAlert konfirmasi sebelum hapus
            const deleteButtons = document.querySelectorAll('.delete-button');

            if (deleteButtons.length === 0) {
                console.warn('Tidak ditemukan tombol dengan class .delete-button');
            }

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const form = this.closest('form');

                    if (!form) {
                        console.error('Form tidak ditemukan untuk tombol hapus ini');
                        return;
                    }

                    Swal.fire({
                        title: 'Yakin ingin menghapus menu ini?',
                        text: 'Data yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Mengirim form...');
                            form.submit();
                        } else {
                            console.log('Penghapusan dibatalkan');
                        }
                    });
                });
            });
        });
    </script>
@endsection

