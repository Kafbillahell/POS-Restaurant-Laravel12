@extends('dashboard.home')

@section('title', 'Laporan Penjualan')

@section('content')

{{-- STYLE KHUSUS MONOCHROME MODERN (SOFTENED) --}}
<style>
    :root {
        --black: #1a1a1a;
        --gray-dark: #4a4a4a;
        --gray-medium: #888;
        --gray-light: #e5e5e5;
        --radius-sm: 0.35rem; /* Sudut membulat halus */
        --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }

    body {
        color: var(--black);
        font-family: 'Poppins', sans-serif;
    }

    /* --- Page Header & Filter Placement --- */
    .page-header-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-bottom: 1rem;
        /* Diubah dari 1.5rem kembali ke 2rem atau sedikit lebih besar untuk memberi jarak ke widget */
        margin-bottom: 2rem; 
        border-bottom: 1px solid var(--gray-light);
    }
    .page-title {
        font-weight: 700;
        color: var(--black);
        letter-spacing: -0.5px;
        font-size: 2rem;
    }

    /* --- Statistic Boxes (Softened) --- */
    .stat-box {
        background: #fff;
        border: 1px solid var(--gray-light);
        padding: 1.5rem;
        border-radius: var(--radius-sm); /* Sudut membulat */
        transition: all 0.2s ease;
        height: 100%;
        box-shadow: var(--shadow-subtle); /* Shadow halus */
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

    /* --- Form Filter Styles --- */
    .form-control-mono {
        border: 1px solid var(--gray-light);
        border-radius: var(--radius-sm);
        padding: 0.5rem 1rem;
        color: var(--black);
        transition: border-color 0.2s;
        box-shadow: none; /* Hilangkan shadow default Bootstrap */
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

    /* --- Buttons --- */
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
        border: 1px solid var(--gray-medium); /* Garis outline lebih lembut */
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

{{-- Perubahan: Mengubah 'pt-3 pb-4' menjadi 'pt-3 pb-5'. Padding atas tetap kecil (pt-3), padding bawah diperbesar untuk memberi jarak dari elemen dashboard di bawah. --}}
<div class="container pt-3 pb-5">
    
    @php
        $selectedStartDate = request('start_date') ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
        $selectedEndDate = request('end_date') ?? \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
    @endphp

    {{-- HEADER WITH INLINE FILTER --}}
    {{-- Margin bawah page-header-container dikembalikan ke 2rem di CSS untuk memberi jarak normal ke widget --}}
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

    {{-- STATISTIK GRID (Softened Stat Boxes) --}}
    {{-- Margin bawah diubah menjadi mb-5 untuk memberi jarak normal ke Export Buttons di bawahnya --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5" id="summaryCardsContainer">
        {{-- Total Order --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value" id="totalOrderValue">
                    {{ number_format($totalSummary['total_order_all'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Pendapatan Kotor</div>
                <div class="stat-value" id="totalPendapatanValue">
                    Rp {{ number_format($totalSummary['total_pendapatan_all'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Total Komisi --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Komisi Kasir (20%)</div>
                <div class="stat-value" id="totalKomisiValue">
                    Rp {{ number_format($totalSummary['total_komisi_kasir_all'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Keuntungan Bersih --}}
        <div class="col">
            <div class="stat-box">
                <div class="stat-label">Keuntungan Bersih</div>
                <div class="stat-value" id="totalKeuntunganBersihValue">
                    Rp {{ number_format($totalSummary['total_keuntungan_bersih_all'] ?? 0, 0, ',', '.') }}
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

{{-- SCRIPT --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.feather) feather.replace();
        
        const form = document.getElementById('reportFilterForm');
        
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 

                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                
                // PENTING: Menggunakan URLSearchParams untuk mendapatkan semua input form,
                // meskipun kita hanya menggunakan input tanggal di sini.
                const formData = new URLSearchParams(new FormData(form)).toString() + '&ajax=1';
                const url = form.getAttribute('action');

                // TAMPILAN LOADING MONOCHROME MODERN
                document.getElementById('reportTableContainer').innerHTML = `
                    <div class="text-center py-5 bg-white shadow-sm rounded-lg" style="border: 1px solid var(--gray-light);">
                        <div class="spinner-border text-dark" role="status" style="width: 3rem; height: 3rem; border-width: 3px;">
                            <span class="sr-only"></span>
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
                        
                        // Fungsi format Rupiah (sederhana)
                        function formatRupiah(number) {
                            number = Number(number) || 0; 
                            return 'Rp ' + number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        // 1. Update Statistik
                        document.getElementById('totalOrderValue').textContent = Number(summary.total_order_all).toLocaleString('id-ID');
                        document.getElementById('totalPendapatanValue').textContent = formatRupiah(summary.total_pendapatan_all);
                        document.getElementById('totalKomisiValue').textContent = formatRupiah(summary.total_komisi_kasir_all);
                        document.getElementById('totalKeuntunganBersihValue').textContent = formatRupiah(summary.total_keuntungan_bersih_all);
                        
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
                                <h5 class="fw-bold">🚨 Gagal Memuat Data</h5>
                                <p class="mb-0">Terjadi kesalahan saat mengambil data laporan. Pastikan koneksi dan parameter filter benar.</p>
                            </div>`;
                    });
            });
        }
    });
</script>
@endsection