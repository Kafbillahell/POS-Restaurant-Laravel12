<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kasir</th>
            <th>Bulan</th>
            <th>Total Order</th>
            <th>Total Pendapatan</th>
            <th>Komisi Kasir (20%)</th>
            <th>Keuntungan Bersih (80%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $index => $report)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $report->kasir_name }}</td>
                <td>{{ \Carbon\Carbon::parse($report->bulan_tahun . '-01')->format('F Y') }}</td>
                <td>{{ $report->total_order }}</td>
                <td>Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($report->total_komisi_kasir, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($report->total_keuntungan_bersih, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
