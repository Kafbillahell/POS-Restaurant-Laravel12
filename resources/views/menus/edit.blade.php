@extends('dashboard.home')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold">Edit Menu</h4>
    </div>

    <form id="editMenuForm" action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded-4">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ $kategori->id == $menu->kategori_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" required value="{{ old('nama_menu', $menu->nama_menu) }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Harga</label>
            <input type="number" name="harga" class="form-control" required step="0.01" value="{{ old('harga', $menu->harga) }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Gambar</label>
            @if($menu->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Gambar Menu" class="img-thumbnail" style="max-height: 150px;">
                </div>
            @endif
            <input type="file" name="gambar" class="form-control" accept="image/*">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Stok</label>
            <input type="number" name="stok" class="form-control" required min="0" value="{{ old('stok', $menu->stok) }}">
        </div>

        <div class="text-end">
          <button type="submit" id="submitButton" class="btn btn-danger mt-3 px-4">
    <i class="bi bi-check-circle me-1"></i> Update
</button>


        </div>
    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('editMenuForm');
        const submitButton = document.getElementById('submitButton');

        const getInitialValues = () => {
            const values = {};
            for (let element of form.elements) {
                if (element.name && element.type !== 'file') {
                    values[element.name] = element.value.trim();
                }
            }
            return values;
        };

        const initialValues = getInitialValues();

        const isFormChanged = () => {
            for (let element of form.elements) {
                if (element.name && element.type !== 'file') {
                    let current = element.value.trim();
                    let original = initialValues[element.name];

                    // Untuk number, samakan 0 dan "0", "" dan "0"
                    if (element.type === 'number') {
                        current = current === '' ? '0' : current;
                        original = original === '' ? '0' : original;
                    }

                    if (current !== original) {
                        return true;
                    }
                }
            }
            return false;
        };

        const updateButton = () => {
            if (isFormChanged()) {
                submitButton.classList.remove('btn-danger');
                submitButton.classList.add('btn-success');
                submitButton.innerHTML = `<i class="bi bi-check-circle me-1"></i> Update`;
            } else {
                submitButton.classList.remove('btn-success');
                submitButton.classList.add('btn-danger');
                submitButton.innerHTML = `<i class="bi bi-check-circle me-1"></i> Update`;
            }
        };

        form.addEventListener('input', updateButton);
        form.addEventListener('change', updateButton);
    });
</script>
@endpush



@endsection
