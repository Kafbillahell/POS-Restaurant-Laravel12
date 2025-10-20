@if($reports->isNotEmpty() || $reports->isEmpty())
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
                @if($reports->isEmpty())
                    <tr>
                        {{-- Gunakan colspan 8 (sesuai jumlah kolom header) --}}
                        <td colspan="8" class="p-0"> {{-- Tambahkan p-0 di sini --}}
                            {{-- Menggunakan bg-white/alert-light agar warna tidak nabrak --}}
                            <div class="alert alert-light m-0 border-0 rounded-0 py-3 d-block text-muted fw-semibold">
                                
                                <i data-feather="alert-triangle" class="mr-2"></i> 
                                Tidak ada data laporan untuk periode ini.
                            </div>
                        </td>
                    </tr>
                @else
                    {{-- Loop data laporan jika ada --}}
                    @foreach($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->kasir_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->bulan_tahun . '-01')->translatedFormat('F Y') }}</td>
                            <td>{{ number_format($report->total_order, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($report->total_komisi_kasir, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($report->total_keuntungan_bersih, 0, ',', '.') }}</td>
                            <td>
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
@endif