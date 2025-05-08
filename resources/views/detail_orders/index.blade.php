@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Detail Orders</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('detail_orders.create') }}" class="btn btn-primary mb-3">+ Tambah Detail Order</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Pemesan</th> <!-- Ganti Order ID dengan Nama Pemesan -->
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($detailOrders as $detailOrder)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detailOrder->order->nama_pemesan ?? '-' }}</td> <!-- Ambil nama pemesan dari order -->
                        <td>{{ $detailOrder->menu->nama_menu ?? '-' }}</td>
                        <td>{{ $detailOrder->jumlah }}</td>
                        <td>Rp{{ number_format($detailOrder->subtotal, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('detail_orders.edit', $detailOrder->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('detail_orders.destroy', $detailOrder->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus detail order ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada detail order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
