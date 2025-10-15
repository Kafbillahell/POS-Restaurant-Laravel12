@extends('dashboard.home')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="welcome-banner px-4 py-4 mb-4">
                @php
                    $user = auth()->user();
                    $role = $user->role;
                    $isAdmin = $role === 'admin';
                    $isKasir = $role === 'kasir';
                @endphp
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div>
                        <div class="welcome-message mb-2">
                            @if($isAdmin)
                                <span class="welcome-icon me-2"><i class="bi bi-person-badge-fill text-success"></i></span>
                                <span>Selamat Datang, <b>{{ $user->name }}</b></span>
                            @elseif($isKasir)
                                <span class="welcome-icon me-2"><i class="bi bi-person-vcard-fill text-info"></i></span>
                                <span>Selamat Datang, <b>{{ $user->name }}</b></span>
                            @else
                                <span class="welcome-icon me-2"><i class="bi bi-emoji-smile text-warning"></i></span>
                                <span>Selamat, <b>{{ $user->name }}</b></span>
                            @endif
                        </div>
                        <div class="dashboard-title mb-1">
                            <i class="bi bi-speedometer2 text-secondary"></i> Dashboard
                        </div>
                        <div class="dashboard-subtitle">
                            @if($isAdmin)
                                Kelola restoran, pantau stok, dan lihat laporan pendapatan di sini.
                            @elseif($isKasir)
                                Buat pesanan dan kelola transaksi pelanggan dengan mudah.
                            @else
                                Jelajahi menu terbaik kami dan nikmati pengalaman kuliner terbaik!
                            @endif
                        </div>
                        <div class="bio-message mt-3">
                            @if($isAdmin)
                                Anda memiliki akses penuh untuk mengelola dan memantau seluruh operasional restoran. Pantau stok, atur menu, kelola kasir, dan dapatkan laporan pendapatan secara real-time dengan mudah dan efisien.
                            @elseif($isKasir)
                                Nikmati kemudahan dalam membuat pesanan dan mengelola transaksi pelanggan setiap hari. Pastikan pelayanan terbaik untuk pengalaman makan yang memuaskan!
                            @else
                                Temukan ragam menu favorit yang disiapkan dengan bahan berkualitas. Jadikan setiap kunjungan Anda menjadi momen istimewa bersama keluarga dan sahabat.
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 mt-md-0">
                        @if($isAdmin)
                            <a href="{{ route('reports.index') }}" class="lihat-menu-btn">
                                <i class="bi bi-bar-chart-line-fill me-1"></i> Lihat Laporan
                            </a>
                        @elseif($isKasir)
                            <a href="{{ route('orders.index') }}" class="lihat-menu-btn">
                                <i class="bi bi-receipt-cutoff me-1"></i> Buat Pesanan
                            </a>
                        @else
                            <a href="{{ route('menus.index') }}" class="lihat-menu-btn mb-4">
                                <i class="bi bi-list-ul me-1"></i> Lihat Semua Menu
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-banner {
    width: 100%;
    background: linear-gradient(90deg, #f8fafc 70%, #d2e6fa 100%);
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px rgba(44,62,80,0.09), 0 2px 8px rgba(44,62,80,0.04);
    margin-top: 2rem;
    margin-bottom: 2rem;
    min-height: 180px;
}
.welcome-message {
    font-size: 1.7rem;
    font-weight: 700;
    color: #1b5e20;
    letter-spacing: 0.01em;
    display: flex;
    align-items: center;
    margin-bottom: .3rem;
}
.dashboard-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #195b9a;
    letter-spacing: 0.03em;
    margin-bottom: .2rem;
}
.dashboard-subtitle {
    color: #7b8a9e;
    font-size: 1.12rem;
    font-weight: 500;
    margin-bottom: 0.3rem;
}
.bio-message {
    background: #e4f0f7;
    border-radius: .9rem;
    padding: .8rem 1.4rem;
    color: #294b63;
    font-size: 1.01rem;
    font-weight: 500;
    max-width: 700px;
    box-shadow: 0 2px 10px #36b37e09;
}
.lihat-menu-btn {
    background: linear-gradient(90deg, #ffe082 0%, #ffd54f 100%);
    color: #663c00;
    font-weight: 600;
    font-size: 1.07rem;
    border: none;
    border-radius: 1.2rem;
    padding: .7rem 1.5rem;
    transition: background .16s, color .16s, box-shadow .16s;
    box-shadow: 0 2px 8px #ffd54f30;
    text-decoration: none;
    display: inline-block;
}
.lihat-menu-btn:hover, .lihat-menu-btn:focus {
    background: linear-gradient(90deg, #ffd54f 0%, #ffe082 100%);
    color: #473400;
    box-shadow: 0 4px 16px #ffd54f55;
    text-decoration: none;
}
@media (max-width: 900px) {
    .welcome-banner { border-radius: 0.9rem; padding: 1rem 0.5rem; }
    .welcome-message { font-size: 1.1rem;}
    .dashboard-title { font-size: 1.05rem;}
    .bio-message { font-size: .94rem; padding: .7rem 1rem;}
    .d-flex.flex-column.flex-md-row { flex-direction: column !important; align-items: flex-start !important; }
    .mt-4.mt-md-0 { margin-top: 1.5rem !important; }
}
</style>
<div class="container">
    {{-- Welcome Section --}}
   <div class="dashboard-wrapper">
   @php
    $user = auth()->user();
    $role = $user->role;
    $isAdmin = $role === 'admin';
    $isKasir = $role === 'kasir';
   @endphp

   {{-- Personalized Welcome --}}


    @if($isAdmin)
        <!-- <a href="{{ route('reports.index') }}" class="lihat-menu-btn">
            Lihat Laporan
        </a> -->
    @elseif($isKasir)
        <!-- <a href="{{ route('orders.index') }}" class="lihat-menu-btn">
            Buat Pesanan
        </a> -->
    @else
        {{-- Untuk user biasa, tampilkan daftar menu per kategori langsung --}}
        <!-- <a href="{{ route('menus.index') }}" class="lihat-menu-btn mb-4">
            Lihat Semua Menu
        </a> -->
    

        <style>
            .user-dashboard-card {
                border-radius: 1rem;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                padding: 1.5rem;
                background: #ffffff;
                transition: 0.3s ease;
            }

            .user-dashboard-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            }

            .dashboard-title {
                font-weight: 600;
                color: #2c3e50;
            }

            .menu-img {
                width: 100%;
                height: 180px;
                object-fit: cover;
                border-radius: 0.75rem;
            }
        </style>

      <div class="container py-4">
    <h2 class="dashboard-title mb-5 text-center" style="font-weight: 800; letter-spacing: 0.03em; color: #195b9a;">
        Menu Tersedia
    </h2>
    @php
        $menusByCategory = $menus->groupBy('kategori_id');
    @endphp

    @foreach ($menusByCategory as $kategoriId => $menusGroup)
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
                <span style="width: 9px; height: 32px; background: linear-gradient(180deg, #2d9cdb 0%, #195b9a 100%); border-radius: 6px; margin-right: 16px; display: inline-block;"></span>
                <h4 class="mb-0" style="color: #195b9a; font-weight: 700;">
                    {{ $menusGroup->first()->kategori->nama_kategori ?? 'Kategori Tidak Diketahui' }}
                </h4>
            </div>
            <div class="row g-4">
                @foreach ($menusGroup as $menu)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="menu-card text-center border-0"
                             style="padding: 1.6rem 1.1rem; border-radius: 1.4rem; background: linear-gradient(135deg, #fafdff 80%, #e6f0fa 100%); box-shadow: 0 8px 32px rgba(25,91,154,0.12); position: relative; overflow: hidden; transition: 0.25s;">
                            <img src="{{ asset('storage/' . $menu->gambar) }}"
                                 alt="{{ $menu->nama_menu }}"
                                 class="menu-img mb-3"
                                 style="border-radius: 1rem; width: 100%; height: 160px; object-fit: cover; box-shadow: 0 4px 16px #195b9a13;">
                            <h5 class="mb-1" style="font-weight: 700; color: #2366b5;">{{ $menu->nama_menu }}</h5>
                            <p class="mb-2" style="color: #789; font-size: 1.01rem; font-weight: 500;">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </p>
                            <div class="mt-3">
                                @if($menu->stok > 0)
                                    <span class="badge bg-gradient-ready" style="font-size: 1em; padding: .54em 1.3em; border-radius: 999px; font-weight:600;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Ready
                                    </span>
                                @else
                                    <span class="badge bg-gradient-habis" style="font-size: 1em; padding: .54em 1.3em; border-radius: 999px; font-weight:600;">
                                        <i class="bi bi-x-circle me-1"></i> Stock Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .menu-card {
        box-shadow: 0 8px 24px rgba(44,62,80,0.09);
        transition: transform .22s, box-shadow .22s, background .22s;
        border: none;
    }
    .menu-card:hover {
        transform: translateY(-9px) scale(1.035);
        box-shadow: 0 18px 36px rgba(25,91,154,0.18);
        background: linear-gradient(135deg, #e6f0fa 80%, #fafdff 100%);
        z-index: 2;
    }
    .badge.bg-gradient-ready {
        background: linear-gradient(90deg,#43d477 60%,#23b26d 100%)!important;
        color: #fff!important;
        box-shadow: 0 2px 12px #43d47725;
    }
    .badge.bg-gradient-habis {
        background: linear-gradient(90deg,#bfc9d1 60%,#7b8a9e 100%)!important;
        color: #fff!important;
        box-shadow: 0 2px 12px #bfc9d122;
    }
    .menu-img {
        transition: filter 0.2s;
    }
    .menu-card:hover .menu-img {
        filter: brightness(0.97) saturate(1.07);
    }
</style>
    @endif
</div>

    {{-- Quick Actions Section --}}
@if ($isKasir)
    <div class="quick-actions d-flex flex-wrap gap-4 justify-content-center align-items-stretch py-3">
        <!-- Buat Pesanan -->
        <div class="action-card action-glass" onclick="cardRipple(this)">
            <div class="action-icon bg1"><i data-feather="plus-circle"></i></div>
            <h5>Buat Pesanan</h5>
            <a href="{{ route('orders.index') }}">
                Pesan Sekarang
            </a>
        </div>
        <!-- Lihat Detail Pesanan -->
        <div class="action-card action-glass" onclick="cardRipple(this)">
            <div class="action-icon bg2"><i data-feather="file-text"></i></div>
            <h5>Lihat Detail Pesanan</h5>
            <a href="{{ route('detail_orders.index') }}">
                Detail Pesanan
            </a>
        </div>
        <!-- Lihat Menu
        <div class="action-card action-glass" onclick="cardRipple(this)">
            <div class="action-icon bg3"><i data-feather="book-open"></i></div>
            <h5>Lihat Menu</h5>
            <a href="{{ route('menus.index') }}">
                Lihat Menu
            </a>
        </div> -->
        <!-- Cetak Struk -->
        <div class="action-card action-glass" onclick="cardRipple(this)">
            <div class="action-icon bg4"><i data-feather="printer"></i></div>
            <h5>Cetak Struk</h5>
            <a href="{{ route('detail_orders.index') }}">
                Pilih Pesanan
            </a>
        </div>
        <!-- Notifikasi Stok Menipis -->
        {{-- <div class="action-card action-glass" onclick="cardRipple(this)">
            <div class="action-icon bg5"><i data-feather="alert-circle"></i></div>
            <h5>Notifikasi Stok</h5>
            <button type="button" class="btn btn-link p-0 action-btn-link" data-bs-toggle="modal" data-bs-target="#stokMenipisModal">
                Cek Stok Menipis
            </button>
        </div> --}}
    </div>

    <!-- Modal Stok Menipis -->
    {{-- <div class="modal fade" id="stokMenipisModal" tabindex="-1" aria-labelledby="stokMenipisLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow rounded-4 glass-modal">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title" id="stokMenipisLabel"> --}}
                        {{-- <i class="bi bi-exclamation-circle me-2"></i>Menu Stok Menipis
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $menipisMenus = $menus->where('stok', '>', 0)->where('stok', '<=', 5);
                    @endphp

                    @if($menipisMenus->isEmpty())
                        <div class="d-flex flex-column align-items-center text-center">
                            <lottie-player src="https://lottie.host/9aa3b3e6-ff73-47b1-9f76-e9b6b7d2fb2e/1w7Wbi9rLw.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                            <p class="text-muted mt-2">Tidak ada menu dengan stok menipis 🎉</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($menipisMenus as $menu)
                                <li class="list-group-item d-flex justify-content-between align-items-center glass-list">
                                    <div> --}}
                                        {{-- <b>{{ $menu->nama_menu }}</b>
                                        <span class="badge bg-warning text-dark ms-2">Stok: {{ $menu->stok }}</span>
                                    </div>
                                    <a href="{{ route('menus.edit', $menu->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1"
                                        title="Tambah Stok">
                                        <i class="bi bi-plus-circle"></i>
                                        <span class="d-none d-sm-inline">Tambah Stok</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap');
        .quick-actions {
            flex-wrap: wrap;
            gap: 2.4rem;
            margin: 2.5rem 0;
        }
        .action-card {
            background: rgba(255,255,255,0.80);
            border-radius: 1.4rem;
            box-shadow: 0 8px 24px 0 rgba(44,62,80,0.13);
            padding: 2.2rem 1.6rem 1.1rem;
            min-width: 200px;
            max-width: 240px;
            flex: 1 1 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: box-shadow .19s, transform .19s, background .19s;
            text-align: center;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .action-card .action-icon {
            width: 58px; height: 58px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin-bottom: 1.1rem;
            box-shadow: 0 2px 13px rgba(44,62,80,0.10);
            color: #fff;
            margin-top: -2.2rem;
            margin-bottom: 0.7rem;
            border: 2.5px solid #fff;
            transition: box-shadow .19s, transform .19s;
        }
        .action-card:hover .action-icon {
            box-shadow: 0 5px 30px rgba(44,62,80,0.16);
            transform: scale(1.08);
        }
        .bg1 { background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);}
        .bg2 { background: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);}
        .bg3 { background: linear-gradient(135deg, #7474bf 0%, #348ac7 100%);}
        .bg4 { background: linear-gradient(135deg, #232526 0%, #414345 100%);}
        .bg5 { background: linear-gradient(135deg, #fc5c7d 0%, #6a82fb 100%);}
        .action-card h5 {
            font-weight: 700;
            color: #232526;
            margin-bottom: 0.7rem;
            font-size: 1.13rem;
        }
        .action-card a, .action-card button.btn-link {
            color: #185a9d;
            font-weight: 600;
            text-decoration: none;
            font-size: 1.06rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
            transition: color .17s;
        }
        .action-card a:hover, .action-card button.btn-link:hover {
            color: #fc5c7d;
        }
        .action-btn-link {
            font-size: 1.06rem;
            font-weight: 600;
        }
        .action-card:hover {
            box-shadow: 0 26px 48px 0 rgba(44,62,80,0.18);
            transform: translateY(-10px) scale(1.04);
            background: rgba(255,255,255,0.96);
            z-index: 2;
        }
        .action-card:active::after {
            opacity:.45;
        }
        .action-card:focus {
            outline: 2px solid #43cea2;
        }
        .action-glass {
            backdrop-filter: blur(3.5px);
            background: rgba(255,255,255,0.80);
        }
        .glass-modal {
            background: rgba(255,255,255,0.89);
            backdrop-filter: blur(4px);
        }
        .glass-list {
            background: rgba(255,255,255,0.82)!important;
            backdrop-filter: blur(2px);
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 900px) {
            .quick-actions {
                flex-direction: column;
                gap: 1.1rem;
            }
            .action-card {
                min-width: unset;
                max-width: unset;
                width: 100%;
            }
        }
    </style>

    <!-- Lottie Player CDN for Animation (stock aman) -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script>
        // Ripple effect untuk card
        function cardRipple(card) {
            let ripple = document.createElement('span');
            ripple.className = 'card-ripple';
            card.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        }

        // Feather icon refresh
        document.addEventListener("DOMContentLoaded", function () {
            if (window.feather) feather.replace();
        });

        // Ripple effect style (dimasukkan lewat JS agar tidak bocor ke card lain)
        let rippleStyle = document.createElement('style');
        rippleStyle.innerHTML = `
        .card-ripple {
            position: absolute;
            left: 50%; top: 50%;
            width: 200px; height: 200px;
            background: rgba(67,206,162,0.16);
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%,-50%) scale(0.2);
            animation: ripple-fx .45s cubic-bezier(.22,1.1,.36,1) forwards;
            z-index:9;
        }
        @keyframes ripple-fx {
            to { transform: translate(-50%,-50%) scale(1.2); opacity:0; }
        }
        `;
        document.head.appendChild(rippleStyle);
    </script>
@endif

@if($isAdmin)
<div class="row mb-4">
    {{-- Aktivitas Terbaru --}}
    <div class="col-md-5 mb-3">
        <div class="card shadow-sm rounded-4 admin-elegant-card h-100 border-0">
            <div class="card-body">
                <h5 class="card-title mb-4 fw-bold text-primary" style="letter-spacing:0.5px;">
                    <i class="bi bi-bell-fill text-warning me-2"></i> Aktivitas Terbaru
                </h5>
                @if($lowStockMenus->isEmpty())
                    <p class="text-muted fst-italic mb-0">Tidak ada pemberitahuan stok menipis.</p>
                @else
                    @foreach($lowStockMenus as $menu)
                    <div class="d-flex mb-3 align-items-start admin-activity-row">
                        <div class="me-3 mt-1">
                            <i data-feather="alert-triangle" class="text-warning" width="24" height="24"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-semibold text-danger">Stok Hampir Habis!</h6>
                            <p class="mb-0 text-dark">
                                Menu <strong>{{ $menu->nama_menu }}</strong> hanya tersisa 
                                <span class="badge bg-warning text-dark">{{ $menu->stok }} pcs</span>
                            </p>
                            <small class="text-muted">Segera lakukan restock</small>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Grafik Pendapatan --}}
    <div class="col-md-7">
    <div class="card shadow-sm rounded-4 admin-elegant-card border-0" style="display:inline-block; width:auto; min-width:360px;">
        <div class="card-body p-4">
            <h5 class="card-title mb-4 fw-bold text-primary" style="letter-spacing:0.5px; white-space:nowrap; font-size:1.45rem;">
                <i class="bi bi-bar-chart-line-fill text-success me-2"></i>
                Grafik Total Pendapatan per Kasir
            </h5>
            <div class="chart-responsive" style="width:100%; min-width:340px; height:290px;">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
.chart-responsive {
    width: 100%;
    min-width: 340px;
    height: 290px;
    position: relative;
}
.chart-responsive canvas {
    width: 100% !important;
    height: 100% !important;
    display: block;
}
.admin-elegant-card {
    background: linear-gradient(120deg, #f8fafc 70%, #fff 100%);
    border-radius: 1.2rem !important;
    box-shadow: 0 8px 32px rgba(44,62,80,0.09), 0 2px 8px rgba(44,62,80,0.04) !important;
    border: 0;
    transition: box-shadow .18s;
}
.admin-elegant-card:hover {
    box-shadow: 0 18px 48px #b59a743a, 0 2px 8px #b59a7414;
}
</style>
<script>
/* 
Pastikan inisialisasi Chart.js seperti berikut:
new Chart(document.getElementById('pendapatanChart').getContext('2d'), {
  type: 'bar',
  data: { ... },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    // ...
  }
});
*/
</script>
</div>

<style>
.admin-elegant-card {
    background: linear-gradient(120deg, #f8fafc 70%, #fff 100%);
    border-radius: 1.2rem !important;
    box-shadow: 0 8px 32px rgba(44,62,80,0.09), 0 2px 8px rgba(44,62,80,0.04) !important;
    border: 0;
    transition: box-shadow .18s;
}
.admin-elegant-card:hover {
    box-shadow: 0 18px 48px #b59a743a, 0 2px 8px #b59a7414;
}
.admin-elegant-card .card-title {
    font-size: 1.17rem;
    font-weight: 700;
    color: #283e51;
    margin-bottom: 1.1rem;
    letter-spacing: 0.03em;
    display: flex;
    align-items: center;
}
.admin-activity-row {
    background: rgba(255, 243, 205, 0.13);
    border-radius: 0.7rem;
    padding: 0.6rem 0.7rem 0.6rem 0.5rem;
    transition: background 0.17s;
}
.admin-activity-row:hover {
    background: rgba(255, 243, 205, 0.30);
}
.admin-elegant-card .badge {
    font-size: 0.97em;
    padding: .38em 1.1em;
    border-radius: 1rem;
    font-weight: 600;
    letter-spacing: .02em;
    box-shadow: 0 2px 7px #b59a7437;
}
@media (max-width: 900px) {
    .admin-elegant-card { padding: .6rem 0.7rem; }
    .row.mb-4 > div { margin-bottom: 1.1rem; }
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.feather) feather.replace();
});
</script>

    {{-- Deskripsi Restoran untuk Non-Admin --}}
    <!-- <div class="restaurant-description">
        <p>
            Restoran kami menawarkan berbagai menu lezat dengan bahan-bahan segar dan berkualitas tinggi. 
            Nikmati suasana nyaman dan pelayanan ramah dari staf kami yang profesional. 
            Kami berkomitmen untuk memberikan pengalaman makan yang tak terlupakan untuk setiap pelanggan.
        </p>
        <p>
            Selain hidangan utama, kami juga menyediakan pilihan minuman segar dan pencuci mulut yang menggugah selera. 
            Datang dan rasakan sendiri keistimewaan kuliner kami yang dibuat dengan penuh cinta dan keahlian.
        </p>
    </div> -->
@endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if($isAdmin)
<script>
    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    const pendapatanChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($reports->pluck('kasir_name')) !!},
            datasets: [{
                label: 'Total Pendapatan',
                data: {!! json_encode($reports->pluck('total_pendapatan')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endif

@endsection 