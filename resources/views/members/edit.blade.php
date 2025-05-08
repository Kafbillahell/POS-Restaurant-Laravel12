@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Member</h1>

    <form action="{{ route('members.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $member->nama) }}" required>
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}" required>
        </div>
        <div class="mb-3">
            <label for="no_telp">No. Telp</label>
            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $member->no_telp) }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
