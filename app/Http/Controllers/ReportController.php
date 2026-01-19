<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay();

        // 1. Logika untuk Laporan Per Kasir/Bulan (Ambil Total Pemasukan dari Order)
        $reports = DB::table('users')
            ->select(
                'users.id as kasir_id',
                'users.name as kasir_name',
                DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as bulan_tahun"),
                DB::raw('COUNT(DISTINCT orders.id) as total_order'),
                // total_pendapatan adalah hasil penjualan per kasir
                DB::raw('COALESCE(SUM(detail_orders.subtotal), 0) as total_pendapatan') 
            )
            ->join('orders', function ($join) use ($startDate, $endDate) {
                $join->on('orders.user_id', '=', 'users.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->join('detail_orders', 'detail_orders.order_id', '=', 'orders.id')
            ->where('users.role', 'kasir')
            ->groupBy('users.id', 'users.name', DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m')"))
            ->orderBy('users.name')
            ->orderBy('bulan_tahun')
            ->get();
            
        // 2. Perhitungan Total Global (Ringkasan Keuangan Utama)
        $totalPemasukanAll = $reports->sum('total_pendapatan');

        // Mengambil Total Pengeluaran
        $totalPengeluaranAll = Pengeluaran::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                        ->sum('jumlah');

        // Menghitung Keuntungan Global (Pemasukan - Pengeluaran)
        $totalKeuntunganAll = $totalPemasukanAll - $totalPengeluaranAll;
        
        $totalSummary = [
            'total_order_all' => $reports->sum('total_order'),
            'total_pemasukan_all' => $totalPemasukanAll,
            'total_pengeluaran_all' => $totalPengeluaranAll,
            'total_keuntungan_all' => $totalKeuntunganAll,
        ];

        // === PERBAIKAN AJAX ===
        if ($request->ajax() || $request->has('ajax')) {
            $tableHtml = view('partials.report_table', [
                'reports' => $reports,
            ])->render(); 
            
            return response()->json([
                'summary' => $totalSummary,
                'table_html' => $tableHtml,
            ]);
        }
        // =======================

        return view('reports.index', [
            'reports' => $reports,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'totalSummary' => $totalSummary,
        ]);
    }

    public function show($kasir_id, $bulan_tahun)
    {
        // Kode ini tidak diubah karena hanya menampilkan rincian order
        [$tahun, $bulan] = explode('-', $bulan_tahun);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

        $user = User::findOrFail($kasir_id);

        $orders = Order::with(['detailOrders.menu'])
            ->where('user_id', $kasir_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        return view('reports.show', [
            'user' => $user,
            'orders' => $orders,
            'bulanTahun' => $bulan_tahun,
            'tanggal' => $startDate->format('Y-m-d'),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay();
        
        // 1. Ambil Laporan Per Kasir
        $reports = DB::table('users')
            ->select(
                'users.name as kasir_name',
                DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as bulan_tahun"),
                DB::raw('COUNT(DISTINCT orders.id) as total_order'),
                DB::raw('COALESCE(SUM(detail_orders.subtotal), 0) as total_pendapatan')
            )
            ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
                $join->on('orders.user_id', '=', 'users.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->leftJoin('detail_orders', 'detail_orders.order_id', '=', 'orders.id')
            ->where('users.role', 'kasir')
            ->groupBy('users.id', 'users.name', DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m')"))
            ->orderBy('users.name')
            ->get();
            
        $totalPemasukanAll = $reports->sum('total_pendapatan');
        
        // 2. Ambil Total Pengeluaran
        $totalPengeluaranAll = Pengeluaran::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                        ->sum('jumlah');

        $totalKeuntunganAll = $totalPemasukanAll - $totalPengeluaranAll;


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $title = 'Laporan Penjualan & Keuangan Periode ' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:E1'); 
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // RINGKASAN GLOBAL
        $sheet->setCellValue('B3', 'Total Transaksi:');
        $sheet->setCellValue('C3', $reports->sum('total_order'))->getStyle('C3')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->setCellValue('B4', 'Total Pemasukan:');
        $sheet->setCellValue('C4', $totalPemasukanAll)->getStyle('C4')->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->setCellValue('B5', 'Total Pengeluaran:');
        $sheet->setCellValue('C5', $totalPengeluaranAll)->getStyle('C5')->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->setCellValue('B6', 'KEUNTUNGAN:');
        $sheet->setCellValue('C6', $totalKeuntunganAll)->getStyle('C6')->getFont()->setBold(true);
        $sheet->getStyle('C6')->getNumberFormat()->setFormatCode('"Rp "#,##0');

        $startRow = 8; 
        
        // Header Tabel Kasir (Kolom HANYA 5)
        $headers = ['No', 'Kasir', 'Bulan', 'Total Transaksi', 'Total Pemasukan'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $startRow, $header);
            $col++;
        }

        $headerRange = 'A' . $startRow . ':E' . $startRow;
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFB0C4DE');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $row = $startRow + 1;
        foreach ($reports as $index => $report) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $report->kasir_name);
            $sheet->setCellValue('C' . $row, Carbon::parse($report->bulan_tahun . '-01')->format('F Y'));
            $sheet->setCellValue('D' . $row, $report->total_order);
            $sheet->setCellValue('E' . $row, $report->total_pendapatan);
            $row++;
        }

        $dataRange = "A" . ($startRow + 1) . ":E" . ($row - 1);
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Styling dan format Rupiah
        foreach (range($startRow + 1, $row - 1) as $r) {
            $sheet->getStyle("E{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle("E{$r}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(25);

        $fileName = 'laporan_penjualan_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportWord(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')))->endOfDay();
        
        // 1. Ambil Laporan Per Kasir
        $reports = DB::table('users')
            ->select(
                'users.name as kasir_name',
                DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as bulan_tahun"),
                DB::raw('COUNT(DISTINCT orders.id) as total_order'),
                DB::raw('COALESCE(SUM(detail_orders.subtotal), 0) as total_pendapatan')
            )
            ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
                $join->on('orders.user_id', '=', 'users.id')
                    ->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->leftJoin('detail_orders', 'detail_orders.order_id', '=', 'orders.id')
            ->where('users.role', 'kasir')
            ->groupBy('users.id', 'users.name', DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m')"))
            ->orderBy('users.name')
            ->get();

        $totalPemasukanAll = $reports->sum('total_pendapatan');
        
        // 2. Ambil Total Pengeluaran
        $totalPengeluaranAll = Pengeluaran::whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                                        ->sum('jumlah');

        $totalKeuntunganAll = $totalPemasukanAll - $totalPengeluaranAll;


        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle("Laporan Penjualan & Keuangan Periode " . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'), 1);

        // Ringkasan Global
        $section->addText("Ringkasan Keuangan:", ['bold' => true]);
        $section->addText("Total Transaksi: " . number_format($reports->sum('total_order'), 0, ',', '.'));
        $section->addText("Total Pemasukan: Rp " . number_format($totalPemasukanAll, 0, ',', '.'));
        $section->addText("Total Pengeluaran: Rp " . number_format($totalPengeluaranAll, 0, ',', '.'));
        $section->addTextBreak();
        
        $keuntunganText = ($totalKeuntunganAll >= 0 ? "KEUNTUNGAN: " : "RUGI: ") . "Rp " . number_format(abs($totalKeuntunganAll), 0, ',', '.');
        $section->addText($keuntunganText, ['bold' => true, 'size' => 12]);
        $section->addTextBreak();

        // Tabel Laporan Kasir
        $headerStyle = [
            'bgColor' => 'B0C4DE',
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
        ];

        $headerFontStyle = ['bold' => true, 'color' => '000000'];
        $headerParagraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $cellStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80];

        $alignCenter = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $alignLeft = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START];
        $alignRight = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END];

        $table = $section->addTable();

        $table->addRow();
        // Header Tabel Kasir (Kolom HANYA 5)
        $headers = ['No', 'Kasir', 'Bulan', 'Total Transaksi', 'Total Pemasukan'];

        foreach ($headers as $header) {
            $cell = $table->addCell(2500, $headerStyle);
            $textrun = $cell->addTextRun($headerParagraphStyle);
            $textrun->addText($header, $headerFontStyle);
        }

        // Isi tabel
        foreach ($reports as $index => $report) {
            $table->addRow();

            $cell = $table->addCell(2500, $cellStyle);
            $cell->addText($index + 1, null, $alignCenter);

            $cell = $table->addCell(2500, $cellStyle);
            $cell->addText($report->kasir_name, null, $alignLeft);

            $cell = $table->addCell(2500, $cellStyle);
            $cell->addText(Carbon::parse($report->bulan_tahun . '-01')->format('F Y'), null, $alignLeft);

            $cell = $table->addCell(2500, $cellStyle);
            $cell->addText($report->total_order, null, $alignCenter);

            $cell = $table->addCell(2500, $cellStyle);
            $cell->addText('Rp ' . number_format($report->total_pendapatan, 0, ',', '.'), null, $alignRight);
        }

        $fileName = 'laporan_penjualan_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

}