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

        // Judul laporan di baris 1
        $sheet->setCellValue('A1', 'Laporan Penjualan Kasir Bulan ' . Carbon::parse($bulanTahun . '-01')->format('F Y'));

        // Header tabel di baris 3
        $headers = ['No', 'Kasir', 'Bulan', 'Total Order', 'Total Pendapatan', 'Komisi Kasir (20%)', 'Keuntungan Bersih (80%)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }

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

        // Formatting: buat kolom E,F,G jadi format rupiah dengan pemisah ribuan
        foreach (range(4, $row - 1) as $r) {
            $sheet->getStyle("E{$r}:G{$r}")
                ->getNumberFormat()
                ->setFormatCode('#,##0');
        }

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

        // Buat tabel dengan border
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 50,
        ]);

        // Header tabel
        $table->addRow();
        $headers = ['No', 'Kasir', 'Bulan', 'Total Order', 'Total Pendapatan', 'Komisi Kasir (20%)', 'Keuntungan Bersih (80%)'];
        foreach ($headers as $header) {
            $table->addCell(3000)->addText($header, ['bold' => true]);
        }

        // Isi tabel
        foreach ($reports as $index => $report) {
            $table->addRow();
            $table->addCell(3000)->addText($index + 1);
            $table->addCell(3000)->addText($report->kasir_name);
            $table->addCell(3000)->addText(Carbon::parse($report->bulan_tahun . '-01')->format('F Y'));
            $table->addCell(3000)->addText($report->total_order);
            $table->addCell(3000)->addText('Rp ' . number_format($report->total_pendapatan, 0, ',', '.'));
            $table->addCell(3000)->addText('Rp ' . number_format($report->total_komisi_kasir, 0, ',', '.'));
            $table->addCell(3000)->addText('Rp ' . number_format($report->total_keuntungan_bersih, 0, ',', '.'));
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
