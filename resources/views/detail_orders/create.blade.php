@extends('dashboard.home')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h1 class="page-title mb-4">Tambah Detail Order</h1>
    <div class="card card-brown p-4">
    <form action="{{ route('detail_orders.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="order_id" class="fw-bold text-dark-brown">Order</label>
            <select name="order_id" id="order_id" class="form-control form-control-brown form-select rounded-3" required>
                @foreach ($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->id }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group mb-3">
            <label for="menu_id" class="fw-bold text-dark-brown">Menu</label>
            <select name="menu_id" id="menu_id" class="form-control form-control-brown form-select rounded-3" required>
                @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->nama_menu }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="jumlah" class="fw-bold text-dark-brown">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control form-control-brown rounded-3" required min="1">
        </div>

        <div class="form-group mb-3">
            <label for="subtotal" class="fw-bold text-dark-brown">Subtotal</label>
            <input type="number" name="subtotal" id="subtotal" class="form-control form-control-brown rounded-3" required min="0">
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-brown rounded-pill px-4">Simpan</button>
        </div>
    </form>
    </div>
</div>
@endsection
