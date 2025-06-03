@extends('dashboard.home')

@section('title', 'Detail Laporan Penjualan Bulanan')

@section('content')
<div class="container mt-5">
    <h2 class="mb-3 fw-semibold">🧾 Laporan Penjualan Bulan: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('F Y') }}</h2>
    <p class="text-muted"><strong>Kasir:</strong> {{ $user->name }}</p>

    @if($orders->isEmpty())
        <div class="alert alert-warning shadow-sm rounded">
            Tidak ada data order untuk kasir ini pada bulan {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('F Y') }}.
        </div>
    @else
        @foreach($orders as $order)
        @php
            $totalSubtotal = 0;
            foreach($order->detailOrders as $detail) {
                $totalSubtotal += $detail->subtotal;
            }
        @endphp
        <div class="card mb-4 shadow-sm border-0 rounded-4">
            <div class="card-header bg-light border-bottom">
                <div class="d-flex flex-column">
                    <span><strong>Order ID:</strong> #{{ $order->id }}</span>
                    <span><strong>Pemesan:</strong> {{ $order->nama_pemesan ?? '-' }}</span>
                    <span><strong>Waktu Order:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($order->detailOrders as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->menu->nama_menu }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end gap-4 align-items-center" style="gap: 1rem;">
                <div>
                    <span class="fw-bold me-1">Subtotal Semua:</span>
                    <span class="text-primary fw-semibold">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                </div>

                <div>
                    <span class="fw-bold me-1">Total Bayar:</span>
                    <span class="text-success fw-semibold">Rp {{ number_format($order->jumlah_bayar, 0, ',', '.') }}</span>
                </div>

                <div>
                   <span class="fw-bold me-1">Kembalian:</span>
                    <span class="text-danger fw-semibold">Rp {{ number_format($order->kembalian, 0, ',', '.') }}</span>

                </div>
            </div>
        </div>
        @endforeach
    @endif

    <a href="{{ route('reports.index', ['bulan' => $tanggal]) }}" class="btn btn-outline-secondary mt-4 rounded-pill shadow-sm">
        ← Kembali ke Laporan Bulanan
    </a>
</div>
@endsection
