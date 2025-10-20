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
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.10), 0 2px 8px rgba(44, 62, 80, 0.03) !important;
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

        .table-bordered td,
        .table-bordered th {
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
            .card-group {
                flex-direction: column;
                gap: 1rem;
            }

            .laporan-summary-card {
                min-width: 0;
            }

            .laporan-heading {
                font-size: 1.3rem;
            }

            .table {
                font-size: .98rem;
            }
        }
    </style>

    <div class="container mt-5">
        <h1 class="laporan-heading">
            <span>📊</span> Laporan Penjualan per Kasir
        </h1>

        <div class="card-group mb-4 d-flex" id="summaryCardsContainer">
    {{-- Kartu 1: Total Order --}}
    <div class="card laporan-summary-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    {{-- Ganti $reports->sum() dengan $totalSummary['total_order_all'] --}}
                    <h2 class="mb-1" id="totalOrderValue">{{ number_format($totalSummary['total_order_all'], 0, ',', '.') }}</h2>
                    <h6>Total Order Bulan Ini</h6>
                </div>
                ...
            </div>
        </div>
    </div>
    {{-- Kartu 2: Total Pendapatan --}}
    <div class="card laporan-summary-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    {{-- Ganti $reports->sum() dengan $totalSummary['total_pendapatan_all'] --}}
                    <h2 class="mb-1" id="totalPendapatanValue">
                        Rp {{ number_format($totalSummary['total_pendapatan_all'], 0, ',', '.') }}
                    </h2>
                    <h6>Total Pendapatan Bulan Ini</h6>
                </div>
                ...
            </div>
        </div>
    </div>
    {{-- Lakukan hal yang sama untuk Komisi dan Keuntungan Bersih --}}
    <div class="card laporan-summary-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h2 class="mb-1" id="totalKomisiValue">
                        Rp {{ number_format($totalSummary['total_komisi_kasir_all'] ?? 0, 0, ',', '.') }}
                    </h2>
                    <h6>Total Komisi Kasir (20%)</h6>
                </div>
                ...
            </div>
        </div>
    </div>
    <div class="card laporan-summary-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h2 class="mb-1" id="totalKeuntunganBersihValue">
                        Rp {{ number_format($totalSummary['total_keuntungan_bersih_all'] ?? 0, 0, ',', '.') }}
                    </h2>
                    <h6>Keuntungan Bersih (80%)</h6>
                </div>
                ...
            </div>
        </div>
    </div>
</div>

        @php
            $selectedStartDate = request('start_date') ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
            $selectedEndDate = request('end_date') ?? \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d');
        @endphp

        <div class="mb-3 d-flex flex-wrap gap-2">
            {{-- Perlu diperhatikan bahwa link export harus diperbarui juga --}}
            <a href="{{ route('reports.exportExcel') }}?start_date={{ $selectedStartDate }}&end_date={{ $selectedEndDate }}"
                class="btn btn-success laporan-export-btn" id="exportExcelBtn">
                <i data-feather="file-text"></i> Export Excel
            </a>
            <a href="{{ route('reports.export-word', ['start_date' => $selectedStartDate, 'end_date' => $selectedEndDate]) }}"
                class="btn btn-primary laporan-export-btn" id="exportWordBtn">
                <i data-feather="file"></i> Export Word
            </a>
        </div>

        <form id="reportFilterForm" method="GET" action="{{ route('reports.index') }}"
            class="row row-cols-lg-auto g-3 align-items-end mb-4">
            <div class="col">
                <label for="start_date" class="form-label fw-semibold">Tanggal Mulai:</label>
                <input type="date" id="start_date" name="start_date" class="form-control shadow-sm rounded-lg"
                    value="{{ request('start_date') ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
            </div>

            <div class="col">
                <label for="end_date" class="form-label fw-semibold">Tanggal Akhir:</label>
                <input type="date" id="end_date" name="end_date" class="form-control shadow-sm rounded-lg"
                    value="{{ request('end_date') ?? \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}">
            </div>

            <div class="col">
                <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                    Tampilkan
                </button>
            </div>
        </form>

        <div id="reportTableContainer">
            @include('partials.report_table', ['reports' => $reports])
        </div>
    </div>

    <script>
    // Skrip JavaScript murni (Vanilla JS)
    document.addEventListener("DOMContentLoaded", function () {
        if (window.feather) feather.replace();
        
        const form = document.getElementById('reportFilterForm');
        
        if (form) {
            form.addEventListener('submit', function (e) {
                // !!! KUNCI: Mencegah submit default form yang menyebabkan refresh
                e.preventDefault(); 

                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                
                // Siapkan data untuk dikirim
                const formData = new URLSearchParams(new FormData(form)).toString() + '&ajax=1';
                const url = form.getAttribute('action');

                // 1. (DIHAPUS): TIDAK MENAMPILKAN LOADING PADA RINGKASAN WIDGET
                //    Ringkasan akan diperbarui langsung di bagian 'then' (success).
                
                // 2. Tampilkan loading PADA TABEL SAJA
                document.getElementById('reportTableContainer').innerHTML = `
                    <div class="table-responsive bg-white rounded-lg p-5 border shadow-sm text-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data laporan baru...</p>
                    </div>
                `;
                
                // Lakukan Fetch API
                fetch(url + '?' + formData, { method: 'GET' })
                    .then(response => {
                        // Cek status respons sebelum mengolah JSON
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(response => {
                        const summary = response.summary;
                        
                        // Fungsi format Rupiah (sederhana)
                        function formatRupiah(number) {
                            number = Number(number) || 0; 
                            return 'Rp ' + number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                        }

                        // 3. Perbarui Nilai di Kartu Ringkasan (Ringkasan diperbarui INSTAN)
                        document.getElementById('totalOrderValue').textContent = Number(summary.total_order_all).toLocaleString('id-ID');
                        document.getElementById('totalPendapatanValue').textContent = formatRupiah(summary.total_pendapatan_all);
                        document.getElementById('totalKomisiValue').textContent = formatRupiah(summary.total_komisi_kasir_all);
                        document.getElementById('totalKeuntunganBersihValue').textContent = formatRupiah(summary.total_keuntungan_bersih_all);
                        
                        // 4. Perbarui Konten Tabel Laporan
                        document.getElementById('reportTableContainer').innerHTML = response.table_html;

                        // 5. Perbarui Link Export
                        const exportParams = '?start_date=' + startDate + '&end_date=' + endDate;
                        
                        const exportExcelBaseUrl = '{{ route('reports.exportExcel') }}';
                        const exportWordBaseUrl = '{{ route('reports.export-word') }}';
                        
                        document.getElementById('exportExcelBtn').href = exportExcelBaseUrl + exportParams;
                        document.getElementById('exportWordBtn').href = exportWordBaseUrl + exportParams;

                        // 6. Ganti icon (jika ada) - DIBIARKAN AGAR ICON FEATHER TETAP MUNCUL DI TABEL BARU
                        if (window.feather) feather.replace();
                    })
                    .catch(error => {
                        console.error("Fetch Gagal:", error);
                        // Tampilkan error pada tabel, bukan pada widget
                        document.getElementById('reportTableContainer').innerHTML = '<div class="alert alert-danger p-3">Terjadi kesalahan saat mengambil data: ' + error.message + '</div>';
                        // Biarkan widget ringkasan menampilkan nilai lama atau kosong jika ada error
                    });
            });
        }
    });
</script>
@endsection