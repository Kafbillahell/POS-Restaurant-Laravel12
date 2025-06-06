<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsExport implements FromView
{
    protected $bulanTahun;

    public function __construct(string $bulanTahun)
    {
        $this->bulanTahun = $bulanTahun;
    }

    public function view(): View
    {
        [$tahun, $bulan] = explode('-', $this->bulanTahun);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

        $reports = DB::table('users')
            ->select(
                'users.name as kasir_name',
                DB::raw("'{$this->bulanTahun}' as bulan_tahun"),
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

        return view('reports.export_excel', [
            'reports' => $reports,
            'bulanTahun' => $this->bulanTahun,
        ]);
    }
}
