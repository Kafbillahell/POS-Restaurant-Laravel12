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

    {{-- Menu Tersedia per Kategori --}}
    <div>
        <h5 class="dashboard-title mb-3">Menu Tersedia</h5>

        @php
            // Group menu berdasarkan kategori_id
            $menusByCategory = $menus->groupBy('kategori_id');
        @endphp

        @foreach ($menusByCategory as $kategoriId => $menusGroup)
            {{-- Tampilkan nama kategori dari item pertama --}}
            <h6 class="mt-4 mb-3">
                {{ $menusGroup->first()->kategori->nama_kategori ?? 'Kategori Tidak Diketahui' }}
            </h6>

            <div class="row g-3">
                @foreach ($menusGroup as $menu)
                    <div class="col-md-4">
                        <div class="user-dashboard-card">
                            <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="menu-img mb-3">
                            <h6 class="mb-1">{{ $menu->nama_menu }}</h6>
                            <p class="text-muted mb-0">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        @if ($menus->isEmpty())
            <div class="text-muted text-center mt-4">Belum ada menu tersedia.</div>
        @endif

    </div>

</div>
@endsection
