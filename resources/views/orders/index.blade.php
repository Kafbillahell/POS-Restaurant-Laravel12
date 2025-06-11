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

 {{-- Kolom Daftar Menu (kiri) --}}
    <div class="row mt-4">
       <div class="col-md-8">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3 mb-3 align-items-end" style="font-family: 'Poppins', sans-serif;">
    <div class="col-md-4">
        <label for="search" class="form-label fw-semibold text-secondary mb-1">Cari Menu</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control shadow-sm rounded-3 py-2"
            placeholder="Nama menu ...">
    </div>
    <div class="col-md-4">
        <label for="kategori" class="form-label fw-semibold text-secondary mb-1">Kategori</label>
        <select name="kategori" id="kategori" class="form-select shadow-sm rounded-3 py-2">
            <option value="">All</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->nama_kategori }}" {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 d-flex gap-3">
        <!-- <button type="submit" class="btn btn-primary flex-fill rounded-pill shadow-sm px-4" style="font-weight:600;letter-spacing:.01em;">
            <i class="bi bi-search"></i> masukan
        </button> -->
        <a href="{{ route('orders.index') }}" class="btn btn-light border flex-fill rounded-pill shadow-sm px-4" style="font-weight:600;">
            <i class="bi bi-x-circle"></i> Reset
        </a>
    </div>
</form>

            
            {{-- Grouping menu per kategori di view --}}
            @php
                $kategoriOrder = ['Seafood', 'Drink', 'Cat Food'];
                $menusGrouped = $menus->groupBy(function ($item) {
                    return $item->kategori->nama_kategori ?? 'Lainnya';
                });
            @endphp

            <style>
                /* Section kategori dengan garis bawah tipis dan spacing nyaman */
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

                /* Card menu clean */
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

                /* Image area */
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

                /* Card content */
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

                /* Button */
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

            {{-- Loop kategori --}}
            <!-- @foreach ($kategoriOrder as $kategori)
                @if(isset($menusGrouped[$kategori]) && $menusGrouped[$kategori]->count() > 0)
                    <section class="category-section ">
                        <h3>{{ $kategori }}</h3>
                        <div class="d-flex flex-wrap gap-4 justify-content-start">
                            @foreach ($menusGrouped[$kategori] as $menu)
                                <div class="card-menu">
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
                                            <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                                            @if($menu->stok > 0)
                                                <button class="btn btn-add-cart add-to-cart-btn" data-id="{{ $menu->id }}">+
                                                    Keranjang</button>
                                            @else
                                                <button class="btn btn-disabled" disabled>Stock Habis</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach -->

            {{-- Kategori lain --}}
    <div id="menu-container">
    @foreach ($menusGrouped as $kategori => $menusInGroup)
        @if(!in_array($kategori, $kategoriOrder))
            <section class="category-section">
                <h3>{{ $kategori }}</h3>
                <div class="d-flex flex-wrap gap-4 justify-content-start">
                    @foreach ($menusInGroup as $menu)
                        <div class="card-menu">

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
                                <div class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>

                                <!-- ✅ Tambahkan ini -->
                                <p class="text-muted mb-2 stok-value" style="font-size: 0.9rem;">
                                    Stok: {{ $menu->stok }}
                                </p>

                                @if($menu->stok > 0)
                                   <button 
                                      class="btn btn-add-cart add-to-cart-btn" 
                                      data-id="{{ $menu->id }}" 
                                      data-max="{{ $menu->stok }}">
                                      + Keranjang
                                  </button>

                                @else
                                    <button class="btn btn-disabled" disabled>Stock Habis</button>
                                @endif
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







            {{-- Sidebar Keranjang (kanan) --}}
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
    overflow-y: scroll; /* pakai scroll supaya scrollbar selalu muncul */
    padding-left: 0;
    margin-bottom: 0;
    min-height: 200px; /* supaya scroll tetap muncul walau sedikit item */
  }

  /* supaya list item rapi */
  #cart-list li.list-group-item {
    padding: 10px 15px;
  }

  #cart-footer {
    flex-shrink: 0;
  }

  /* Scrollbar minimalis */
  #cart-list {
    scrollbar-width: thin; /* Firefox */
    scrollbar-color: #888 #f0f0f0; /* Firefox */
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
        <li class="list-group-item text-center text-muted d-flex justify-content-center align-items-center" style="min-height: 200px;">
          Keranjang kosong
        </li>
      @endforelse
    </ul>

    <div id="cart-footer" class="card-footer text-center">
      <a href="#" class="btn btn-sm w-100 btn-secondary disabled" id="checkout-button">Checkout</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const shownAlerts = new Set();
  const cartState = {};

  const searchInput = document.querySelector('#search');
  const kategoriSelect = document.querySelector('#kategori');

  function updateCartUI(cart) {
    const cartList = document.getElementById('cart-list');
    const cartFooter = document.getElementById('cart-footer');
    const checkoutButton = document.getElementById('checkout-button');

    Object.keys(cartState).forEach(id => {
      if (!cart[id]) delete cartState[id];
    });
    Object.assign(cartState, cart);

    if (Object.keys(cartState).length === 0) {
      cartList.innerHTML = `
        <li class="list-group-item text-center text-muted d-flex justify-content-center align-items-center" style="min-height: 200px;">
          Keranjang kosong
        </li>`;
      checkoutButton.classList.remove('btn-primary');
      checkoutButton.classList.add('btn-secondary', 'disabled');
      checkoutButton.setAttribute('href', '#');
      cartFooter.style.display = 'none';
      return;
    }

    let html = '';
    for (const id in cartState) {
      const item = cartState[id];
      const totalHarga = (item.harga * item.quantity).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR'
      });
      html += `
        <li class="list-group-item" data-id="${id}">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <strong>${item.nama_menu}</strong>
              <div class="mt-1 d-flex align-items-center">
                <button class="btn btn-sm btn-outline-success px-2 btn-increase" data-id="${id}">+</button>
                <span class="mx-2">x${item.quantity}</span>
                <button class="btn btn-sm btn-outline-danger px-2 btn-decrease" data-id="${id}">−</button>
              </div>
            </div>
            <span>${totalHarga}</span>
          </div>
        </li>`;
    }
    cartList.innerHTML = html;
    cartFooter.style.display = 'block';

    checkoutButton.classList.remove('btn-secondary', 'disabled');
    checkoutButton.classList.add('btn-primary');
    checkoutButton.setAttribute('href', "{{ route('orders.create') }}");
  }

  function updateStockInCard(menuId, newStock) {
    const card = document.querySelector(`.card-menu button[data-id="${menuId}"]`)?.closest('.card-menu') ||
                 document.querySelector(`.card-menu .btn-disabled[data-id="${menuId}"]`)?.closest('.card-menu');
    if (!card) return;

    const stokEl = card.querySelector('.stok-value');
    const addBtn = card.querySelector(`.add-to-cart-btn[data-id="${menuId}"]`);
    const disabledBtn = card.querySelector('.btn-disabled');

    if (stokEl) stokEl.textContent = `Stok: ${newStock}`;

    if (newStock <= 0 && addBtn) {
      addBtn.outerHTML = `<button class="btn btn-disabled" data-id="${menuId}" disabled>Stok Habis</button>`;
    } else if (newStock > 0 && disabledBtn) {
      disabledBtn.outerHTML = `
        <button class="btn btn-add-cart add-to-cart-btn" data-id="${menuId}" data-max="${newStock}">
          + Keranjang
        </button>`;
    }
  }

  function optimisticUpdate(menuId, increment) {
    if (!cartState[menuId]) {
      if (increment > 0) {
        cartState[menuId] = { nama_menu: 'Loading...', harga: 0, quantity: 0 };
      } else return;
    }

    cartState[menuId].quantity += increment;
    if (cartState[menuId].quantity <= 0) delete cartState[menuId];

    updateCartUI(cartState);

    const card = document.querySelector(`.card-menu button[data-id="${menuId}"]`)?.closest('.card-menu');
    const stokEl = card?.querySelector('.stok-value');
    if (stokEl) {
      let currentStock = parseInt(stokEl.textContent.replace(/\D/g, '')) || 0;
      currentStock -= increment;
      if (currentStock < 0) currentStock = 0;
      updateStockInCard(menuId, currentStock);
    }
  }

  async function sendUpdate(menuId, route) {
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

      if (data.status === 'success') {
        updateCartUI(data.cart);
        if (typeof data.new_stok !== 'undefined') {
          updateStockInCard(menuId, data.new_stok);
        }
      } else {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
        updateCartUI(data.cart || {});
      }
    } catch {
      Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan jaringan.' });
      updateCartUI(cartState);
    }
  }

  // Event tombol tambah/kurang di keranjang
  document.getElementById('cart-list').addEventListener('click', e => {
    if (e.target.classList.contains('btn-increase') || e.target.classList.contains('btn-decrease')) {
      const menuId = e.target.getAttribute('data-id');
      const isIncrease = e.target.classList.contains('btn-increase');
      const route = isIncrease
        ? "{{ route('orders.cart.add') }}"
        : "{{ route('orders.cart.remove') }}";

      optimisticUpdate(menuId, isIncrease ? 1 : -1);
      sendUpdate(menuId, route);
    }
  });

  // Event tombol tambah ke keranjang dari card menu
  document.body.addEventListener('click', e => {
    if (e.target.classList.contains('add-to-cart-btn')) {
      const menuId = e.target.getAttribute('data-id');
      const buttonEl = e.target;

      optimisticUpdate(menuId, 1);

      fetch("{{ route('orders.cart.add') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ menu_id: menuId })
      }).then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            updateCartUI(data.cart);

            const card = buttonEl.closest('.card-menu');
            const stokEl = card?.querySelector('.stok-value');
            if (stokEl && typeof data.new_stok !== 'undefined') {
              stokEl.textContent = `Stok: ${data.new_stok}`;
              if (data.new_stok <= 0) {
                buttonEl.outerHTML = `<button class="btn btn-disabled" disabled>Stok Habis</button>`;
              }
            }

            if (!shownAlerts.has(menuId)) {
              shownAlerts.add(menuId);
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 990,
                timerProgressBar: true
              });
            }
          } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
            updateCartUI(data.cart || {});
          }
        });
    }
  });

  // Filter pencarian dan kategori
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
      });
  }

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      clearTimeout(searchInput._timeout);
      searchInput._timeout = setTimeout(fetchFilteredMenus, 350);
    });
  }

  if (kategoriSelect) {
    kategoriSelect.addEventListener('change', fetchFilteredMenus);
  }

  // Reset cart saat pindah halaman selain /orders
  document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      const href = link.getAttribute('href');
      if (!href.includes('/orders')) {
        fetch("{{ route('orders.cart.reset') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
        shownAlerts.clear();
      }
    });
  });

  // Reset saat refresh/close
  window.addEventListener('beforeunload', () => {
    navigator.sendBeacon("{{ route('orders.cart.reset') }}", new Blob([], { type: 'application/json' }));
    shownAlerts.clear();
  });
});
</script>






@endpush
