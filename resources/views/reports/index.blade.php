@extends('dashboard.home')

@section('title', 'Laporan Penjualan')

@section('content')

<style>
    /* Menggunakan variabel CSS untuk konsistensi monochrome */
    :root {
        --black: #1a1a1a;
        --gray-dark: #4a4a4a;
        --gray-medium: #888;
        --gray-light: #e5e5e5;
        --radius-sm: 0.35rem;
        --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        --color-profit: #1e7e34; 
        --color-loss: #dc3545;
    }

    body {
        color: var(--black);
        font-family: 'Poppins', sans-serif;
    }

    /* Page Header & Filter Placement */
    .page-header-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-bottom: 1rem;
        margin-bottom: 2rem; 
        border-bottom: 1px solid var(--gray-light);
    }
    .page-title {
        font-weight: 700;
        color: var(--black);
        letter-spacing: -0.5px;
        font-size: 2rem;
    }

    /* Statistic Boxes */
    .stat-box {
        background: #fff;
        border: 1px solid var(--gray-light);
        padding: 1.5rem;
        border-radius: var(--radius-sm);
        transition: all 0.2s ease;
        height: 100%;
        box-shadow: var(--shadow-subtle);
    }
    .stat-box:hover {
        border-color: var(--gray-medium);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--black);
        margin-bottom: 0.2rem;
        line-height: 1.1;
    }
    .stat-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-dark);
        margin-top: 5px;
        font-weight: 500;
    }

    /* Keuntungan dan Kerugian */
    .stat-value.profit {
        color: var(--color-profit);
    }
    .stat-value.loss {
        color: var(--color-loss);
    }

    /* Form Filter Styles */
    .form-control-mono {
        border: 1px solid var(--gray-light);
        border-radius: var(--radius-sm);
        padding: 0.5rem 1rem;
        color: var(--black);
        transition: border-color 0.2s;
        box-shadow: none;
    }
    .form-control-mono:focus {
        border-color: var(--gray-medium);
        outline: none;
    }
    .form-label-mono {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--gray-dark);
        margin-bottom: 0.3rem;
    }

    /* Buttons */
    .btn-monochrome {
        background-color: var(--black);
        color: #fff;
        border: 1px solid var(--black);
        border-radius: var(--radius-sm);
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-monochrome:hover {
        background-color: var(--gray-dark);
        border-color: var(--gray-dark);
    }
    
    .btn-outline-mono {
        background-color: transparent;
        color: var(--black);
        border: 1px solid var(--gray-medium);
        border-radius: var(--radius-sm);
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-outline-mono:hover {
        background-color: var(--black);
        color: #fff;
        border-color: var(--black);
    }
</style>

<div class="container pt-3 pb-5">
    
    @php
        use Carbon\Carbon;
        $selectedStartDate = request('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $selectedEndDate = request('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Mengambil data ringkasan dari Controller
        $totalOrderAll = $totalSummary['total_order_all'] ?? 0;
        $totalPemasukanAll = $totalSummary['total_pemasukan_all'] ?? 0;
        $totalPengeluaranAll = $totalSummary['total_pengeluaran_all'] ?? 0;
        
        // Perhitungan Keuntungan
        $totalKeuntunganAll = $totalPemasukanAll - $totalPengeluaranAll;
        
        $keuntunganClass = $totalKeuntunganAll >= 0 ? 'profit' : 'loss';
    @endphp

    {{-- HEADER WITH INLINE FILTER --}}
    <div class="page-header-container">
        <h1 class="page-title">
            Laporan Penjualan
            <span class="text-muted" style="font-size: 0.6em; font-weight: 500;">(Per Kasir)</span>
        </h1>

        {{-- Form Filter Diposisikan di Kanan Header --}}
        <form id="reportFilterForm" method="GET" action="{{ route('reports.index') }}" class="d-flex gap-3">
            <div>
                <label for="start_date" class="form-label-mono d-block">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" class="form-control form-control-mono"
                    value="{{ $selectedStartDate }}" style="width: 150px;">
            </div>
            <div>
                <label for="end_date" class="form-label-mono d-block">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" class="form-control form-control-mono"
                    value="{{ $selectedEndDate }}" style="width: 150px;">
            </div>
            <div>
                <button type="submit" class="btn-monochrome" style="margin-top: 1.5rem;">
                    <i data-feather="search" style="width: 16px; height: 16px;"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- STATISTIK GRID --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5" id="summaryCardsContainer">
        {{-- 1. Total Transaksi --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value" id="totalOrderValue">
                    {{ number_format($totalOrderAll, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- 2. Total Pemasukan --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Total Pemasukan</div>
                <div class="stat-value" id="totalPemasukanValue">
                    Rp {{ number_format($totalPemasukanAll, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- 3. Total Pengeluaran --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Total Pengeluaran</div>
                <div class="stat-value loss" id="totalPengeluaranValue">
                    Rp {{ number_format($totalPengeluaranAll, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- 4. Keuntungan (Pemasukan - Pengeluaran) --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Keuntungan</div>
                <div class="stat-value {{ $keuntunganClass }}" id="totalKeuntunganValue">
                    Rp {{ number_format($totalKeuntunganAll, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- EXPORT BUTTONS --}}
    <div class="d-flex gap-2 justify-content-start mb-4">
        <a href="{{ route('reports.exportExcel') }}?start_date={{ $selectedStartDate }}&end_date={{ $selectedEndDate }}"
            class="btn-outline-mono" id="exportExcelBtn">
            <i data-feather="grid"></i> Export Excel
        </a>
        <a href="{{ route('reports.export-word', ['start_date' => $selectedStartDate, 'end_date' => $selectedEndDate]) }}"
            class="btn-outline-mono" id="exportWordBtn">
            <i data-feather="file-text"></i> Export Word
        </a>
    </div>

    {{-- TABLE CONTAINER --}}
    <div id="reportTableContainer">
        @include('partials.report_table', ['reports' => $reports])
    </div>
</div>

{{-- SCRIPT AJAX --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.feather) feather.replace();
        
        const form = document.getElementById('reportFilterForm');
        
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 

                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                
                const formData = new URLSearchParams(new FormData(form)).toString() + '&ajax=1';
                const url = form.getAttribute('action');

                // TAMPILAN LOADING
                document.getElementById('reportTableContainer').innerHTML = `
                    <div class="text-center py-5 bg-white shadow-sm rounded-lg" style="border: 1px solid var(--gray-light);">
                        <div class="spinner-border text-dark" role="status" style="width: 3rem; height: 3rem; border-width: 3px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted small text-uppercase" style="letter-spacing: 2px;">Memuat Data...</p>
                    </div>
                `;
                
                fetch(url + '?' + formData, { method: 'GET' })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(response => {
                        const summary = response.summary;
                        
                        // Data Pemasukan dan Keuntungan
                        const totalPemasukan = Number(summary.total_pemasukan_all) || 0;
                        const totalPengeluaran = Number(summary.total_pengeluaran_all) || 0; 
                        const totalKeuntungan = totalPemasukan - totalPengeluaran;

                        // Fungsi format Rupiah (sederhana)
                        function formatRupiah(number) {
                            number = Number(number) || 0; 
                            const formatted = Math.abs(number).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                            return (number < 0 ? '-Rp ' : 'Rp ') + formatted;
                        }

                        // 1. Update Statistik
                        document.getElementById('totalOrderValue').textContent = Number(summary.total_order_all).toLocaleString('id-ID');
                        document.getElementById('totalPemasukanValue').textContent = formatRupiah(totalPemasukan);
                        document.getElementById('totalPengeluaranValue').textContent = formatRupiah(totalPengeluaran);
                        
                        // Update Keuntungan
                        const keuntunganElement = document.getElementById('totalKeuntunganValue');
                        keuntunganElement.textContent = formatRupiah(totalKeuntungan);
                        keuntunganElement.classList.remove('profit', 'loss');
                        keuntunganElement.classList.add(totalKeuntungan >= 0 ? 'profit' : 'loss');

                        // 2. Update Tabel
                        document.getElementById('reportTableContainer').innerHTML = response.table_html;

                        // 3. Update Link Export
                        const exportParams = '?start_date=' + startDate + '&end_date=' + endDate;
                        document.getElementById('exportExcelBtn').href = '{{ route('reports.exportExcel') }}' + exportParams;
                        document.getElementById('exportWordBtn').href = '{{ route('reports.export-word') }}' + exportParams;

                        // 4. Ganti icon untuk tabel baru
                        if (window.feather) feather.replace();
                    })
                    .catch(error => {
                        console.error("Fetch Gagal:", error);
                        document.getElementById('reportTableContainer').innerHTML = `
                            <div class="alert border-danger rounded-0 text-center text-danger p-4" style="border: 1px solid var(--gray-medium);">
                                <h5 class="fw-bold">Gagal Memuat Data</h5>
                                <p class="mb-0">Terjadi kesalahan saat mengambil data laporan. Pastikan koneksi dan parameter filter benar.</p>
                            </div>`;
                    });
            });
        }
    });
</script>
@endsection