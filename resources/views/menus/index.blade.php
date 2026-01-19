@extends('dashboard.home')

@section('content')

<style>
    /* KONSEP: Minimalist Monochrome & Soft Grid */
    :root {
        --black: #1a1a1a;
        --gray-dark: #4a4a4a;
        --gray-light: #e5e5e5;
        --off-white: #f5f5f5; /* Background yang lebih soft */
        --white: #ffffff;
    }

    body {
        color: var(--black);
        font-family: 'Poppins', sans-serif;
        background-color: var(--off-white);
    }

    .page-title {
        font-weight: 700;
        color: var(--black);
        letter-spacing: -0.5px;
    }

    /* --- Statistik Boxes (Header) --- */
    .stat-box {
        /* Perubahan: Border lebih tipis, radius lebih besar, dan shadow halus */
        border: none; 
        background-color: var(--white);
        padding: 1rem 1.5rem;
        border-radius: 12px; /* Lebih Bulat */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); /* Bayangan modern */
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 150px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    .stat-label {
        font-size: 0.7rem; /* Sedikit lebih kecil */
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gray-dark);
        margin-bottom: 0.3rem;
    }
    .stat-value {
        font-size: 1.8rem; /* Sedikit lebih besar */
        font-weight: 700;
        line-height: 1;
    }
    
    /* Tombol Peringatan Stok Kosong (Link Style) */
    .stat-box.alert-mode {
        cursor: pointer;
    }
    .stat-box.alert-mode:hover {
        background-color: #fff8f8;
    }
    .indicator-dot {
        height: 8px; width: 8px; background: var(--black); border-radius: 50%; display: inline-block; margin-left: 5px;
    }

    /* --- Buttons --- */
    .btn-monochrome {
        background-color: var(--black);
        color: #fff;
        border: 1px solid var(--black);
        border-radius: 4px; /* Sudut lebih lembut */
        font-weight: 500;
        padding: 0.6rem 1.5rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-monochrome:hover {
        background-color: #fff;
        color: var(--black);
        box-shadow: 0 0 0 1px var(--black) inset;
    }

    .btn-outline-mono {
        background: transparent;
        border: 1px solid var(--gray-light);
        color: var(--black);
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-outline-mono:hover {
        border-color: var(--black);
        background: var(--black);
        color: #fff;
    }

    /* --- Table Container (Card Minimal) --- */
    .card-minimal {
        background-color: var(--white);
        border: none; /* Menghilangkan border tebal */
        border-radius: 12px; /* Lebih Bulat */
        overflow: hidden; 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); /* Bayangan halus */
    }
    /* --- Table Styling (Admin) --- */
    .table-minimal {
        background-color: var(--white);
    }
    .table-minimal thead th {
        background-color: var(--white);
        color: var(--black);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-bottom: 1px solid var(--gray-light); /* Lebih tipis */
        padding: 1rem 0.75rem;
    }
    .table-minimal tbody td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
        border-bottom: 1px solid var(--gray-light);
    }
    .table-minimal tbody tr:last-child td {
        border-bottom: none;
    }
    /* Baris kategori */
    .table-minimal .bg-light {
        background-color: var(--off-white) !important;
        border-bottom: 1px solid var(--gray-light);
    }
    .menu-thumb {
        width: 50px; height: 50px; object-fit: cover; border: 1px solid var(--gray-light); border-radius: 4px;
    }
    .action-link {
        font-size: 0.85rem; color: var(--gray-dark); text-decoration: none; margin: 0 5px; cursor: pointer; border: none; background: none;
    }
    .action-link:hover { color: var(--black); text-decoration: underline; }

    /* --- Card Styling (User View) --- */
    .menu-card {
        border: 1px solid transparent; 
        background: #fff;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        border-radius: 12px; /* Lebih Bulat */
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .menu-card:hover {
        border-color: var(--gray-light);
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
    .card-img-wrapper {
        border-radius: 12px 12px 0 0; /* Hanya di atas */
        position: relative;
        overflow: hidden;
        aspect-ratio: 1/1; 
        background: var(--off-white);
    }
    .menu-card-img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.5s ease;
        filter: grayscale(10%); 
    }
    .menu-card:hover .menu-card-img {
        transform: scale(1.05);
        filter: grayscale(0%); 
    }
    .sold-out-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex; justify-content: center; align-items: center;
        z-index: 2;
    }
    .sold-out-text {
        border: 2px solid var(--black);
        color: var(--black);
        padding: 0.5rem 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        background: #fff;
        border-radius: 4px;
    }
    .card-info {
        padding: 1rem; /* Tambah padding sedikit */
        text-align: center;
    }
    .menu-cat {
        font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--gray-dark);
    }
    .menu-title {
        font-size: 1.1rem; font-weight: 600; margin: 0.4rem 0; color: var(--black);
    }
    .menu-price {
        font-weight: 500; color: var(--black); font-size: 1rem;
    }

    /* --- Modal Monochrome --- */
    .modal-content { border-radius: 8px; border: none; } /* Lebih lembut */
    .modal-header { border-bottom: 1px solid var(--gray-light); background: #fff; color: var(--black); }
    .modal-title { font-weight: 700; letter-spacing: -0.5px; }
    .list-group-item { border-color: var(--gray-light); }
</style>

<div class="container py-4">
    
    {{-- Header & Stats DIBUNGKUS DALAM TAG <header> --}}
    <header class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-4">
        <div>
            <h2 class="page-title mb-1">Daftar Menu</h2>
            <div style="width: 40px; height: 3px; background: #000; margin-bottom: 2rem;"></div>
            
            {{-- Statistik Minimalis (3 Kotak/Widget) --}}
            <div class="d-flex flex-wrap gap-3">
                <div class="stat-box">
                    <span class="stat-label">Total Item</span>
                    <span class="stat-value">{{ $menus->count() }}</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">Tersedia</span>
                    <span class="stat-value">{{ $menus->where('stok', '>', 0)->count() }}</span>
                </div>
                {{-- Tombol Trigger Modal --}}
                <div class="stat-box alert-mode" data-bs-toggle="modal" data-bs-target="#stokKosongModal">
                    <span class="stat-label text-danger">Stok Habis</span>
                    <div class="d-flex align-items-center">
                        <span class="stat-value text-danger">{{ $menus->where('stok', '<=', 0)->count() }}</span>
                        @if($menus->where('stok', '<=', 0)->count() > 0)
                            <span class="indicator-dot ms-2 bg-danger"></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->role != 'user')
            <a href="{{ route('menus.create') }}" class="btn-monochrome shadow-sm">
                <i class="bi bi-plus-lg"></i> Tambah Menu
            </a>
        @endif
    </header>
    {{-- AKHIR TAG </header> --}}

    {{-- Alert Minimalis --}}
    @if(session('success'))
        <div id="successToast" class="alert bg-white border border-dark rounded-4 mb-4 d-flex align-items-center" role="alert" style="color: #000;">
            <i class="bi bi-check-circle me-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- KONTEN UTAMA --}}
    @if (auth()->user()->role != 'user')
        
        {{-- TAMPILAN ADMIN (TABEL BERSIH) DIBUNGKUS CARD MODERN --}}
        <div class="card card-minimal">
            <div class="table-responsive">
                <table class="table table-minimal w-100 mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="10%">Gambar</th>
                            <th width="15%">Kategori</th>
                            <th width="25%">Nama Menu</th>
                            <th width="15%">Harga</th>
                            <th class="text-center" width="10%">Stok</th>
                            <th class="text-end pe-4" width="20%">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedMenus = $menus->groupBy(fn($menu) => $menu->kategori->nama_kategori ?? 'Tanpa Kategori');
                        @endphp

                        @foreach ($groupedMenus as $kategori => $menusByKategori)
                            {{-- Header Kategori dalam Tabel --}}
                            <tr>
                                <td colspan="7" class="bg-light fw-bold text-uppercase fs-7 py-2 ps-3" style="letter-spacing: 1px;">
                                    {{ $kategori }}
                                </td>
                            </tr>

                            @foreach ($menusByKategori as $menu)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    @if ($menu->gambar)
                                        <img src="{{ asset('storage/' . $menu->gambar) }}" class="menu-thumb" alt="img">
                                    @else
                                        <div class="menu-thumb d-flex align-items-center justify-content-center bg-light text-muted small">N/A</div>
                                    @endif
                                </td>
                                <td class="text-muted small text-uppercase">{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                                <td class="fw-bold">{{ $menu->nama_menu }}</td>
                                <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($menu->stok <= 0)
                                        <span class="badge bg-dark rounded-1">HABIS</span>
                                    @else
                                        {{ $menu->stok }}
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('menus.edit', $menu->id) }}" class="action-link">Edit</a>
                                    <span class="text-muted mx-1">|</span>
                                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-link delete-button">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        
        {{-- TAMPILAN USER (GRID MINIMALIS) --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="menuCards">
            @forelse ($menus as $menu)
                <div class="col">
                    <div class="menu-card">
                        <div class="card-img-wrapper">
                            @if($menu->stok <= 0)
                                <div class="sold-out-overlay">
                                    <div class="sold-out-text">Sold Out</div>
                                </div>
                            @endif
                            
                            @if ($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" class="menu-card-img" alt="{{ $menu->nama_menu }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted">
                                    <i class="bi bi-image fs-1 opacity-25"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-info">
                            <div class="menu-cat">{{ $menu->kategori->nama_kategori ?? 'Umum' }}</div>
                            <h5 class="menu-title">{{ $menu->nama_menu }}</h5>
                            <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                            @if($menu->stok > 0)
                                <small class="text-muted" style="font-size: 0.75rem;">Stok: {{ $menu->stok }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada menu yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

    @endif
</div>

{{-- MODAL STOK KOSONG (RE-STYLED) --}}
<div class="modal fade" id="stokKosongModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">Stok Kosong</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @php $kosongMenus = $menus->where('stok', '<=', 0); @endphp

                @if($kosongMenus->isEmpty())
                    <div class="p-4 text-center text-muted">Semua stok aman. Tidak ada item kosong.</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($kosongMenus as $menu)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="fw-bold d-block">{{ $menu->nama_menu }}</span>
                                    <small class="text-muted">Current: {{ $menu->stok }}</small>
                                </div>

                                {{-- FORM TAMBAH STOK (Logika dipertahankan, Style diubah) --}}
                                <form action="{{ route('menus.update.stok', $menu->id) }}" method="POST" class="form-add-stok d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PUT')

                                    <button type="button" class="btn-outline-mono btn-initial-stok" title="Klik untuk Tambah">
                                        + Isi Stok
                                    </button>

                                    <input type="number" name="stok_tambahan" placeholder="Qty" 
                                        class="form-control form-control-sm text-center input-stok-tambah d-none" 
                                        style="width: 70px; border-radius: 4px; border-color: #000;" min="1">

                                    <button type="submit" class="btn btn-dark btn-sm d-none btn-submit-stok rounded-1">
                                        OK
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            // --- Logic Form Stok Kosong (Sama seperti sebelumnya, disesuaikan selector) ---
            const initialStokButtons = document.querySelectorAll('.btn-initial-stok');
            initialStokButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('.form-add-stok');
                    const input = form.querySelector('.input-stok-tambah');
                    const submitBtn = form.querySelector('.btn-submit-stok');

                    this.classList.add('d-none');
                    input.classList.remove('d-none');
                    submitBtn.classList.remove('d-none');
                    input.focus();
                    input.required = true;
                });
            });

            // --- AJAX Submit Stok (Dipertahankan) ---
            const stokForms = document.querySelectorAll('.form-add-stok');
            stokForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const url = form.getAttribute('action');
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalButtonHtml = submitButton.innerHTML;
                    const stokInput = form.querySelector('.input-stok-tambah');

                    if (stokInput.value === '' || parseInt(stokInput.value) <= 0) {
                        Swal.fire({ title: 'Invalid', text: 'Jumlah harus > 0', icon: 'warning', confirmButtonColor: '#000' });
                        return;
                    }

                    submitButton.disabled = true;
                    submitButton.innerHTML = '...';

                    fetch(url, {
                        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal update.');
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success', title: 'Berhasil', text: 'Stok diperbarui',
                            showConfirmButton: false, timer: 1500, iconColor: '#000'
                        }).then(() => {
                             window.location.reload();
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal update stok', confirmButtonColor: '#000' });
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonHtml;
                    });
                });
            });

            // --- SweetAlert Delete (Monochrome Style) ---
            const deleteButtons = document.querySelectorAll('.delete-button');
            const swalMono = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-dark px-4 py-2 rounded-1 mx-1',
                    cancelButton: 'btn btn-outline-secondary px-4 py-2 rounded-1 mx-1'
                },
                buttonsStyling: false
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    swalMono.fire({
                        title: 'Hapus Menu?',
                        text: 'Data tidak bisa dikembalikan.',
                        icon: 'warning',
                        iconColor: '#333',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // --- Animation Fade In Card ---
            const cards = document.querySelectorAll('#menuCards .menu-card');
            cards.forEach((card, i) => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease, box-shadow 0.3s';
                    card.style.opacity = 1;
                    card.style.transform = 'translateY(0)';
                }, i * 100);
            });
        });
    </script>
@endsection