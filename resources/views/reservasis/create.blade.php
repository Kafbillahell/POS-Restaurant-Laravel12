@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-3">Tambah Reservasi</h1>

    <form action="{{ route('reservasis.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="member_id" class="form-label">Member (Opsional)</label>
            <select name="member_id" id="member_id" class="form-select">
                <option value="">- Tidak ada -</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}" 
                            data-nama="{{ $member->nama }}" 
                            data-telp="{{ $member->no_telp }}"
                            {{ old('member_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
            <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control" value="{{ old('nama_pemesan') }}" placeholder="Nama pemesan...">
        </div>

        <div class="mb-3">
            <label for="no_telp" class="form-label">Nomor Telepon</label>
            <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx">
        </div>

        <div class="mb-3">
            <label for="tanggal_reservasi" class="form-label">Tanggal & Waktu Reservasi</label>
            <input type="datetime-local" name="tanggal_reservasi" id="tanggal_reservasi" class="form-control" value="{{ old('tanggal_reservasi') }}">
        </div>

        <div class="mb-3">
            <label for="jumlah_orang" class="form-label">Jumlah Orang</label>
            <input type="number" name="jumlah_orang" id="jumlah_orang" class="form-control" value="{{ old('jumlah_orang') }}" placeholder="Jumlah orang...">
        </div>

        <div class="mb-3">
            <label for="down_payment" class="form-label">Down Payment (DP)</label>
            <input type="number" name="down_payment" id="down_payment" class="form-control" value="{{ old('down_payment', 0) }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('reservasis.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

{{-- SCRIPT agar nama_pemesan & no_telp otomatis terisi --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const memberSelect = document.getElementById('member_id');
        const namaPemesanInput = document.getElementById('nama_pemesan');
        const noTelpInput = document.getElementById('no_telp');

        memberSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const nama = selectedOption.getAttribute('data-nama') || '';
            const telp = selectedOption.getAttribute('data-telp') || '';

            namaPemesanInput.value = nama;
            noTelpInput.value = telp;
        });
    });
</script>
@endsection
