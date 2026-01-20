@extends('dashboard.home')

@section('content')
    <style>
    <style>
        /* Theme variables moved to custom.css */

        /* --- Header & Search --- */


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

        /* --- Category Sidebar --- */
        .category-sidebar {
            position: sticky;
            top: 100px;
            background: white;
            border: 1px solid #d7ccc8;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--card-shadow);
        }

        .category-item {
            display: block;
            padding: 12px 20px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: #6d4c41;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            text-align: left;
            width: 100%;
        }

        .category-item:hover {
            background-color: rgba(121, 85, 72, 0.05);
            color: var(--primary-brown);
            transform: translateX(5px);
        }

        .category-item.active {
            background: linear-gradient(135deg, var(--primary-brown), var(--dark-brown));
            color: white;
            box-shadow: 0 4px 15px rgba(121, 85, 72, 0.3);
        }

        .category-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* --- Menu Grid --- */
        .category-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--dark-brown);
            margin-bottom: 1.5rem;
            margin-top: 1rem;
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
            height: 160px;
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
            top: 100px;
            right: 30px;
            z-index: 100;
            width: 350px;
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
            <!-- Column 1: Categories (Sidebar) -->
            <div class="col-lg-2 col-md-3 d-none d-md-block">
                <div class="category-sidebar">
                    <h5 class="mb-3 fw-bold" style="color: var(--dark-brown);">Categories</h5>
                    
                    <button class="category-item active" onclick="filterCategory('')">
                        <i class="bi bi-grid-fill"></i> All Menu
                    </button>
                    
                    @foreach($kategoris as $kategori)
                        <button class="category-item" onclick="filterCategory('{{ $kategori->nama_kategori }}')">
                            <i class="bi bi-tag-fill"></i> {{ $kategori->nama_kategori }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Column 2: Menu Grid (Center) -->
            <div class="col-lg-7 col-md-9 pb-5">
                
                <!-- Search Bar (Simplified) -->
                <div class="mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-7">
                            <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden; border: 1px solid #e0e0e0;">
                                <span class="input-group-text bg-white border-0 text-muted ps-3">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="search-input" class="form-control border-0 ps-0 py-2" placeholder="Search menu..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="button" class="btn btn-reset w-100 py-2 shadow-sm" id="reset-button">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

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

            <!-- Column 3: Fixed Cart (Right) -->
            <div class="col-lg-3 col-md-12 d-none d-lg-block">
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
            const searchInput = document.querySelector('#search-input');
            
            // Client-side filtering logic
            let activeCategory = "";
            let searchTerm = "";

            function filterMenus() {
                const term = searchTerm.toLowerCase();
                const category = activeCategory;

                const sections = document.querySelectorAll('.category-section');
                let hasVisibleItems = false;

                sections.forEach(section => {
                    const sectionTitle = section.querySelector('.category-title').textContent.trim();
                    const items = section.querySelectorAll('.col'); // The grid columns containing cards
                    let sectionHasVisible = false;
                    
                    const sectionMatchesCategory = category === '' || sectionTitle === category;

                    if (!sectionMatchesCategory) {
                        section.style.display = 'none';
                        return;
                    }

                    items.forEach(item => {
                        const card = item.querySelector('.card-menu');
                        const title = card.querySelector('.menu-title').textContent.toLowerCase();
                        const matchesSearch = title.includes(term);

                        if (matchesSearch) {
                            item.style.display = 'block';
                            sectionHasVisible = true;
                            hasVisibleItems = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    if (sectionHasVisible) {
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });
                
                // Show/Hide "No Data" message
                let noDataMsg = document.getElementById('no-data-message');
                if (!hasVisibleItems) {
                    if (!noDataMsg) {
                        const container = document.getElementById('menu-container');
                        noDataMsg = document.createElement('div');
                        noDataMsg.id = 'no-data-message';
                        noDataMsg.className = 'text-center py-5';
                        noDataMsg.innerHTML = `
                            <img src="https://cdn-icons-png.flaticon.com/512/13637/13637462.png" alt="No Data" style="width: 100px; opacity: 0.5;">
                            <p class="text-muted mt-3">No menu items found.</p>
                        `;
                        container.appendChild(noDataMsg);
                    }
                    noDataMsg.style.display = 'block';
                } else {
                    if (noDataMsg) noDataMsg.style.display = 'none';
                }
            }

            // Expose filter function globally
            window.filterCategory = function(categoryName) {
                activeCategory = categoryName;
                
                // Update active state visually
                document.querySelectorAll('.category-item').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                const activeBtn = Array.from(document.querySelectorAll('.category-item')).find(btn => {
                    return btn.textContent.trim().includes(categoryName) && categoryName !== '' || 
                           (categoryName === '' && btn.textContent.trim().includes('All Menu'));
                });
                
                if (activeBtn) activeBtn.classList.add('active');

                filterMenus();
            };

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    searchTerm = e.target.value;
                    filterMenus();
                });
            }

            const resetButton = document.querySelector('#reset-button');
            if (resetButton) {
                resetButton.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    searchTerm = '';
                    window.filterCategory('');
                });
            }

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
                        updateCartUI(previousCartState);
                    }

                } catch (error) {
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
            
            // Initial Load
            updateCartUI(@json(session('cart', [])));
            startPromoTimers();
        });
    </script>
@endpush