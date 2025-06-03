@extends('dashboard.home')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container mt-6">
        <h1 class="text-xl font-semibold mb-4">📊 Laporan Penjualan per Kasir</h1>

        <div class="card-group mb-4">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $reports->sum('total_order') }}</h2>
                                {{-- Kalau mau tampilkan persen kenaikan bisa tambah di sini --}}
                                {{-- <span
                                    class="badge bg-primary font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none">+X%</span>
                                --}}
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Order Bulan Ini</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="shopping-cart"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium">
                                Rp {{ number_format($reports->sum('total_pendapatan'), 0, ',', '.') }}
                            </h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Pendapatan Bulan Ini
                            </h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <div class="d-inline-flex align-items-center">
                                <h2 class="text-dark mb-1 font-weight-medium">
                                    Rp {{ number_format($reports->sum('total_komisi_kasir'), 0, ',', '.') }}
                                </h2>
                                {{-- Badge persen bisa ditambahkan --}}
                            </div>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Komisi Kasir (20%)</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-check"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <div>
                            <h2 class="text-dark mb-1 font-weight-medium">
                                Rp {{ number_format($reports->sum('total_keuntungan_bersih'), 0, ',', '.') }}
                            </h2>
                            <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Keuntungan Bersih (80%)</h6>
                        </div>
                        <div class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="trending-up"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Export --}}
        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('reports.exportExcel') }}?bulan_tahun={{ $bulanTahun ?? \Carbon\Carbon::now()->format('Y-m') }}"
                class="btn btn-success rounded-pill shadow-sm">
                <i data-feather="file-text"></i> Export Excel
            </a>

            <a href="{{ route('reports.export-word', ['bulan_tahun' => $bulanTahun ?? \Carbon\Carbon::now()->format('Y-m')]) }}"
                class="btn btn-primary rounded-pill shadow-sm">
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
@endsection