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
        <div class="col-md-8">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3 mb-3 align-items-end"
                style="font-family: 'Poppins', sans-serif;">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold text-secondary mb-1">Cari Menu</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="form-control shadow-sm rounded-3 py-2" placeholder="Nama menu ...">
                </div>
                <div class="col-md-4">
                    <label for="kategori" class="form-label fw-semibold text-secondary mb-1">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select shadow-sm rounded-3 py-2">
                        <option value="">All</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->nama_kategori }}" {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}> {{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-3">
                    <button type="button" class="btn btn-light border flex-fill rounded-pill shadow-sm px-4"
                        style="font-weight:600;" id="reset-button">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                </div>
            </form>

            @php
                // Logika pengelompokan menu dan status keranjang awal
                $kategoriOrder = ['Seafood', 'Drink', 'Cat Food'];
                $menusGrouped = $menus->groupBy(function ($item) {
                    return $item->kategori->nama_kategori ?? 'Lainnya';
                });
                $cartState = session('cart', []); // Mengambil status keranjang dari session
            @endphp

            <style>
                .category-section {
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 12px;
                    margin-top: 48px;
                    margin-bottom: 28px;
                }

                .category-section h3 {
                    font-size: 1.75rem;
                    font-weight: 600;
                    color: #222;
                    letter-spacing: 0.05em;
                    margin-bottom: 20;
                    margin-top: -30px;
                    text-transform: capitalize;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .card-menu {
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    width: 250px;
                    display: flex;
                    flex-direction: column;
                    overflow: hidden;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.07);
                    transition: box-shadow 0.25s ease, transform 0.25s ease;
                    cursor: pointer;
                }

                .card-menu:hover {
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
                    transform: translateY(-4px);
                }

                .card-menu img {
                    width: 100%;
                    height: 170px;
                    object-fit: cover;
                    display: block;
                    border-bottom: 1px solid #ddd;
                }

                .no-image-placeholder {
                    height: 170px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #999;
                    font-weight: 500;
                    font-size: 1rem;
                    background-color: #f8f8f8;
                    border-bottom: 1px solid #ddd;
                    user-select: none;
                }

                .card-body {
                    padding: 16px 18px;
                    flex-grow: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }

                .card-title {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #111;
                    margin-bottom: 6px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .kategori-label {
                    font-size: 0.8rem;
                    font-weight: 400;
                    color: #777;
                    margin-bottom: 14px;
                    font-style: italic;
                }

                .price {
                    font-weight: 700;
                    font-size: 1rem;
                    color: #333;
                    margin-bottom: 18px;
                    letter-spacing: 0.02em;
                }

                .btn-add-cart {
                    background: transparent;
                    border: 2px solid #333;
                    color: #333;
                    font-weight: 600;
                    border-radius: 25px;
                    padding: 10px 0;
                    width: 100%;
                    transition: background-color 0.3s ease, color 0.3s ease;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    cursor: pointer;
                }

                .btn-add-cart:hover {
                    background-color: #333;
                    color: #fff;
                }

                .btn-disabled {
                    background: #e0e0e0 !important;
                    border: 2px solid #ccc !important;
                    color: #aaa !important;
                    cursor: not-allowed !important;
                    font-weight: 600;
                    border-radius: 25px;
                    padding: 10px 0;
                    width: 100%;
                }
            </style>

            <div id="menu-container">
                {{-- Kategori lain --}}
                @foreach ($menusGrouped as $kategori => $menusInGroup)
                    @if(!in_array($kategori, $kategoriOrder))
                        <section class="category-section">
                            <h3>{{ $kategori }}</h3>
                            <div class="d-flex flex-wrap gap-4 justify-content-start">
                                @foreach ($menusInGroup as $menu)
                                    @php
                                        // Logika Promo dari versi DEV
                                        $isPromoActive = false;
                                        $promoEndTime = '';
                                        $currentPrice = $menu->harga;

                                        if ($menu->promo_start_at && $menu->harga_promo) {
                                            $promoEnd = Carbon\Carbon::parse($menu->promo_start_at)
                                                ->addDays($menu->durasi_promo_hari ?? 0)
                                                ->addHours($menu->durasi_promo_jam ?? 0)
                                                ->addMinutes($menu->durasi_promo_menit ?? 0);

                                            if (now()->greaterThanOrEqualTo($menu->promo_start_at) && now()->lessThan($promoEnd)) {
                                                $isPromoActive = true;
                                                $currentPrice = $menu->harga_promo;
                                                $promoEndTime = $promoEnd->toIso8601String();
                                            }
                                        }

                                        $originalPriceText = 'Rp ' . number_format($menu->harga, 0, ',', '.');
                                        $rawOriginalPrice = $menu->harga;

                                        // Status Kuantitas Awal dari keranjang
                                        $initialQty = $cartState[$menu->id]['quantity'] ?? 0;
                                        $currentStokView = $menu->stok - $initialQty;
                                        // Harga yang akan masuk ke JS (harga jual setelah promo jika aktif)
                                        $priceForJs = $isPromoActive ? $menu->harga_promo : $menu->harga;
                                    @endphp

                                    <div class="card-menu shadow-sm"
                                        data-promo-end="{{ $promoEndTime }}"
                                        data-original-price="{{ $originalPriceText }}"
                                        data-original-raw-price="{{ $rawOriginalPrice }}"
                                        data-current-price="{{ $priceForJs }}"> {{-- Tambahkan data-current-price untuk JS --}}
                                        @if($menu->gambar)
                                            <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}">
                                        @else
                                            <div class="no-image-placeholder">No Image</div>
                                        @endif
                                        <div class="card-body">
                                            <div>
                                                <div class="kategori-label">{{ $menu->kategori->nama_kategori ?? '-' }}</div>
                                                <h5 class="card-title">{{ $menu->nama_menu }}</h5>
                                            </div>
                                            <div>
                                                <div class="price @if ($isPromoActive) text-danger fw-bold @endif"
                                                    data-harga-jual="{{ $currentPrice }}">
                                                    @if ($isPromoActive)
                                                        @php
                                                            $discount = $menu->harga - $currentPrice;
                                                            $discountPercentage = ($menu->harga > 0) ? round(($discount / $menu->harga) * 100) : 0;
                                                        @endphp
                                                        <span class="text-decoration-line-through text-muted small me-2">
                                                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                                        </span>
                                                        <span class="fw-bold text-danger">
                                                            Rp {{ number_format($currentPrice, 0, ',', '.') }}
                                                        </span>
                                                        <span class="badge bg-danger ms-1" style="font-size: 0.7rem;">
                                                            -{{ $discountPercentage }}%
                                                        </span>
                                                    @else
                                                        Rp {{ number_format($currentPrice, 0, ',', '.') }}
                                                    @endif
                                                </div>

                                                @if ($isPromoActive)
                                                    <div class="promo-timer text-danger mb-2" style="font-size: 0.9rem; font-weight: 500;">
                                                        {{-- Timer akan diisi oleh JS --}}
                                                    </div>
                                                @endif

                                                <p class="text-muted mb-2 stok-value" style="font-size: 0.9rem;" data-initial-stok="{{ $menu->stok }}"> {{-- Tambahkan data-initial-stok --}}
                                                    Stok: {{ $currentStokView }}
                                                </p>
                                                <div class="d-flex align-items-center justify-content-between"
                                                    data-menu-id="{{ $menu->id }}">
                                                    @if($menu->stok > 0)
                                                        <button class="btn btn-sm btn-outline-danger px-2 me-2 btn-quantity-card"
                                                            data-action="decrease" data-id="{{ $menu->id }}"
                                                            style="display: {{ $initialQty > 0 ? 'block' : 'none' }}; width:30px; height:30px; border-radius: 50%;">
                                                            −
                                                        </button>
                                                        <span class="fw-bold me-2 quantity-value-card" data-id="{{ $menu->id }}"
                                                            style="display: {{ $initialQty > 0 ? 'block' : 'none' }};">
                                                            {{ $initialQty }}
                                                        </span>
                                                        <button
                                                            class="btn btn-dark flex-fill rounded-pill shadow-sm px-4 btn-quantity-card add-to-cart-initial"
                                                            data-action="increase" data-id="{{ $menu->id }}"
                                                            style="font-weight:600; width:100%; display: {{ $initialQty > 0 ? 'none' : 'block' }};">
                                                            <i class="bi bi-cart-plus me-1"></i> + Keranjang
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-success px-2 btn-quantity-card"
                                                            data-action="increase" data-id="{{ $menu->id }}"
                                                            style="display: {{ $initialQty > 0 ? 'block' : 'none' }}; width:30px; height:30px; border-radius: 50%;">
                                                            +
                                                        </button>
                                                    @else
                                                        <button class="btn btn-disabled w-100" disabled>Stock Habis</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach

                @if ($menus->count() === 0)
                    <p>Tidak ada menu ditemukan.</p>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <style>
                #cart-target {
                    position: fixed;
                    top: 190px;
                    right: 70px;
                    z-index: 9999;
                    width: 330px;
                    height: 400px;
                }

                #cart-target .card {
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }

                #cart-list {
                    flex-grow: 1;
                    overflow-y: scroll;
                    padding-left: 0;
                    margin-bottom: 0;
                    min-height: 200px;
                }

                #cart-list li.list-group-item {
                    padding: 10px 15px;
                }

                #cart-footer {
                    flex-shrink: 0;
                }

                #cart-list {
                    scrollbar-width: thin;
                    scrollbar-color: #888 #f0f0f0;
                }

                #cart-list::-webkit-scrollbar {
                    width: 8px;
                }

                #cart-list::-webkit-scrollbar-track {
                    background: #f0f0f0;
                    border-radius: 4px;
                }

                #cart-list::-webkit-scrollbar-thumb {
                    background-color: #888;
                    border-radius: 4px;
                    border: 2px solid #f0f0f0;
                }

                #cart-list::-webkit-scrollbar-thumb:hover {
                    background-color: #555;
                }
            </style>

            <div id="cart-target">
                <div class="card">
                    <div class="card-header fs-5">🛒 Keranjang</div>

                    <ul class="list-group list-group-flush" id="cart-list">
                        {{-- Akan diisi oleh JavaScript dari cartState --}}
                        @php $cart = $cartState; @endphp
                        @forelse ($cart as $id => $item)
                            <li class="list-group-item" data-id="{{ $id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item['nama_menu'] }}</strong>
                                        <span class="mx-2">x{{ $item['quantity'] }}</span> 
                                    </div>
                                    <span>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted d-flex justify-content-center align-items-center"
                                style="min-height: 200px;">
                                Keranjang kosong
                            </li>
                        @endforelse
                    </ul>

                    <div id="cart-footer" class="card-footer p-3 border-0" style="{{ empty($cartState) ? 'display: none;' : 'display: block;' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                            <span class="text-muted fw-semibold small text-uppercase">Total Bayar</span>
                            @php
                                $totalBayar = array_reduce($cartState, function($sum, $item) {
                                    return $sum + ($item['harga'] * $item['quantity']);
                                }, 0);
                            @endphp
                            <span class="fs-4 fw-bold text-success" id="cart-subtotal">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ empty($cartState) ? '#' : route('orders.create') }}" 
                           class="btn btn-lg w-100 {{ empty($cartState) ? 'btn-secondary disabled' : 'btn-success' }} shadow-sm" 
                           id="checkout-button">
                            <i class="bi bi-cup-hot-fill me-2"></i>
                            Selesaikan Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .alert-success,
    .alert-danger {
        /* Memperbaiki konflik, asumsikan ini untuk hidden */
        /* Anda bisa menghapus ini jika ingin alert tampil */
        /* display: none !important; */
    }
</style>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi cartState dari PHP session
            const cartState = @json($cartState);
            const searchInput = document.querySelector('#search');
            const kategoriSelect = document.querySelector('#kategori');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Pastikan Anda memiliki meta tag CSRF

            // Fungsi untuk memperbarui tampilan tombol di card
            function updateCardButtons(menuId, quantity, maxStock) {
                const container = document.querySelector(`div[data-menu-id="${menuId}"]`);
                if (!container) return;

                const initialBtn = container.querySelector('.add-to-cart-initial');
                const plusBtn = container.querySelector('.btn-quantity-card[data-action="increase"]:not(.add-to-cart-initial)');
                const minusBtn = container.querySelector('.btn-quantity-card[data-action="decrease"]');
                const quantityEl = container.querySelector('.quantity-value-card');
                const stokEl = container.closest('.card-body').querySelector('.stok-value');

                if (stokEl) {
                    const currentStok = maxStock - quantity;
                    // Memperbarui tampilan stok
                    stokEl.innerHTML = `Stok: <span class="stok-current-value">${currentStok}</span>`;
                }
                if (quantityEl) {
                    quantityEl.textContent = quantity;
                }

                if (quantity > 0) {
                    if (initialBtn) initialBtn.style.display = 'none';
                    if (plusBtn) plusBtn.style.display = 'block';
                    if (minusBtn) minusBtn.style.display = 'block';
                    if (quantityEl) quantityEl.style.display = 'block';
                } else {
                    if (initialBtn) initialBtn.style.display = 'block';
                    if (plusBtn) plusBtn.style.display = 'none';
                    if (minusBtn) minusBtn.style.display = 'none';
                    if (quantityEl) quantityEl.style.display = 'none';
                }

                // Mengubah status tombol plus jika stok habis
                if (plusBtn) {
                    if (quantity >= maxStock) {
                        plusBtn.disabled = true;
                        plusBtn.classList.remove('btn-outline-success');
                        plusBtn.classList.add('btn-secondary');
                    } else {
                        plusBtn.disabled = false;
                        plusBtn.classList.remove('btn-secondary');
                        plusBtn.classList.add('btn-outline-success');
                    }
                }
            }
            
            // Fungsi untuk memperbarui tampilan keranjang di sidebar
            function updateCartUI(cart) {
                const cartList = document.getElementById('cart-list');
                const cartFooter = document.getElementById('cart-footer');
                const checkoutButton = document.getElementById('checkout-button');
                
                // Sinkronisasi cartState dengan data dari server
                Object.keys(cartState).forEach(id => {
                    if (!cart[id]) delete cartState[id];
                });
                Object.assign(cartState, cart); // Memperbarui/menambahkan item yang baru

                let subtotal = 0;
                let html = '';

                for (const id in cartState) {
                    const item = cartState[id];
                    subtotal += item.harga * item.quantity;
                    const totalHarga = (item.harga * item.quantity).toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    });

                    // Update tombol card untuk setiap menu yang ada di keranjang
                    const cardContainer = document.querySelector(`div[data-menu-id="${id}"]`);
                    let maxStock = item.max_stok;
                    if (cardContainer) {
                        const initialStokAttr = cardContainer.closest('.card-menu').querySelector('.stok-value')?.getAttribute('data-initial-stok');
                        maxStock = initialStokAttr ? parseInt(initialStokAttr) : item.max_stok;
                    }
                    updateCardButtons(id, item.quantity, maxStock); 
                    
                    html += `
                    <li class="list-group-item" data-id="${id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${item.nama_menu}</strong>
                                <span class="mx-2">x${item.quantity}</span> 
                            </div>
                            <span>${totalHarga}</span>
                        </div>
                    </li>`;
                }

                // Tampilan keranjang kosong atau terisi
                if (Object.keys(cartState).length === 0) {
                    cartList.innerHTML = `
                    <li class="list-group-item text-center text-muted d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        Keranjang kosong
                    </li>`;
                    checkoutButton.classList.remove('btn-success');
                    checkoutButton.classList.add('btn-secondary', 'disabled');
                    checkoutButton.setAttribute('href', '#');
                    cartFooter.style.display = 'none';
                    
                    // Reset semua card button ke tampilan awal (tanpa keranjang)
                    document.querySelectorAll('.card-menu').forEach(card => {
                        const menuId = card.querySelector('div[data-menu-id]')?.getAttribute('data-menu-id');
                        const maxStock = parseInt(card.querySelector('.stok-value').getAttribute('data-initial-stok'));
                        updateCardButtons(menuId, 0, maxStock);
                    });
                    
                } else {
                    cartList.innerHTML = html;
                    cartFooter.style.display = 'block';

                    const totalEl = document.getElementById('cart-subtotal');
                    if (totalEl) {
                        totalEl.textContent = subtotal.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        });
                    }

                    checkoutButton.classList.remove('btn-secondary', 'disabled');
                    checkoutButton.classList.add('btn-success');
                    checkoutButton.setAttribute('href', "{{ route('orders.create') }}");
                }
            }


            // Fungsi AJAX untuk menambah/mengurangi keranjang
            async function updateCart(menuId, action) {
                const response = await fetch('{{ route('cart.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        menu_id: menuId,
                        action: action
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    updateCartUI(data.cart);
                    // Menampilkan notifikasi SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan saat memperbarui keranjang.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    // Refresh UI dari state saat ini jika gagal
                    updateCartUI(cartState);
                }
            }


            // Delegasi event listener untuk tombol +/- di card menu
            document.getElementById('menu-container').addEventListener('click', (event) => {
                const button = event.target.closest('.btn-quantity-card');
                if (!button) return;

                const menuId = button.getAttribute('data-id');
                const action = button.getAttribute('data-action');
                
                if (action && menuId) {
                    updateCart(menuId, action);
                }
            });

            // Logika Reset Button (Form Pencarian)
            document.getElementById('reset-button').addEventListener('click', () => {
                searchInput.value = '';
                kategoriSelect.value = '';
                document.querySelector('form').submit();
            });

            // Panggil updateCartUI saat DOMContentLoaded untuk sinkronisasi awal
            updateCartUI(cartState);

            // Menghilangkan pesan alert setelah 3 detik (opsional, jika Anda ingin mengaktifkan alert)
            document.querySelectorAll('.alert-success, .alert-danger').forEach(alert => {
                if (alert.textContent.trim()) {
                    alert.style.display = 'block';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 3000);
                }
            });
            
            // Logika Timer Promo (dari versi DEV)
            function updatePromoTimers() {
                document.querySelectorAll('.card-menu').forEach(card => {
                    const promoEndTime = card.getAttribute('data-promo-end');
                    const timerEl = card.querySelector('.promo-timer');
                    const priceEl = card.querySelector('.price');
                    
                    if (promoEndTime && timerEl && priceEl) {
                        const now = new Date();
                        const end = new Date(promoEndTime);
                        const diff = end - now;

                        if (diff > 0) {
                            const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                            const minutes = Math.floor((diff / (1000 * 60)) % 60);
                            const seconds = Math.floor((diff / 1000) % 60);

                            timerEl.textContent = `Promo berakhir dalam: ${hours}j ${minutes}m ${seconds}d`;
                        } else {
                            timerEl.textContent = 'Promo telah berakhir';
                            // Logika untuk mengembalikan harga ke harga normal jika promo berakhir
                            const originalRawPrice = card.getAttribute('data-original-raw-price');
                            const originalPriceText = card.getAttribute('data-original-price');

                            priceEl.innerHTML = originalPriceText;
                            priceEl.classList.remove('text-danger', 'fw-bold');
                            
                            // Hapus card dari keranjang jika ada (opsional, tergantung UX)
                            // updateCart(menuId, 'remove'); // Panggil ini jika Anda ingin otomatis menghapus dari keranjang
                        }
                    }
                });
            }

            // Jalankan timer setiap detik
            setInterval(updatePromoTimers, 1000);
            updatePromoTimers(); // Panggil segera saat load
        });
    </script>
@endpush