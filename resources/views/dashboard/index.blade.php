@extends('dashboard.home')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #f8fbff, #eef3f9);
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-wrapper {
        text-align: center;
        padding: 2rem 1rem 3rem;
    }

    .dashboard-title {
        font-weight: 700;
        font-size: 2.2rem;
        color: #2c3e50;
    }

    .dashboard-subtitle {
        color: #7b8a9e;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }

    .lihat-menu-btn {
        background: #2d9cdb;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 999px;
        font-size: 1rem;
        font-weight: 500;
        margin-top: 2rem;
        transition: 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .lihat-menu-btn:hover {
        background: #238ac3;
        box-shadow: 0 4px 12px rgba(45, 156, 219, 0.3);
    }

    .recent-activity {
        margin: 3rem auto;
        max-width: 800px;
    }

    .recent-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        align-items: flex-start;
    }

    .activity-icon {
        background-color: #ffc107;
        color: #fff;
        border-radius: 50%;
        padding: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .activity-content h5 {
        margin: 0;
        font-weight: 600;
        color: #333;
    }

    .activity-content p {
        margin: 0.2rem 0;
        color: #666;
    }

    .activity-content span {
        font-size: 0.85rem;
        color: #999;
    }

    .quick-actions {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 2rem 0;
    }

    .action-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        text-align: center;
        flex: 1;
        transition: transform 0.3s ease;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .action-card h5 {
        margin: 0.5rem 0;
        font-weight: 600;
        color: #2c3e50;
    }

    .action-card a {
        text-decoration: none;
        color: #2d9cdb;
        font-weight: 500;
    }
</style>

<div class="container">
    {{-- Welcome Section --}}
    <div class="dashboard-wrapper">
        <h3 class="dashboard-title">Selamat Datang, {{ auth()->user()->user_name }}</h3>
        <p class="dashboard-subtitle">
            Ini adalah dashboard {{ auth()->user()->role }}. Berikut ringkasan aktivitas restoran bulan ini.
        </p>

        @php $isAdmin = auth()->user()->role === 'admin'; @endphp

        <a href="{{ $isAdmin ? route('reports.index') : route('menus.index') }}" class="lihat-menu-btn">
            {{ $isAdmin ? 'Lihat Laporan' : 'Lihat Menu' }}
        </a>
    </div>

    
    {{-- Quick Actions Section --}}

@php
    $isAdmin = auth()->user()->role === 'admin';
    $isKasir = auth()->user()->role === 'kasir';
@endphp

@if ($isAdmin || $isKasir)
    <div class="quick-actions">
        @if ($isAdmin)
            <div class="action-card">
                <h5>Kelola Menu</h5>
                <a href="{{ route('users.index') }}">
                    <i data-feather="plus-circle"></i> Kelola User
                </a>
            </div>

            <div class="action-card">
                <h5>Lihat Laporan</h5>
                <a href="{{ route('reports.index') }}">
                    <i data-feather="file-text"></i> Lihat Laporan
                </a>
            </div>
        @elseif ($isKasir)
            <div class="action-card">
                <h5>Buat Pesanan</h5>
                <a href="{{ route('orders.index') }}">
                    <i data-feather="plus-circle"></i> Pesan Sekarang
                </a>
            </div>

            <div class="action-card">
                <h5>Lihat Detail Pesanan</h5>
                <a href="{{ route('detail_orders.index') }}">
                    <i data-feather="file-text"></i> Detail Pesanan
                </a>
            </div>
        @endif
    </div>
@endif




    {{-- Recent Activity Section for Admin --}}
    @if($isAdmin)
    <div class="recent-activity">
        <div class="recent-card">
            <h4 class="mb-4" style="font-weight: 600; color: #2c3e50;">Aktivitas Terbaru</h4>

            @if($lowStockMenus->isEmpty())
                <p class="text-muted">Tidak ada pemberitahuan stok menipis.</p>
            @else
                @foreach($lowStockMenus as $menu)
                <div class="activity-item">
                    <div class="activity-icon">
                        <i data-feather="alert-triangle" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div class="activity-content">
                        <h5>Stok Hampir Habis!</h5>
                        <p>Menu <strong>{{ $menu->nama_menu }}</strong> hanya tersisa <strong>{{ $menu->stok }}</strong> pcs.</p>
                        <span>Segera lakukan restock</span>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @else
    {{-- Deskripsi Restoran untuk Non-Admin --}}
    <div class="restaurant-description">
        <p>
            Restoran kami menawarkan berbagai menu lezat dengan bahan-bahan segar dan berkualitas tinggi. 
            Nikmati suasana nyaman dan pelayanan ramah dari staf kami yang profesional. 
            Kami berkomitmen untuk memberikan pengalaman makan yang tak terlupakan untuk setiap pelanggan.
        </p>
        <p>
            Selain hidangan utama, kami juga menyediakan pilihan minuman segar dan pencuci mulut yang menggugah selera. 
            Datang dan rasakan sendiri keistimewaan kuliner kami yang dibuat dengan penuh cinta dan keahlian.
        </p>
    </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
