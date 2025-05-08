@extends('dashboard.home')

@section('content')
    <h1>Tambah Pesanan</h1>
    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Hidden menu_id jika dipilih dari menu --}}
        @if(isset($selectedMenu))
            <input type="hidden" name="menu_id" value="{{ $selectedMenu->id }}">
        @endif

        <div class="form-group">
            <label for="nama_pemesan">Nama Pemesan</label>
            <input type="text" name="nama_pemesan" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="nama_menu">Nama Menu</label>
            <input type="text" id="nama_menu" name="nama_menu" class="form-control" readonly required
                value="{{ isset($selectedMenu) ? $selectedMenu->nama_menu : '' }}">
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="text" id="harga" class="form-control" readonly
                value="{{ isset($selectedMenu) ? 'Rp ' . number_format($selectedMenu->harga, 2, ',', '.') : '' }}">
            <input type="hidden" id="harga_menu" name="harga_menu"
                value="{{ isset($selectedMenu) ? $selectedMenu->harga : '' }}">
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Pesanan</label>
            <input type="number" name="jumlah" class="form-control" required min="1">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Pesan</button>
    </form>

    <script>
        function updatePrice() {
            var menuSelect = document.getElementById('menu_id');
            var hargaInput = document.getElementById('harga');
            var hargaHidden = document.getElementById('harga_menu');
            var namaMenuInput = document.getElementById('nama_menu');
            var selectedOption = menuSelect.options[menuSelect.selectedIndex];

            var price = selectedOption.getAttribute('data-price');
            hargaInput.value = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
            hargaHidden.value = price;
            namaMenuInput.value = selectedOption.text.split(' - ')[0];
        }

        window.onload = function () {
            var menuIdFromUrl = new URLSearchParams(window.location.search).get('menu_id');
            if (menuIdFromUrl) {
                var menuSelect = document.getElementById('menu_id');
                if (menuSelect) {
                    for (var i = 0; i < menuSelect.options.length; i++) {
                        if (menuSelect.options[i].value == menuIdFromUrl) {
                            menuSelect.selectedIndex = i;
                            updatePrice();
                            break;
                        }
                    }
                }
            } else {
                if (document.getElementById('menu_id')) {
                    updatePrice();
                }
            }
        };
    </script>
@endsection
