@extends('dashboard.home')

@section('content')
<style>
    body {
        background: #f4f7fc;
        font-family: 'Poppins', sans-serif;
    }

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

<div class="container py-5">

    {{-- Menu Tersedia --}}
    <div>
        <h5 class="dashboard-title mb-3">Menu Tersedia</h5>
        <div class="row g-3">
            @forelse ($menus as $menu)
                <div class="col-md-4">
                    <div class="user-dashboard-card">
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="menu-img mb-3">
                        <h6 class="mb-1">{{ $menu->nama_menu }}</h6>
                        <p class="text-muted mb-0">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-muted text-center">Belum ada menu tersedia.</div>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
