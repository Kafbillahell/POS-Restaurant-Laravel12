@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Reservasi</h1>

    <form action="{{ route('reservasis.update', $reservasi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Member</label>
            <select name="member_id" class="form-control" required>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ $reservasi->member_id == $member->id ? 'selected' : '' }}>
                        {{ $member->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Reservasi</label>
            <input type="datetime-local" name="tanggal_reservasi" class="form-control" value="{{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Orang</label>
            <input type="number" name="jumlah_orang" class="form-control" value="{{ $reservasi->jumlah_orang }}" required>
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="catatan" class="form-control">{{ $reservasi->catatan }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('reservasis.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
