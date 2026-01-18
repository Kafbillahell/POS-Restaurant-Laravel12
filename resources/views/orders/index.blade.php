@extends('dashboard.home')

@section('content')
    <style>
        :root {
            --primary-brown: #795548;
            --dark-brown: #4e342e;
            --light-brown: #d7ccc8;
            --bg-beige: #fdfbf7;
            --text-dark: #3e2723;
            --card-shadow: 0 4px 20px rgba(121, 85, 72, 0.08);
            --card-hover-shadow: 0 8px 30px rgba(121, 85, 72, 0.15);
        }

        body {
            background-color: var(--bg-beige);
            font-family: 'Outfit', 'Segoe UI', sans-serif; /* Assuming Outfit or similar is available, fallback to Segoe */
        }

        /* --- Header & Search --- */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-brown);
            font-size: 2rem;
        }

        .search-bar-container {
            background: white;
            padding: 1rem;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(121, 85, 72, 0.1);
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-brown);
            box-shadow: 0 0 0 4px rgba(121, 85, 72, 0.1);
        }

        .btn-reset {
            background-color: #f5f5f5;
            color: #666;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background-color: #eee;
            color: #333;
        }

        /* --- Menu Grid --- */
        .category-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--dark-brown);
            margin-bottom: 1.5rem;
            margin-top: 2rem;
            border-bottom: 2px solid var(--light-brown);
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        .card-menu {
            background: white;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .card-img-top {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }

        .card-body {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8d6e63;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .menu-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .menu-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-brown);
            margin-bottom: 1rem;
        }

        .stok-badge {
            font-size: 0.8rem;
            color: #757575;
            background: #f5f5f5;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        /* --- Buttons --- */
        .btn-add-cart {
            background: var(--primary-brown);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-add-cart:hover {
            background: var(--dark-brown);
            color: white;
        }

        .qty-control {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 4px;
        }

        .btn-qty {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-qty.minus {
            background: white;
            color: var(--primary-brown);
            border: 1px solid var(--light-brown);
        }

        .btn-qty.plus {
            background: var(--primary-brown);
            color: white;
        }

        .qty-display {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1rem;
        }

        /* --- Cart Panel --- */
        #cart-target {
            position: fixed;
            top: 100px; /* Adjusted for header */
            right: 30px;
            z-index: 100;
            width: 380px;
            height: calc(100vh - 130px);
        }

        .cart-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(121, 85, 72, 0.1);
        }

        .cart-header {
            background: var(--dark-brown);
            color: white;
            padding: 1.5rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-body {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0;
            background: #fff;
        }

        .cart-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .cart-item:hover {
            background: #fafafa;
        }

        .cart-item-title {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .cart-item-price {
            font-size: 0.9rem;
            color: #757575;
        }

        .cart-footer {
            background: #fafafa;
            padding: 1.5rem;
            border-top: 1px solid #eee;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .total-label {
            font-size: 1rem;
            color: #666;
            font-weight: 600;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-brown);
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
            color: white;
            border: none;
            border-radius: 16px;
            padding: 1rem;
            width: 100%;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(121, 85, 72, 0.3);
            transition: all 0.3s;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(121, 85, 72, 0.4);
            color: white;
        }
        
        .btn-checkout.disabled {
            background: #e0e0e0;
            box-shadow: none;
            cursor: not-allowed;
            color: #999;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #d7ccc8;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a1887f;
        }
    </style>

    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center page-header">
            <div>
                <h1 class="page-title">Order Menu</h1>
                <p class="text-muted mb-0">Select items to add to the customer's order</p>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="background-color: #d4edda; color: #155724;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="background-color: #f8d7da; color: #721c24;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Menu Grid -->
            <div class="col-lg-8 col-md-7 pb-5">
                
                <!-- Search & Filter -->
                <form action="{{ route('orders.index') }}" method="GET" class="search-bar-container mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted ps-3">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-control border-start-0 ps-0" placeholder="Search menu...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->nama_kategori }}" {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-reset w-100 py-2" id="reset-button">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Menu Container -->
                @php
                    $kategoriOrder = ['Seafood', 'Drink', 'Cat Food'];
                    $menusGrouped = $menus->groupBy(function ($item) {
                        return $item->kategori->nama_kategori ?? 'Lainnya';
                    });
                    $cartState = session('cart', []);
                @endphp

                <div id="menu-container">
                    @if ($menus->count() === 0)
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/13637/13637462.png" alt="No Data" style="width: 100px; opacity: 0.5;">
                            <p class="text-muted mt-3">No menu items found.</p>
                        </div>
                    @endif

                    @foreach ($menusGrouped as $kategori => $menusInGroup)
                        @if(!in_array($kategori, $kategoriOrder))
                            <div class="category-section">
                                <h3 class="category-title">{{ $kategori }}</h3>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
                                    @foreach ($menusInGroup as $menu)
                                        @php
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
                                            
                                            $initialQty = $cartState[$menu->id]['quantity'] ?? 0;
                                            $currentStokView = $menu->stok - $initialQty;
                                        @endphp

                                        <div class="col">
                                            <div class="card-menu"
                                                data-promo-end="{{ $promoEndTime }}" 
                                                data-original-price="{{ $originalPriceText }}"
                                                data-original-raw-price="{{ $rawOriginalPrice }}">
                                                
                                                <div class="position-relative">
                                                    @if($menu->gambar)
                                                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="card-img-top">
                                                    @else
                                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light text-muted">
                                                            <i class="bi bi-image fs-1"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($isPromoActive)
                                                        <div class="position-absolute top-0 end-0 m-2 badge bg-danger shadow-sm">
                                                            PROMO
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="card-body">
                                                    <div class="menu-category">{{ $menu->kategori->nama_kategori ?? '-' }}</div>
                                                    <h5 class="menu-title">{{ $menu->nama_menu }}</h5>
                                                    
                                                    <div class="price @if ($isPromoActive) text-danger @endif" data-harga-jual="{{ $currentPrice }}">
                                                        @if ($isPromoActive)
                                                            <span class="text-decoration-line-through text-muted small me-1" style="font-weight: 400;">
                                                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                                            </span>
                                                            <span class="menu-price">
                                                                Rp {{ number_format($currentPrice, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="menu-price">
                                                                Rp {{ number_format($currentPrice, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if ($isPromoActive)
                                                        <div class="promo-timer text-danger mb-2 small fw-bold"></div>
                                                    @endif

                                                    <div class="mt-auto">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="stok-badge stok-value">Stok: {{ $currentStokView }}</span>
                                                        </div>

                                                        <div data-menu-id="{{ $menu->id }}">
                                                            @if($menu->stok > 0)
                                                                <!-- Add to Cart Button -->
                                                                <button class="btn-add-cart add-to-cart-initial btn-quantity-card"
                                                                    data-action="increase" data-id="{{ $menu->id }}"
                                                                    style="display: {{ $initialQty > 0 ? 'none' : 'block' }};">
                                                                    <i class="bi bi-plus-lg me-1"></i> Add to Order
                                                                </button>

                                                                <!-- Quantity Control -->
                                                                <div class="qty-control" style="display: {{ $initialQty > 0 ? 'flex' : 'none' }};">
                                                                    <button class="btn-qty minus btn-quantity-card" data-action="decrease" data-id="{{ $menu->id }}">
                                                                        <i class="bi bi-dash"></i>
                                                                    </button>
                                                                    <span class="qty-display quantity-value-card" data-id="{{ $menu->id }}">
                                                                        {{ $initialQty }}
                                                                    </span>
                                                                    <button class="btn-qty plus btn-quantity-card" data-action="increase" data-id="{{ $menu->id }}">
                                                                        <i class="bi bi-plus"></i>
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <button class="btn btn-secondary w-100 disabled" disabled>Out of Stock</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Fixed Cart -->
            <div class="col-lg-4 col-md-5 d-none d-md-block">
                <div id="cart-target">
                    <div class="cart-card">
                        <div class="cart-header">
                            <i class="bi bi-basket2-fill"></i> Current Order
                        </div>

                        <div class="cart-body">
                            <ul class="list-group list-group-flush" id="cart-list">
                                @php $cart = []; @endphp
                                @forelse ($cart as $id => $item)
                                    <!-- JS will populate this -->
                                @empty
                                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted p-4">
                                        <i class="bi bi-cart-x fs-1 mb-3 opacity-25"></i>
                                        <p>No items selected yet</p>
                                    </div>
                                @endforelse
                            </ul>
                        </div>

                        <div class="cart-footer" id="cart-footer" style="display: none;">
                            <div class="total-row">
                                <span class="total-label">Total Amount</span>
                                <span class="total-amount" id="cart-subtotal">Rp 0</span>
                            </div>
                            <a href="#" class="btn-checkout disabled" id="checkout-button">
                                Process Payment <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const cartState = {};
            const searchInput = document.querySelector('#search');
            const kategoriSelect = document.querySelector('#kategori');

            function startPromoTimers() {
                document.querySelectorAll('.card-menu').forEach(card => {
                    const promoEndTimestamp = card.getAttribute('data-promo-end');
                    const originalPriceText = card.getAttribute('data-original-price'); 
                    const originalRawPrice = parseFloat(card.getAttribute('data-original-raw-price')) || 0;
                    const priceElement = card.querySelector('.price');
                    const promoTimerElement = card.querySelector('.promo-timer');
                    
                    if (!promoEndTimestamp || !originalPriceText || !priceElement || !promoTimerElement) {
                        priceElement.setAttribute('data-harga-jual', originalRawPrice);
                        return;
                    }

                    const promoEndTime = new Date(promoEndTimestamp).getTime();
                    const menuId = card.querySelector('div[data-menu-id]')?.getAttribute('data-menu-id');
                    
                    if (card.promoInterval) {
                        clearInterval(card.promoInterval);
                    }

                    function updateTimer() {
                        const now = new Date().getTime();
                        const distance = promoEndTime - now;

                        if (distance < 0) {
                            clearInterval(card.promoInterval);
                            promoTimerElement.textContent = 'PROMO ENDED';
                            promoTimerElement.classList.remove('text-danger', 'text-warning', 'fw-bold');
                            promoTimerElement.classList.add('text-muted');
                            
                            // Revert price display logic if needed, simplified here
                            priceElement.innerHTML = `<span class="menu-price">${originalPriceText}</span>`;
                            priceElement.classList.remove('text-danger');
                            
                            priceElement.setAttribute('data-harga-jual', originalRawPrice);
                            
                            if (menuId && cartState[menuId]) {
                                cartState[menuId].harga = originalRawPrice; 
                                updateCartUI(cartState); 
                            }
                            return;
                        }

                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        const timerText = `Ends in: ${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                        promoTimerElement.textContent = timerText;
                    }

                    updateTimer(); 
                    card.promoInterval = setInterval(updateTimer, 1000);
                });
            }
            
            function updateCardButtons(menuId, quantity, maxStock) {
                const container = document.querySelector(`div[data-menu-id="${menuId}"]`);
                if (!container) return;

                const initialBtn = container.querySelector('.add-to-cart-initial');
                const qtyControl = container.querySelector('.qty-control');
                const quantityEl = container.querySelector('.quantity-value-card');
                const stokEl = container.closest('.card-body').querySelector('.stok-value');

                if (stokEl) {
                    const currentStok = maxStock - quantity;
                    stokEl.textContent = `Stok: ${currentStok}`;
                }
                if (quantityEl) {
                    quantityEl.textContent = quantity;
                }

                if (quantity > 0) {
                    if (initialBtn) initialBtn.style.display = 'none';
                    if (qtyControl) qtyControl.style.display = 'flex';
                } else {
                    if (initialBtn) initialBtn.style.display = 'block';
                    if (qtyControl) qtyControl.style.display = 'none';
                }
                
                // Disable plus button if max stock reached
                const plusBtn = container.querySelector('.btn-qty.plus');
                if (plusBtn) {
                    if (quantity >= maxStock) {
                        plusBtn.disabled = true;
                        plusBtn.style.opacity = '0.5';
                    } else {
                        plusBtn.disabled = false;
                        plusBtn.style.opacity = '1';
                    }
                }
            }

            function updateCartUI(cart) {
                const cartList = document.getElementById('cart-list');
                const cartFooter = document.getElementById('cart-footer');
                const checkoutButton = document.getElementById('checkout-button');

                // Sync local state
                Object.keys(cartState).forEach(id => {
                    if (!cart[id]) delete cartState[id];
                });
                Object.assign(cartState, cart);

                let subtotal = 0;
                let html = '';

                for (const id in cartState) {
                    const item = cartState[id];
                    subtotal += item.harga * item.quantity;
                    const totalHarga = (item.harga * item.quantity).toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    });
                    html += `
                    <li class="cart-item" data-id="${id}">
                        <div>
                            <div class="cart-item-title">${item.nama_menu}</div>
                            <div class="cart-item-price">${item.quantity} x Rp ${item.harga.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="fw-bold text-dark">${totalHarga}</div>
                    </li>`;
                }

                if (Object.keys(cartState).length === 0) {
                    cartList.innerHTML = `
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted p-4">
                        <i class="bi bi-cart-x fs-1 mb-3 opacity-25"></i>
                        <p>No items selected yet</p>
                    </div>`;
                    
                    if(checkoutButton) {
                        checkoutButton.classList.add('disabled');
                        checkoutButton.setAttribute('href', '#');
                    }
                    if(cartFooter) cartFooter.style.display = 'none';
                } else {
                    cartList.innerHTML = html;
                    if(cartFooter) cartFooter.style.display = 'block';

                    const totalEl = document.getElementById('cart-subtotal');
                    if (totalEl) {
                        totalEl.textContent = subtotal.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });
                    }

                    if(checkoutButton) {
                        checkoutButton.classList.remove('disabled');
                        checkoutButton.setAttribute('href', "{{ route('orders.create') }}");
                    }
                }

                // Update all cards on page
                document.querySelectorAll('.card-menu').forEach(card => {
                    const menuId = card.querySelector('div[data-menu-id]')?.getAttribute('data-menu-id');
                    if (menuId) {
                        const cartItem = cartState[menuId] || { quantity: 0 };
                        let maxStock;
                        const stokEl = card.querySelector('.stok-value');
                        
                        if (stokEl) {
                            const currentStokView = parseInt(stokEl.textContent.replace('Stok: ', '')) || 0;
                            maxStock = currentStokView + cartItem.quantity;
                        } else {
                            maxStock = cartItem.quantity > 0 ? cartItem.quantity : 0;
                        }

                        updateCardButtons(menuId, cartItem.quantity, maxStock);
                    }
                });
            }

            function performOptimisticUpdate(menuId, action) {
                const isIncrease = action === 'increase';
                const increment = isIncrease ? 1 : -1;
                let currentQuantity = cartState[menuId] ? cartState[menuId].quantity : 0;

                const cardContainer = document.querySelector(`div[data-menu-id="${menuId}"]`);
                if (!cardContainer) return false;

                const stokEl = cardContainer.closest('.card-body').querySelector('.stok-value');
                if (!stokEl) return false;

                const currentStokView = parseInt(stokEl.textContent.replace('Stok: ', '')) || 0;
                const totalMaxStock = currentQuantity + currentStokView; 

                if (isIncrease && currentQuantity >= totalMaxStock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Out of Stock',
                        text: 'Cannot add more items than available stock.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    return false;
                }
                if (!isIncrease && currentQuantity <= 0) {
                    return false;
                }

                const newQuantity = currentQuantity + increment;
                let namaMenu = '';

                if (newQuantity > 0) {
                    if (!cartState[menuId]) {
                        const cardBody = cardContainer.closest('.card-body');
                        namaMenu = cardBody.querySelector('.menu-title')?.textContent || '';

                        const priceElement = cardBody.querySelector('.price');
                        const harga = parseInt(priceElement?.getAttribute('data-harga-jual')) || 0;

                        cartState[menuId] = {
                            nama_menu: namaMenu.trim(),
                            harga: harga,
                            quantity: newQuantity
                        };
                    } else {
                        cartState[menuId].quantity = newQuantity;
                        namaMenu = cartState[menuId].nama_menu;
                    }
                } else {
                    namaMenu = cartState[menuId]?.nama_menu || 'Item';
                    delete cartState[menuId];
                }

                updateCartUI(cartState);
                updateCardButtons(menuId, newQuantity, totalMaxStock);
                
                return true;
            }

            async function sendUpdate(menuId, action) {
                const route = action === 'increase' ? "{{ route('orders.cart.add') }}" : "{{ route('orders.cart.remove') }}";
                const previousCartState = JSON.parse(JSON.stringify(cartState));

                try {
                    const res = await fetch(route, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ menu_id: menuId })
                    });
                    const data = await res.json();

                    if (data.status !== 'success') {
                        // Revert on failure
                        updateCartUI(previousCartState);
                    }

                } catch (error) {
                    // Revert on network error
                    updateCartUI(previousCartState);
                }
            }

            document.body.addEventListener('click', e => {
                const target = e.target.closest('.btn-quantity-card');
                if (target) {
                    const menuId = target.getAttribute('data-id');
                    const action = target.getAttribute('data-action');

                    if (performOptimisticUpdate(menuId, action)) {
                        sendUpdate(menuId, action);
                    }
                }
            });

            const resetButton = document.querySelector('#reset-button');
            if (resetButton) {
                resetButton.addEventListener('click', (e) => {
                    if (searchInput) searchInput.value = '';
                    if (kategoriSelect) kategoriSelect.value = '';
                    fetchFilteredMenus();
                });
            }

            function fetchFilteredMenus() {
                const search = searchInput?.value || '';
                const kategori = kategoriSelect?.value || '';

                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (kategori) params.append('kategori', kategori);

                fetch(`{{ route('orders.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const newDoc = parser.parseFromString(html, 'text/html');
                        const newMenu = newDoc.querySelector('#menu-container');
                        document.querySelector('#menu-container').innerHTML = newMenu.innerHTML;

                        updateCartUI(cartState);
                        startPromoTimers(); 
                    });
            }

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(searchInput._timeout);
                    searchInput._timeout = setTimeout(fetchFilteredMenus, 300);
                });
            }

            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', fetchFilteredMenus);
            }
            
            // Initial Load
            updateCartUI(@json(session('cart', [])));
            startPromoTimers();
        });
    </script>
@endpush