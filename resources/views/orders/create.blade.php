@extends('dashboard.home')

@section('content')

@if(auth()->check() && in_array(auth()->user()->role, ['kasir', 'user']))
    <h1>Checkout Pesanan dari Keranjang</h1>

    @php
        $cart = session('cart', []);
        $totalHarga = 0;
    @endphp

    @if(count($cart) === 0)
        <div class="alert alert-warning">Keranjang Anda kosong. Silakan tambahkan menu terlebih dahulu.</div>
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Kembali ke Menu</a>
    @else
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            {{-- Nama Kasir --}}
           {{-- Nama Kasir --}}
            <div class="form-group mb-3">
                <label for="nama_kasir">Nama Kasir</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                <input type="hidden" name="nama_kasir" value="{{ auth()->user()->name }}">
            </div>


            {{-- Nama Pemesan --}}
            <div class="form-group mb-3">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control" required value="{{ old('nama_pemesan') }}">
            </div>

            {{-- Daftar Pesanan --}}
            <h3>Pesanan Anda</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $id => $item)
                        @php
                            $subtotal = $item['harga'] * $item['quantity'];
                            $totalHarga += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $item['nama_menu'] }}</td>
                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td>
                                {{ $item['quantity'] }}
                                <input type="hidden" name="menu_id[]" value="{{ $id }}">
                                <input type="hidden" name="jumlah[]" value="{{ $item['quantity'] }}">
                            </td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total Harga --}}
            <div class="form-group mb-3">
                <label>Total Harga:</label>
                <p><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></p>
                <input type="hidden" name="total_harga" value="{{ $totalHarga }}">
            </div>

            {{-- Jumlah Bayar --}}
            <div class="form-group mb-3">
    <label for="jumlah_bayar">Jumlah Bayar</label>
    <input type="number" 
           name="jumlah_bayar" 
           id="jumlah_bayar" 
           class="form-control" 
           placeholder="Masukkan jumlah bayar" 
           min="{{ $totalHarga }}"
           value="{{ old('jumlah_bayar') }}">
</div>


            {{-- Kembalian --}}
            <div class="form-group mb-3">
                <label for="kembalian_display">Kembalian</label>
                <input type="text" id="kembalian_display" class="form-control" readonly>
                <input type="hidden" name="kembalian" id="kembalian">
            </div>

            <button type="submit" class="btn btn-success mt-3">Checkout</button>
        </form>

        <script>
            function updateKembalian() {
                const jumlahBayarInput = document.getElementById('jumlah_bayar');
                const kembalianDisplay = document.getElementById('kembalian_display');
                const kembalianInput = document.getElementById('kembalian');
                const totalHarga = {{ $totalHarga }};

                let jumlahBayar = parseFloat(jumlahBayarInput.value) || 0;
                let kembalian = jumlahBayar - totalHarga;

                kembalianDisplay.value = formatRupiah(kembalian > 0 ? kembalian : 0);
                kembalianInput.value = kembalian > 0 ? kembalian : 0;
            }

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(angka);
            }

            document.getElementById('jumlah_bayar').addEventListener('input', updateKembalian);
            document.addEventListener('DOMContentLoaded', updateKembalian);
        </script>
    @endif

@else
    <div class="alert alert-danger">Anda tidak memiliki akses untuk menambahkan pesanan.</div>
@endif

@endsection
