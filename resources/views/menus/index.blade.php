@extends('dashboard.home')

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Menu</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">+ Tambah Menu</a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Nama Menu</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $menu->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $menu->nama_menu }}</td>
                            <td>{{ $menu->deskripsi }}</td>
                            <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>
                                @if ($menu->gambar)
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" width="80" alt="Gambar Menu">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus menu ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
