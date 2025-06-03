@extends('dashboard.home')

@section('content')
<h1>Daftar Orders</h1>

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

@if(session('menuBaru'))
    <div class="alert alert-success mt-3">
        Menu baru "{{ session('menuBaru')->nama_menu }}" berhasil ditambahkan dan siap dipesan.
    </div>
@endif


<div class="row mt-4">
    {{-- Kolom Daftar Menu (kiri) --}}
    <div class="col-md-8">
        <form action="{{ route('orders.index') }}" method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama menu...">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-control">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->nama_kategori }}" {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Cari</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary flex-grow-1">Reset</a>
            </div>
        </form>

        {{-- List Menu --}}
        @forelse ($menus as $menu)
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row g-0 align-items-center">
                    <div class="col-md-4">
                        @if($menu->gambar)
                            <img src="{{ asset('storage/'.$menu->gambar) }}" alt="{{ $menu->nama_menu }}" style="width: 100%; max-height: 180px; object-fit: contain;">
                        @else
                            <div class="d-flex justify-content-center align-items-center bg-secondary text-white" style="height: 180px;">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-3">
                            <h5 class="card-title mb-1">{{ $menu->nama_menu }}</h5>
                            <p class="card-text mb-1"><strong>Kategori:</strong> {{ $menu->kategori->nama_kategori ?? '-' }}</p>
                            <p class="card-text mb-2"><strong>Harga:</strong> Rp {{ number_format($menu->harga, 2, ',', '.') }}</p>
                            @if($menu->stok > 0)
                                <button class="btn btn-success btn-sm add-to-cart-btn" data-id="{{ $menu->id }}">+ Keranjang</button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Stock Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada menu ditemukan.</p>
        @endforelse
    </div>

    {{-- Sidebar Keranjang (kanan) --}}
   <div id="cart-target" style="position: fixed; top: 190px; right: 100px; z-index: 9999; width: 300px;">
    <div class="card">
        <div class="card-header fs-5">🛒 Keranjang</div>
        <ul class="list-group list-group-flush" id="cart-list">
            @php $cart = session('cart', []); @endphp
            @forelse ($cart as $id => $item)
                <li class="list-group-item" data-id="{{ $id }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item['nama_menu'] }}</strong>
                            <div class="mt-1 d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-success px-2 btn-increase" data-id="{{ $id }}">+</button>
                                <span class="mx-2">x{{ $item['quantity'] }}</span>
                                <button class="btn btn-sm btn-outline-danger px-2 btn-decrease" data-id="{{ $id }}">−</button>
                            </div>
                        </div>
                        <span>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted">Keranjang kosong</li>
            @endforelse
        </ul>

        {{-- Tombol Checkout dipisah di sini --}}
        <div id="cart-footer" class="card-footer text-center" style="{{ count($cart) == 0 ? 'display:none;' : '' }}">
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm w-100">Checkout</a>
        </div>
        
    </div>
</div>

</div>
@endsection

<style>
    .alert-success,
    .alert-danger {
        display: none !important;
    }
</style>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Tampilkan pesan sukses atau error dari session (jika ada)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Fungsi reload isi keranjang
    function reloadCart() {
        fetch("{{ route('orders.cart.reload') }}")
            .then(response => response.text())
            .then(html => {
                document.getElementById('cart-list').innerHTML = html;

                const list = document.getElementById('cart-list');
                const emptyText = list.querySelector('li.text-center.text-muted');
                const cartFooter = document.getElementById('cart-footer');

                cartFooter.style.display = emptyText ? 'none' : 'block';
            });
    }

    // Tambah ke keranjang
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const menuId = e.target.getAttribute('data-id');

            fetch("{{ route('orders.cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ menu_id: menuId })
            }).then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    reloadCart();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                    });
                }
            });
        });
    });

    // + / - kuantitas
    document.getElementById('cart-list').addEventListener('click', e => {
        if (e.target.classList.contains('btn-increase') || e.target.classList.contains('btn-decrease')) {
            const menuId = e.target.getAttribute('data-id');
            const route = e.target.classList.contains('btn-increase') 
                ? "{{ route('orders.cart.add') }}" 
                : "{{ route('orders.cart.remove') }}";

            fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ menu_id: menuId })
            }).then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    reloadCart();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                    });
                }
            });
        }
    });
});

// Tangkap semua klik pada <a> (link) di halaman
document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = link.getAttribute('href');

        // Cek kalau link menuju halaman selain orders.index (hindari reset saat reload halaman order)
        if (!href.includes('/orders')) {
            fetch("{{ route('orders.cart.reset') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        }
    });
});

window.addEventListener('beforeunload', function () {
    navigator.sendBeacon("{{ route('orders.cart.reset') }}", new Blob([], { type: 'application/json' }));
});

</script>
@endpush
