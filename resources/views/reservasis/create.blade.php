@extends('dashboard.home')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h1 class="page-title mb-4">Tambah Reservasi</h1>
    <div class="card card-brown p-4">
    <form action="{{ route('reservasis.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="member_id" class="fw-bold text-dark-brown">Member (Opsional)</label>
            <select name="member_id" id="member_id" class="form-control form-control-brown form-select rounded-3">
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
            <label for="nama_pemesan" class="fw-bold text-dark-brown">Nama Pemesan</label>
            <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control form-control-brown rounded-3" value="{{ old('nama_pemesan') }}" placeholder="Nama pemesan...">
        </div>

        <div class="mb-3">
            <label for="no_telp" class="fw-bold text-dark-brown">Nomor Telepon</label>
            <input type="text" name="no_telp" id="no_telp" class="form-control form-control-brown rounded-3" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx">
        </div>

        <div class="mb-3">
            <label for="tanggal_reservasi" class="fw-bold text-dark-brown">Tanggal & Waktu Reservasi</label>
            <input type="datetime-local" name="tanggal_reservasi" id="tanggal_reservasi" class="form-control form-control-brown rounded-3" value="{{ old('tanggal_reservasi') }}">
        </div>

        <div class="mb-3">
            <label for="jumlah_orang" class="fw-bold text-dark-brown">Jumlah Orang</label>
            <input type="number" name="jumlah_orang" id="jumlah_orang" class="form-control form-control-brown rounded-3" value="{{ old('jumlah_orang') }}" placeholder="Jumlah orang...">
        </div>

        <div class="mb-3">
            <label for="down_payment" class="fw-bold text-dark-brown">Down Payment (DP)</label>
            <input type="number" name="down_payment" id="down_payment" class="form-control form-control-brown rounded-3" value="{{ old('down_payment', 0) }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="status" class="fw-bold text-dark-brown">Status</label>
            <select name="status" id="status" class="form-control form-control-brown form-select rounded-3">
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('reservasis.index') }}" class="btn btn-outline-brown rounded-pill px-4">Batal</a>
            <button type="submit" class="btn btn-brown rounded-pill px-4">Simpan</button>
        </div>
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
