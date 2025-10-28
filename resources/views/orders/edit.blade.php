    @extends('dashboard.home')

    @section('content')
        <h1>Edit Order</h1>
        <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="menu_id">Menu</label>
                <select name="menu_id" id="menu_id" class="form-control">
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}" @if($order->menu_id == $menu->id) selected @endif>{{ $menu->nama_menu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="nama_menu">Nama Menu</label>
                <input type="text" name="nama_menu" id="nama_menu" class="form-control" value="{{ $order->nama_menu }}" required>
            </div>
            <div class="form-group">
                <label for="harga_menu">Harga Menu</label>
                <input type="number" name="harga_menu" id="harga_menu" class="form-control" value="{{ $order->harga_menu }}" required>
            </div>
            <div class="form-group">
                <label for="gambar_menu">Gambar Menu</label>
                <input type="file" name="gambar_menu" id="gambar_menu" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    @endsection
