@extends('dashboard.home')

@section('title', 'Laporan Penjualan')

@section('content')
<style>
    .laporan-heading {
        font-size: 2rem;
        font-weight: 700;
        color: #195b9a;
        letter-spacing: 0.03em;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: .7rem;
    }
    .card-group {
        gap: 1.2rem;
    }
    .laporan-summary-card {
        border-radius: 1.25rem !important;
        box-shadow: 0 8px 32px rgba(44,62,80,0.10), 0 2px 8px rgba(44,62,80,0.03) !important;
        border: none !important;
        min-width: 230px;
        background: linear-gradient(120deg, #fafdff 70%, #e4f0f7 100%);
        transition: box-shadow .17s, transform .17s;
    }
    .laporan-summary-card:hover {
        box-shadow: 0 16px 48px #195b9a1a, 0 2px 8px #195b9a14;
        transform: translateY(-3px) scale(1.014);
    }
    .laporan-summary-card .card-body {
        padding: 1.5rem 1.3rem 1.1rem 1.3rem;
    }
    .laporan-summary-card h2 {
        font-size: 2.1rem;
        font-weight: 700;
        color: #283e51;
    }
    .laporan-summary-card h6 {
        font-size: 1.06rem;
        font-weight: 500;
        color: #7b8a9e !important;
        margin-bottom: 0;
    }
    .laporan-summary-card .icon-summary {
        font-size: 2rem;
        opacity: 0.8;
        margin-left: .5rem;
        color: #195b9a;
        background: #e8f2fb;
        padding: .7rem;
        border-radius: 50%;
        box-shadow: 0 2px 10px #195b9a1a;
    }
    .laporan-export-btn {
        min-width: 160px;
        font-weight: 600;
        font-size: 1.01rem;
        border-radius: 1.4rem;
        padding: .65rem 1.4rem;
        box-shadow: 0 2px 8px #36b37e15;
        margin-bottom: .4rem;
        margin-right: .5rem;
    }
    .laporan-export-btn i {
        margin-right: .5rem;
        font-size: 1.2em;
    }
    .table-responsive {
        border-radius: 1.1rem !important;
        overflow: hidden;
    }
    .table thead th {
        background: linear-gradient(90deg, #f8fafc 60%, #e4f0f7 100%) !important;
        color: #195b9a !important;
        font-weight: 700;
        font-size: 1.08rem;
        vertical-align: middle;
    }
    .table-bordered td, .table-bordered th {
        border: 1.5px solid #e7e7e7;
    }
    .table tbody tr {
        vertical-align: middle;
        font-size: 1.03rem;
        background: #fff;
        transition: background .14s;
    }
    .table tbody tr:hover {
        background: #e8f2fb;
    }
    .btn-info {
        background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
        color: #fff;
        border: none;
        font-weight: 600;
        box-shadow: 0 2px 8px #185a9d22;
        transition: background .15s;
    }
    .btn-info:hover {
        background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
        color: #fff;
    }
    .alert-warning {
        font-size: 1.07rem;
        border-radius: 1rem;
        font-weight: 500;
    }
    @media (max-width: 900px) {
        .card-group { flex-direction: column; gap: 1rem; }
        .laporan-summary-card { min-width: 0; }
        .laporan-heading { font-size: 1.3rem; }
        .table { font-size: .98rem; }
    }
</style>

<div class="container mt-5">
    <h1 class="laporan-heading">
        <span>📊</span> Laporan Penjualan per Kasir
    </h1>

    <div class="card-group mb-4 d-flex">
        <div class="card laporan-summary-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-1">{{ $reports->sum('total_order') }}</h2>
                        <h6>Total Order Bulan Ini</h6>
                    </div>
                    <div class="icon-summary ms-auto">
                        <i data-feather="shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card laporan-summary-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-1">
                            Rp {{ number_format($reports->sum('total_pendapatan'), 0, ',', '.') }}
                        </h2>
                        <h6>Total Pendapatan Bulan Ini</h6>
                    </div>
                    <div class="icon-summary ms-auto">
                        <i data-feather="dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card laporan-summary-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-1">
                            Rp {{ number_format($reports->sum('total_komisi_kasir'), 0, ',', '.') }}
                        </h2>
                        <h6>Total Komisi Kasir (20%)</h6>
                    </div>
                    <div class="icon-summary ms-auto">
                        <i data-feather="user-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card laporan-summary-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h2 class="mb-1">
                            Rp {{ number_format($reports->sum('total_keuntungan_bersih'), 0, ',', '.') }}
                        </h2>
                        <h6>Keuntungan Bersih (80%)</h6>
                    </div>
                    <div class="icon-summary ms-auto">
                        <i data-feather="trending-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $selectedMonth = request('bulan_tahun') ?? \Carbon\Carbon::now()->format('Y-m');
    @endphp

    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="{{ route('reports.exportExcel') }}?bulan_tahun={{ $selectedMonth }}"
            class="btn btn-success laporan-export-btn">
            <i data-feather="file-text"></i> Export Excel
        </a>
        <a href="{{ route('reports.export-word', ['bulan_tahun' => $selectedMonth]) }}"
            class="btn btn-primary laporan-export-btn">
            <i data-feather="file"></i> Export Word
        </a>
    </div>

    <form method="GET" action="{{ route('reports.index') }}" class="row row-cols-lg-auto g-3 align-items-end mb-4">
        <div class="col">
            <label for="bulan_tahun" class="form-label fw-semibold">Bulan:</label>
            <input type="month" id="bulan_tahun" name="bulan_tahun" class="form-control shadow-sm rounded-lg"
                value="{{ $bulanTahun ?? \Carbon\Carbon::now()->format('Y-m') }}">
        </div>
        <div class="col">
            <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                Tampilkan
            </button>
        </div>
    </form>

    @if($reports->isEmpty())
        <div class="alert alert-warning shadow-sm rounded-lg">
            Tidak ada data laporan untuk bulan ini.
        </div>
    @else
        <div class="table-responsive shadow-sm rounded-lg">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kasir</th>
                        <th>Bulan</th>
                        <th>Total Order</th>
                        <th>Total Pendapatan</th>
                        <th>Komisi Kasir (20%)</th>
                        <th>Keuntungan Bersih (80%)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->kasir_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->bulan_tahun . '-01')->format('F Y') }}</td>
                            <td>{{ $report->total_order }}</td>
                            <td>Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($report->total_komisi_kasir, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($report->total_keuntungan_bersih, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('reports.show', ['kasir_id' => $report->kasir_id, 'bulan_tahun' => $report->bulan_tahun]) }}"
                                    class="btn btn-sm btn-info rounded-pill shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.feather) feather.replace();
    });
</script>
@endsection