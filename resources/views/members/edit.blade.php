@extends('dashboard.home')

@section('content')
<div class="container">
    <h1>Edit Member</h1>

    <form action="{{ route('members.update', $member) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $member->nama) }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}" required>
        </div>

        <div class="mb-3">
            <label>No Telepon</label>
            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $member->no_telp) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
