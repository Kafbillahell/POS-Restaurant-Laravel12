<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\DetailOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Ambil data ringkasan laporan bulan ini
        $bulanIni = date('Y-m');

        $reports = DB::table('users')
            ->select(
                'users.id as kasir_id',
                'users.name as kasir_name',
                DB::raw('COUNT(orders.id) as total_order'),
                DB::raw('COALESCE(SUM(orders.jumlah_bayar), 0) as total_pendapatan'),
                DB::raw('COALESCE(SUM(orders.jumlah_bayar) * 0.2, 0) as total_komisi_kasir'),
                DB::raw('COALESCE(SUM(orders.jumlah_bayar) * 0.8, 0) as total_keuntungan_bersih')
            )
            ->leftJoin('orders', function ($join) use ($bulanIni) {
                $join->on('orders.user_id', '=', 'users.id')
                    ->whereRaw("DATE_FORMAT(orders.created_at, '%Y-%m') = ?", [$bulanIni]);
            })
            ->where('users.role', 'kasir')
            ->groupBy('users.id', 'users.name')
            ->get();

        // Buat array untuk chart
        $labels = $reports->pluck('kasir_name');
        $pendapatan = $reports->pluck('total_pendapatan');
        $komisi = $reports->pluck('total_komisi_kasir');
        $keuntungan = $reports->pluck('total_keuntungan_bersih');

        // Total ringkasan
        $totalOrder = $reports->sum('total_order');
        $totalPendapatan = $reports->sum('total_pendapatan');
        $totalKomisi = $reports->sum('total_komisi_kasir');
        $totalKeuntungan = $reports->sum('total_keuntungan_bersih');

        // Data pendapatan harian selama 7 hari terakhir, group by tanggal (bukan langsung created_at)
        $earningData = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%a') as day"),
            DB::raw("SUM(jumlah_bayar) as total"),
            DB::raw("DATE(created_at) as tanggal")
        )
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('tanggal', 'day')
            ->orderBy('tanggal')
            ->get();


        $earningLabels = $earningData->pluck('day');
        $earningValues = $earningData->pluck('total');

        // Ambil data paginasi menu, order, dan detail order (10 per halaman)
        $menus = Menu::paginate(10);
        $orders = Order::paginate(10);
        $detailOrders = DetailOrder::paginate(10);

        // Data stok menu yang hampir habis (misal stok <=5)
        $lowStockMenus = Menu::where('stok', '<=', 5)->get();

           return view('dashboard.index', compact(
    'reports', // <== tambahkan ini
    'totalOrder',
    'totalPendapatan',
    'totalKomisi',
    'totalKeuntungan',
    'menus',
    'orders',
    'detailOrders',
    'labels',
    'pendapatan',
    'komisi',
    'keuntungan',
    'lowStockMenus',
    'earningLabels',
    'earningValues'
));

        }
}
