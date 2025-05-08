@extends('dashboard.home')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Reservasi</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('reservasis.create') }}" class="btn btn-primary mb-3">+ Tambah Reservasi</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Member</th>
                    <th>Tanggal Reservasi</th>
                    <th>Jumlah Orang</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasis as $reservasi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $reservasi->member->nama ?? '-' }}</td>
                        <td>{{ $reservasi->tanggal_reservasi }}</td>
                        <td>{{ $reservasi->jumlah_orang }}</td>
                        <td>{{ $reservasi->catatan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('reservasis.edit', $reservasi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('reservasis.destroy', $reservasi->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus reservasi ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Belum ada data reservasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
