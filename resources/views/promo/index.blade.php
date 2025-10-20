@extends('dashboard.home')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 text-primary fw-bold"><i class="bi bi-percent"></i> Kelola Promo</h3>

    <a href="{{ route('promos.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Tambah Promo
    </a>

    @if(session('success'))
    <div id="notifModal"
        class="fixed inset-0 flex items-center justify-center bg-black/20 backdrop-blur-sm z-50 opacity-0 animate-fadein">
        <div
            class="bg-white/80 border border-green-300 rounded-3xl shadow-2xl p-6 w-80 text-center transform scale-95 opacity-0 animate-popup ring-1 ring-green-200 backdrop-blur-lg">
            <div
                class="mx-auto mb-3 flex items-center justify-center w-14 h-14 rounded-full bg-green-100 text-green-500 shadow-inner ring-2 ring-green-300">
                <i class="bi bi-check-circle-fill text-3xl"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Berhasil!</h2>
            <p class="text-gray-600 text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif


    <table class="table table-bordered table-striped align-middle mt-3">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Menu</th>
                <th>Diskon (%)</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promos as $promo)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $promo->menu->nama_menu }}</td>
                <td>{{ $promo->diskon_persen }}%</td>
                <td>{{ $promo->mulai }} s/d {{ $promo->selesai }}</td>
                <td>
                    @if($promo->isActive())
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('promos.edit', $promo->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('promos.destroy', $promo->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus promo ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('notifModal');
        const content = modal.querySelector('div');
        setTimeout(() => {
            content.classList.add('opacity-0', 'scale-90');
            modal.classList.add('opacity-0');
            modal.style.transition = 'opacity 0.7s ease';
            setTimeout(() => modal.remove(), 800);
        }, 2200);
    });
</script>
@endif

<style>
@keyframes popup {
    0% { opacity: 0; transform: scale(0.9) translateY(10px); }
    50% { opacity: 1; transform: scale(1.03) translateY(0); }
    100% { opacity: 1; transform: scale(1); }
}
@keyframes fadein {
    0% { opacity: 0; }
    100% { opacity: 1; }
}
.animate-popup {
    animation: popup 0.4s cubic-bezier(.4, 0, .2, 1);
}
.animate-fadein {
    animation: fadein 0.3s ease-in forwards;
}
</style>
@endsection
