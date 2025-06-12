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
    .badge-point {
        background: linear-gradient(90deg, #0d6efd 40%, #5ac8fa 100%);
        color: #fff;
        font-weight: 500;
        border-radius: 10px;
        padding: 0.22em 0.8em;
        font-size: 0.86rem;
        letter-spacing: 0.02em;
        box-shadow: 0 1px 8px rgba(13,110,253,0.15);
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
    /* Avatar Circle */
    .avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 0.7em;
        border: 2.5px solid #e8eaf1;
        box-shadow: 0 2px 10px 0 rgba(13,110,253,0.07);
        background: #f7fbff;
    }
    .td-name {
        display: flex;
        align-items: center;
        gap: 0.3em;
        font-weight: 500;
        color: #232a35;
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
        .avatar-circle { width: 30px; height: 30px; }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="page-header">
        <h2>Data Members</h2>
        <a href="{{ route('members.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Tambah Member
        </a>
    </div>
    @if(session('success'))
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
                        <th style="width: 50px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telepon</th>
                        <th>Points</th>
                        <th style="width: 135px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr style="animation: fadeIn .8s;">
                            <td>{{ $loop->iteration }}</td>
                            <td class="td-name">
                                @php
                                    $avatar = $member->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($member->nama).'&background=0d6efd&color=fff&bold=true';
                                @endphp
                                <img src="{{ $avatar }}" class="avatar-circle me-2" alt="Avatar">
                                {{ $member->nama }}
                            </td>
                            <td>
                                <i class="bi bi-envelope-at text-primary me-1"></i>
                                {{ $member->email }}
                            </td>
                            <td>
                                <i class="bi bi-telephone text-success me-1"></i>
                                {{ $member->no_telp }}
                            </td>
                            <td>
                                <span class="badge-point">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ $member->points }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-group">
                                    <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm shadow-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')" style="display:inline;">
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
                            <td colspan="6" class="text-center empty-message">
                                <i class="bi bi-person-x me-2"></i>
                                Tidak ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-3">
            {{ $members->links() }}
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