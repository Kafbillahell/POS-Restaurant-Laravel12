@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Detail Order</h1>
    
    <form action="{{ route('detail_orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="order_id">Order</label>
            <select name="order_id" id="order_id" class="form-control" required>
                @foreach ($orders as $order)
                    <option value="{{ $order->id }}">{{ $order->id }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="menu_id">Menu</label>
            <select name="menu_id" id="menu_id" class="form-control" required>
                @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->nama_menu }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
        </div>

        <div class="form-group">
            <label for="subtotal">Subtotal</label>
            <input type="number" name="subtotal" id="subtotal" class="form-control" required min="0">
        </div>

        <button type="submit" class="btn btn-success mt-3">Simpan</button>
    </form>
</div>
@endsection
