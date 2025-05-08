@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Reservasi</h1>

    <form action="{{ route('reservasis.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Member</label>
            <select name="member_id" class="form-control" required>
                <option value="">Pilih Member</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Reservasi</label>
            <input type="datetime-local" name="tanggal_reservasi" class="form-control"
       value="{{ old('tanggal_reservasi', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}">

        </div>

        <div class="mb-3">
            <label>Jumlah Orang</label>
            <input type="number" name="jumlah_orang" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('reservasis.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
