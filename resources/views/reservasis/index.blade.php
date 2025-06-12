@extends('dashboard.home')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    body, table, .btn, h2, h1, th, td {
        font-family: 'Poppins', sans-serif !important;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.2rem;
        animation: fadeInDown .6s cubic-bezier(.44,1.12,.82,1.02);
    }
    .page-header h2 {
        font-size: 2.05rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        margin: 0;
        color: #232a35;
    }
    .btn-primary {
        background: linear-gradient(90deg, #0d6efd 60%, #5ac8fa 100%);
        border: none;
        color: #fff;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        padding: 0.5rem 1.6rem;
        box-shadow: 0 2px 16px 0 rgba(13, 110, 253, 0.11);
        transition: background 0.22s, transform 0.18s, box-shadow 0.22s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(90deg, #0b5ed7 60%, #38b6ff 100%);
        box-shadow: 0 6px 18px 0 rgba(13, 110, 253, 0.19);
        transform: translateY(-2px) scale(1.03);
        color: #fff;
    }
    .btn-warning {
        background: linear-gradient(90deg, #fbbf24 60%, #f59e42 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 18px;
        padding: 0.32rem 1rem;
        font-size: 0.94rem;
        transition: background 0.17s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(251,191,36,0.13);
    }
    .btn-warning:hover, .btn-warning:focus {
        background: linear-gradient(90deg, #eab308 60%, #f59e42 100%);
        color: #fff;
        transform: translateY(-1px) scale(1.035);
    }
    .btn-danger {
        background: linear-gradient(90deg, #dc3545 60%, #ff758c 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 18px;
        padding: 0.32rem 1rem;
        font-size: 0.94rem;
        transition: background 0.17s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(220,53,69,0.12);
    }
    .btn-danger:hover, .btn-danger:focus {
        background: linear-gradient(90deg, #a71d2a 60%, #ff758c 100%);
        color: #fff;
        transform: translateY(-1px) scale(1.035);
    }
    .card {
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 4px 32px rgba(13,110,253,0.06), 0 1.5px 4px rgba(0,0,0,0.02);
        animation: fadeInUp .7s cubic-bezier(.44,1.12,.82,1.02);
    }
    .table {
        margin-bottom: 0;
        font-size: 0.97rem;
    }
    .table thead {
        background-color: #f8f9fa !important;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.90rem;
        letter-spacing: 0.04em;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f7fbff !important;
    }
    .table-hover > tbody > tr:hover {
        background-color: #e5f0fc !important;
        transition: background 0.17s;
    }
    .table th, .table td {
        vertical-align: middle;
        padding-top: 0.72rem;
        padding-bottom: 0.72rem;
    }
    .table td {
        font-size: 0.97rem;
    }
    .badge-status {
        padding: 0.47em 1em 0.47em 1em;
        font-size: 0.85em;
        font-weight: 600;
        border-radius: 12px;
        letter-spacing: 0.02em;
        box-shadow: 0 1px 4px rgba(13,110,253,0.08);
        text-transform: capitalize;
    }
    .badge-status-success {
        background: linear-gradient(90deg, #22c55e 50%, #a7ff83 100%);
        color: #fff;
    }
    .badge-status-warning {
        background: linear-gradient(90deg, #fbbf24 60%, #fff176 100%);
        color: #fff;
    }
    .badge-status-primary {
        background: linear-gradient(90deg, #0d6efd 60%, #5ac8fa 100%);
        color: #fff;
    }
    .badge-status-danger {
        background: linear-gradient(90deg, #dc3545 60%, #ff758c 100%);
        color: #fff;
    }
    .empty-message {
        color: #b0b8c1;
        font-style: italic;
        font-size: 1.04rem;
        letter-spacing: 0.03em;
        animation: fadeIn .7s;
        padding: 1.5rem 0;
    }
    #success-alert {
        animation: fadeInDown .7s cubic-bezier(.44,1.12,.82,1.02);
    }
    .actions-group {
        display: flex;
        gap: 0.48em;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-44px);}
        to   { opacity: 1; transform: none;}
    }
    @keyframes fadeIn {
        from { opacity: 0;}
        to   { opacity: 1;}
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px);}
        to   { opacity: 1;}
    }
    @media (max-width: 650px) {
        .page-header h2 { font-size: 1.15rem; }
        .btn-primary { font-size: 0.92rem; padding: 0.46rem 1.2rem; }
        .card { border-radius: 9px; }
        .table th, .table td { font-size: 0.86rem; padding: 0.42rem 0.38rem; }
    }
    @media (max-width: 480px) {
        .page-header { flex-direction: column; align-items: flex-start; gap:0.7rem;}
        .btn-primary { width: 100%; justify-content: center;}
        .table-responsive { font-size: 0.83rem;}
        .card { padding: 0;}
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="page-header">
        <h2>Daftar Reservasi</h2>
        <a href="{{ route('reservasis.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Tambah Reservasi
        </a>
    </div>

    @if (session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary">
                    <tr>
                        <th>Nama Pemesan</th>
                        <th>Member</th>
                        <th>Tanggal</th>
                        <th>Jumlah Orang</th>
                        <th>DP</th>
                        <th>Status</th>
                        <th style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservasis as $reservasi)
                        <tr style="animation: fadeIn .8s;">
                            <td>
                                <i class="bi bi-person-fill text-primary me-1"></i>
                                {{ $reservasi->nama_pemesan }}
                            </td>
                            <td>
                                <i class="bi bi-person-badge-fill text-success me-1"></i>
                                {{ $reservasi->member?->nama ?? '-' }}
                            </td>
                            <td>
                                <i class="bi bi-calendar-event text-info me-1"></i>
                                {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <i class="bi bi-people-fill text-secondary me-1"></i>
                                {{ $reservasi->jumlah_orang }}
                            </td>
                            <td>
                                <span class="badge bg-light text-primary fw-bold shadow-sm">
                                    Rp{{ number_format($reservasi->down_payment, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($reservasi->status) {
                                        'confirmed' => 'badge-status badge-status-success',
                                        'pending' => 'badge-status badge-status-warning',
                                        'completed' => 'badge-status badge-status-primary',
                                        default => 'badge-status badge-status-danger',
                                    };
                                @endphp
                                <span class="{{ $statusClass }}">
                                    {{ ucfirst($reservasi->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-group">
                                    <a href="{{ route('reservasis.edit', $reservasi) }}" class="btn btn-warning btn-sm shadow-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('reservasis.destroy', $reservasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin hapus?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center empty-message">
                                <i class="bi bi-calendar-x me-2"></i>
                                Tidak ada data reservasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-3">
            {{ $reservasis->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alert success setelah 2.5 detik
    const alertSuccess = document.getElementById('success-alert');
    if (alertSuccess) {
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertSuccess);
            alert.close();
        }, 2500);
    }

    // Animasi klik pada tombol aksi
    const actionButtons = document.querySelectorAll('.btn-warning, .btn-danger');
    actionButtons.forEach(button => {
        button.addEventListener('mousedown', () => {
            button.style.transform = 'scale(0.96)';
            button.style.boxShadow = '0 0 0 3px #38b6ff30';
        });
        button.addEventListener('mouseup', () => {
            button.style.transform = '';
            button.style.boxShadow = '';
        });
        button.addEventListener('mouseleave', () => {
            button.style.transform = '';
            button.style.boxShadow = '';
        });
    });
});
</script>
@endsection