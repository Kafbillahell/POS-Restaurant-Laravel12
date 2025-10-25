@extends('dashboard.home')

@section('content')

@if(auth()->check() && in_array(auth()->user()->role, ['kasir', 'user']))
    <h1>Checkout Pesanan dari Keranjang</h1>

    @php
        $totalHargaFaktur = $totalBayar ?? 0;
        $totalHargaMenu = 0;
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

        <form id="checkoutForm" action="{{ route('orders.store') }}" method="POST">
            @csrf

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
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $id => $item)
                        @php
                            $hargaFinal = $item['harga']; 
                            
                            $isPromo = isset($item['harga_normal']) && $item['harga_normal'] > $hargaFinal;
                            
                            $subtotal = $hargaFinal * $item['quantity'];
                            $totalHargaMenu += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                {{ $item['nama_menu'] }}
                                @if ($isPromo)
                                    <span class="badge bg-danger text-white ms-2">PROMO</span>
                                @endif
                            </td>
                            <td>
                                @if ($isPromo)
                                    <del class="text-muted small d-block">Rp {{ number_format($item['harga_normal'], 0, ',', '.') }}</del>
                                    <strong>Rp {{ number_format($hargaFinal, 0, ',', '.') }}</strong>
                                @else
                                    Rp {{ number_format($hargaFinal, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                {{ $item['quantity'] }}
                                <input type="hidden" name="menu_id[]" value="{{ $id }}">
                                <input type="hidden" name="jumlah[]" value="{{ $item['quantity'] }}">
                                <input type="hidden" name="harga_satuan[]" value="{{ $hargaFinal }}"> 
                            </td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if (!empty($potongan) && $potongan > 0)
                <div class="text-end mb-2">
                    <p class="mb-0">Total Menu: <strong>Rp {{ number_format($totalHargaMenu, 0, ',', '.') }}</strong></p>
                    <p class="text-danger mb-0">Potongan Member: <strong>- Rp {{ number_format($potongan, 0, ',', '.') }}</strong></p>
                </div>
            @endif

            {{-- Total Harga (Harga yang harus dibayar) --}}
            <div class="form-group mb-3">
                <label>Total Harga Yang Harus Dibayar:</label>
                <p><strong>Rp {{ number_format($totalHargaFaktur, 0, ',', '.') }}</strong></p>
                <input type="hidden" name="total_harga" value="{{ $totalHargaFaktur }}">
            </div>

            {{-- Jumlah Bayar --}}
            <div class="form-group mb-3">
                <label for="jumlah_bayar">Jumlah Bayar</label>
                <input type="number" 
                        name="jumlah_bayar" 
                        id="jumlah_bayar" 
                        class="form-control" 
                        placeholder="Masukkan jumlah bayar" 
                        min="{{ $totalHargaFaktur }}"
                        value="{{ old('jumlah_bayar') ?? $totalHargaFaktur }}"> 
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
            const form = document.getElementById('checkoutForm');
            const totalHarga = {{ $totalHargaFaktur }}; 

            form.addEventListener('submit', function(e) {
                e.preventDefault(); 

                Swal.fire({
                    title: 'Konfirmasi Checkout',
                    text: "Apakah Anda yakin ingin melakukan checkout pesanan ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, checkout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            function updateKembalian() {
                const jumlahBayarInput = document.getElementById('jumlah_bayar');
                const kembalianDisplay = document.getElementById('kembalian_display');
                const kembalianInput = document.getElementById('kembalian');
                
                let jumlahBayar = parseFloat(jumlahBayarInput.value) || 0;
                let kembalian = jumlahBayar - totalHarga; 

                kembalianDisplay.value = formatRupiah(kembalian > 0 ? kembalian : 0);
                kembalianInput.value = kembalian > 0 ? kembalian : 0;
            }

            document.getElementById('jumlah_bayar').addEventListener('input', updateKembalian);
            document.addEventListener('DOMContentLoaded', updateKembalian);
        </script>
        
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        </script>
        @endif
        
    @endif

@else
    <div class="alert alert-danger">Anda tidak memiliki akses untuk menambahkan pesanan.</div>
@endif

@endsection