@extends('dashboard.home')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<!-- Bootstrap Icons -->
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

    .btn-info {
        background: linear-gradient(90deg, #17a2b8 60%, #6dd5ed 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 0.2rem 0.85rem;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(19, 132, 150, 0.18);
        font-size: 0.87rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.18s, box-shadow 0.18s, transform 0.17s;
    }
    .btn-info:hover, .btn-info:focus {
        background: linear-gradient(90deg, #0e798c 60%, #38b6ff 100%);
        box-shadow: 0 6px 15px rgba(11, 90, 99, 0.20);
        color: #e0f7fa;
        transform: translateY(-1.5px) scale(1.045);
        text-decoration: none;
        outline: none;
    }
    .btn-info i {
        font-size: 1.12rem;
        margin-right: 0.18rem;
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
    .table td .badge {
        font-size: 0.73rem;
        border-radius: 8px;
        padding: 0.18em 0.68em;
        font-weight: 500;
    }
    /* Empty message */
    .empty-message {
        color: #b0b8c1;
        font-style: italic;
        font-size: 1.04rem;
        letter-spacing: 0.03em;
        animation: fadeIn .7s;
        padding: 1.5rem 0;
    }
    /* Alert animation */
    #success-alert {
        animation: fadeInDown .7s cubic-bezier(.44,1.12,.82,1.02);
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
        to   { opacity: 1; transform: none;}
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
        <h2>Daftar Detail Orders <span style="font-weight:400;font-size:1.09rem;color:#7c8796;">(Ringkasan per Order)</span></h2>
        <!-- <a href="{{ route('orders.index') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle"></i> Tambah Detail Order
        </a> -->
    </div>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-secondary">
                    <tr>
                        <th scope="col" style="width: 50px;">No</th>
                        <th scope="col">Nama Pemesan</th>
                        <th scope="col">Menu</th>
                        <th scope="col" style="width: 80px;">Jumlah</th>
                        <th scope="col" style="width: 130px;">Subtotal</th>
                        <th scope="col" style="width: 130px;">Jumlah Bayar</th>
                        <th scope="col" style="width: 130px;">Kembalian</th>
                        <th scope="col" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $displayedOrders = [];
                        $no = 1;
                    @endphp

                    @forelse ($detailOrders as $detailOrder)
                        @php
                            $orderId = $detailOrder->order->id ?? null;
                        @endphp

                        @if ($orderId && !in_array($orderId, $displayedOrders))
                            <tr style="animation: fadeIn .8s;">
                                <th scope="row">{{ $no++ }}</th>
                                <td>
                                    {{ $detailOrder->order->nama_pemesan ?? '-' }}
                                    {{-- Placeholder badge status order future --}}
                                    {{-- <span class="badge bg-success ms-1">Selesai</span> --}}
                                </td>
                                <td>
                                    @php
                                        $menus = $detailOrder->order->detailOrders->pluck('menu.nama_menu')->toArray();
                                        $countMenus = count($menus);
                                        if ($countMenus == 1) {
                                            echo e($menus[0]);
                                        } elseif ($countMenus > 1) {
                                            echo e($menus[0]) . " <span style='color:#6c757d;font-size:0.96em;font-weight:400;'>dan " . ($countMenus - 1) . " menu lainnya</span>";
                                        } else {
                                            echo "-";
                                        }
                                    @endphp
                                </td>
                                <td>{{ $detailOrder->order->detailOrders->sum('jumlah') }}</td>
                                <td>Rp{{ number_format($detailOrder->order->detailOrders->sum('subtotal'), 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($detailOrder->order->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $totalOrder = $detailOrder->order->detailOrders->sum('subtotal') ?? 0;
                                        $jumlahBayar = $detailOrder->order->jumlah_bayar ?? 0;
                                        $kembalian = $jumlahBayar - $totalOrder;
                                    @endphp
                                    Rp{{ number_format($kembalian > 0 ? $kembalian : 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $orderId) }}" 
                                       class="btn btn-info btn-sm btn-detail shadow-sm">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @php
                                $displayedOrders[] = $orderId;
                            @endphp
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center empty-message">
                                Belum ada detail order.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alert success setelah 3 detik
    const alertSuccess = document.getElementById('success-alert');
    if (alertSuccess) {
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertSuccess);
            alert.close();
        }, 3000);
    }

    // Konfirmasi sebelum membuka halaman detail order, dengan efek animasi klik
    const detailButtons = document.querySelectorAll('.btn-detail');
    detailButtons.forEach(button => {
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

        // Menghapus konfirmasi sebelum membuka halaman detail order
        button.removeEventListener('click', function(event) {
            const confirmed = confirm('Apakah Anda yakin ingin melihat detail order ini?');
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});

</script>
@endsection