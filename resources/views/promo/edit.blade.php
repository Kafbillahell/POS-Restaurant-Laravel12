@extends('dashboard.home')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4 text-primary fw-bold"><i class="bi bi-pencil-square"></i> Edit Promo</h3>

        <form action="{{ route('promos.update', $promo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="menu_id" class="form-label">Pilih Menu</label>
                <select name="menu_id" id="menu_id" class="form-select" required>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}" {{ $promo->menu_id == $menu->id ? 'selected' : '' }}>
                            {{ $menu->nama_menu }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="diskon_persen" class="form-label">Diskon (%)</label>
                <input type="number" name="diskon_persen" id="diskon_persen" class="form-control"
                    value="{{ $promo->diskon_persen }}" required>
            </div>

            <div class="mb-3">
                <label for="mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" name="mulai" id="mulai" class="form-control"
                    value="{{ \Carbon\Carbon::parse($promo->mulai)->format('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label for="selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" name="selesai" id="selesai" class="form-control"
                    value="{{ \Carbon\Carbon::parse($promo->selesai)->format('Y-m-d') }}" required>
            </div>


            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('promos.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection