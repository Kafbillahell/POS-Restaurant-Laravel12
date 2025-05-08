@extends('dashboard.home')

@section('content')
    <h1>Daftar Orders</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">Tambah Order</a>

    @if(session('menuBaru'))
        <div class="alert alert-success mt-3">
            Menu baru "{{ session('menuBaru')->nama_menu }}" berhasil ditambahkan dan siap dipesan.
        </div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Kategori</th>
                <th>Nama Menu</th>
                <th>Harga Menu</th>
                <th>Gambar Menu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            {{-- Tampilkan order yang sudah ada --}}
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->menu->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $order->nama_menu }}</td>
                    <td>Rp {{ number_format($order->harga_menu, 2) }}</td>
                    <td>
                        @if($order->gambar_menu)
                            <img src="{{ asset('storage/'.$order->gambar_menu) }}" alt="Gambar Menu" width="100">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('orders.create', ['menu_id' => $order->menu->id]) }}" class="btn btn-success">Order Sekarang</a>
                    </td>
                </tr>
            @endforeach

            {{-- Jika ada menu baru yg belum dipesan, tampilkan juga di bawah --}}
            @isset($menus)
                @php
                    $orderedMenuIds = $orders->pluck('menu_id')->toArray();
                @endphp
                @foreach ($menus as $menu)
                    @if (!in_array($menu->id, $orderedMenuIds))
                        <tr>
                            <td>#</td>
                            <td>{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $menu->nama_menu }}</td>
                            <td>Rp {{ number_format($menu->harga, 2) }}</td>
                            <td>
                                @if($menu->gambar)
                                    <img src="{{ asset($menu->gambar) }}" alt="Gambar Menu" width="100">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('orders.create', ['menu_id' => $menu->id]) }}" class="btn btn-success">Order Sekarang</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endisset
        </tbody>
    </table>
@endsection
