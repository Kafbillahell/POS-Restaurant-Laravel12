<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
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
    $bulanTahun = $request->input('bulan_tahun', Carbon::now()->format('Y-m'));
    [$tahun, $bulan] = explode('-', $bulanTahun);

    $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
    $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

    $reports = DB::table('users')
        ->select(
            'users.id as kasir_id',
            'users.name as kasir_name',
            DB::raw("'$bulanTahun' as bulan_tahun"),
            DB::raw('COUNT(DISTINCT orders.id) as total_order'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal), 0) as total_pendapatan'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal) * 0.2, 0) as total_komisi_kasir'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal) * 0.8, 0) as total_keuntungan_bersih')
        )
        ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
            $join->on('orders.user_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate]);
        })
        ->leftJoin('detail_orders', 'detail_orders.order_id', '=', 'orders.id')
        ->where('users.role', 'kasir')
        ->groupBy('users.id', 'users.name')
        ->orderBy('users.name')
        ->get();

    return view('reports.index', compact('reports', 'bulanTahun'));
}


    public function show($id, Request $request)
    {
        $bulanTahun = $request->query('bulan_tahun', Carbon::now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $bulanTahun);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

        $user = User::findOrFail($id);

        $orders = Order::with(['detailOrders.menu'])
            ->where('user_id', $id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        return view('reports.show', [
            'user' => $user,
            'orders' => $orders,
            'tanggal' => $startDate->format('Y-m-d'),
        ]);
    }

    public function exportExcel(Request $request)
{
    $bulanTahun = $request->input('bulan_tahun', Carbon::now()->format('Y-m'));
    [$tahun, $bulan] = explode('-', $bulanTahun);

    $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
    $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

    $reports = DB::table('users')
        ->select(
            'users.name as kasir_name',
            DB::raw("'$bulanTahun' as bulan_tahun"),
            DB::raw('COUNT(DISTINCT orders.id) as total_order'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal), 0) as total_pendapatan'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal) * 0.2, 0) as total_komisi_kasir'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal) * 0.8, 0) as total_keuntungan_bersih')
        )
        ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
            $join->on('orders.user_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate]);
        })
        ->leftJoin('detail_orders', 'detail_orders.order_id', '=', 'orders.id')
        ->where('users.role', 'kasir')
        ->groupBy('users.id', 'users.name')
        ->orderBy('users.name')
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set judul laporan di baris 1 dan merge beberapa kolom supaya center
    $title = 'Laporan Penjualan Kasir Bulan ' . Carbon::parse($bulanTahun . '-01')->format('F Y');
    $sheet->setCellValue('A1', $title);
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Header tabel di baris 3
    $headers = ['No', 'Kasir', 'Bulan', 'Total Order', 'Total Pendapatan', 'Komisi Kasir (20%)', 'Keuntungan Bersih (80%)'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '3', $header);
        $col++;
    }

    // Style header: Bold, center alignment, background color, border
    $headerRange = 'A3:G3';
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFB0C4DE'); // Light steel blue bg
    $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // Isi data mulai baris 4
    $row = 4;
    foreach ($reports as $index => $report) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $report->kasir_name);
        $sheet->setCellValue('C' . $row, Carbon::parse($report->bulan_tahun . '-01')->format('F Y'));
        $sheet->setCellValue('D' . $row, $report->total_order);
        $sheet->setCellValue('E' . $row, $report->total_pendapatan);
        $sheet->setCellValue('F' . $row, $report->total_komisi_kasir);
        $sheet->setCellValue('G' . $row, $report->total_keuntungan_bersih);
        $row++;
    }

    // Style data rows: borders dan alignment
    $dataRange = "A4:G" . ($row - 1);
    $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    // Alignment kolom:
    // Kolom No, Total Order rata tengah
    $sheet->getStyle("A4:A" . ($row - 1))->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("D4:D" . ($row - 1))->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Kolom Kasir dan Bulan rata kiri
    $sheet->getStyle("B4:C" . ($row - 1))->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

    // Kolom nominal uang (E,F,G) rata kanan dan format rupiah
    foreach (range(4, $row - 1) as $r) {
        foreach (['E', 'F', 'G'] as $colNominal) {
            $sheet->getStyle("{$colNominal}{$r}")
                ->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
            $sheet->getStyle("{$colNominal}{$r}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }
    }

    // Sesuaikan lebar kolom agar pas isi
    $sheet->getColumnDimension('A')->setWidth(5);   // No
    $sheet->getColumnDimension('B')->setWidth(25);  // Kasir
    $sheet->getColumnDimension('C')->setWidth(20);  // Bulan
    $sheet->getColumnDimension('D')->setWidth(12);  // Total Order
    $sheet->getColumnDimension('E')->setWidth(20);  // Total Pendapatan
    $sheet->getColumnDimension('F')->setWidth(20);  // Komisi Kasir
    $sheet->getColumnDimension('G')->setWidth(20);  // Keuntungan Bersih

    $fileName = 'laporan_penjualan_' . $bulanTahun . '.xlsx';

    // Kirim header untuk download file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}


    public function exportWord(Request $request)
{
    $bulanTahun = $request->input('bulan_tahun', Carbon::now()->format('Y-m'));
    [$tahun, $bulan] = explode('-', $bulanTahun);

    $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
    $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

    $reports = DB::table('users')
        ->select(
            'users.name as kasir_name',
            DB::raw("'$bulanTahun' as bulan_tahun"),
            DB::raw('COUNT(DISTINCT orders.id) as total_order'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal), 0) as total_pendapatan'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal) * 0.2, 0) as total_komisi_kasir'),
            DB::raw('COALESCE(SUM(detail_orders.subtotal) * 0.8, 0) as total_keuntungan_bersih')
        )
        ->leftJoin('orders', function ($join) use ($startDate, $endDate) {
            $join->on('orders.user_id', '=', 'users.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate]);
        })
        ->leftJoin('detail_orders', 'detail_orders.order_id', '=', 'orders.id')
        ->where('users.role', 'kasir')
        ->groupBy('users.id', 'users.name')
        ->orderBy('users.name')
        ->get();

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    // Judul laporan
    $section->addTitle("Laporan Penjualan Kasir Bulan " . Carbon::parse($bulanTahun . '-01')->format('F Y'), 1);

    // Style array untuk header tabel
    $headerStyle = [
        'bgColor' => 'B0C4DE',  // Light Steel Blue
        'borderSize' => 6,
        'borderColor' => '999999',
        'cellMargin' => 80,
    ];

    // Style array untuk cell header text
    $headerFontStyle = ['bold' => true, 'color' => '000000'];

    // Style untuk teks rata tengah di header dan beberapa kolom
    $headerParagraphStyle = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];

    // Style array untuk cell isi tabel
    $cellStyle = [
        'borderSize' => 6,
        'borderColor' => '999999',
        'cellMargin' => 80,
    ];

    // Style untuk teks rata kiri dan kanan
    $alignCenter = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
    $alignLeft = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START];
    $alignRight = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END];

    // Buat tabel
    $table = $section->addTable();

    // Header tabel
    $table->addRow();
    $headers = ['No', 'Kasir', 'Bulan', 'Total Order', 'Total Pendapatan', 'Komisi Kasir (20%)', 'Keuntungan Bersih (80%)'];

    foreach ($headers as $header) {
        $cell = $table->addCell(3000, $headerStyle);
        $textrun = $cell->addTextRun($headerParagraphStyle);
        $textrun->addText($header, $headerFontStyle);
    }

    // Isi tabel
    foreach ($reports as $index => $report) {
        $table->addRow();

        // No (center)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText($index + 1, null, $alignCenter);

        // Kasir (left)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText($report->kasir_name, null, $alignLeft);

        // Bulan (left)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText(Carbon::parse($report->bulan_tahun . '-01')->format('F Y'), null, $alignLeft);

        // Total Order (center)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText($report->total_order, null, $alignCenter);

        // Total Pendapatan (right, format rupiah)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText('Rp ' . number_format($report->total_pendapatan, 0, ',', '.'), null, $alignRight);

        // Komisi Kasir (right, format rupiah)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText('Rp ' . number_format($report->total_komisi_kasir, 0, ',', '.'), null, $alignRight);

        // Keuntungan Bersih (right, format rupiah)
        $cell = $table->addCell(3000, $cellStyle);
        $cell->addText('Rp ' . number_format($report->total_keuntungan_bersih, 0, ',', '.'), null, $alignRight);
    }

    $fileName = 'laporan_penjualan_' . $bulanTahun . '.docx';

    // Kirim header untuk download file Word
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('php://output');
    exit;
}

}
