<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Rincian Laporan Penjualan Per Kasir</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Kasir</th>
                        <th style="width: 15%;">Bulan</th>
                        <th class="text-center" style="width: 15%;">Total Transaksi</th>
                        <th class="text-end" style="width: 20%;">Total Pemasukan</th>
                        <th class="text-center" style="width: 20%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($reports->isEmpty())
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="alert alert-light m-0 border-0 rounded-0 py-3 d-block text-muted fw-semibold">
                                    <i data-feather="alert-triangle" class="mr-2"></i> 
                                    Tidak ada data laporan untuk periode ini.
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($reports as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $report->kasir_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->bulan_tahun . '-01')->translatedFormat('F Y') }}</td>
                                <td class="text-center">{{ number_format($report->total_order, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('reports.show', ['kasir_id' => $report->kasir_id, 'bulan_tahun' => $report->bulan_tahun]) }}"
                                       class="btn btn-sm btn-info rounded-pill px-3">
                                       Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>