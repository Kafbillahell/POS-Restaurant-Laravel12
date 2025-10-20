@extends('dashboard.home')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-primary fw-bold"><i class="bi bi-percent"></i> Tambah Promo</h3>

    <form action="{{ route('promos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="menu_id" class="form-label">Pilih Menu</label>
            <select name="menu_id" id="menu_id" class="form-select" required>
                @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}">{{ $menu->nama_menu }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="diskon_persen" class="form-label">Diskon (%)</label>
            <input type="number" name="diskon_persen" id="diskon_persen" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="mulai" id="mulai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="selesai" class="form-label">Tanggal Selesai</label>
            <input type="date" name="selesai" id="selesai" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('promos.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
