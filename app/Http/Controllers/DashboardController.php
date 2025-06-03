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
            ->leftJoin('orders', function($join) use ($bulanIni) {
                $join->on('orders.user_id', '=', 'users.id')
                     ->whereRaw("DATE_FORMAT(orders.created_at, '%Y-%m') = ?", [$bulanIni]);
            })
            ->where('users.role', 'kasir')
            ->groupBy('users.id', 'users.name')
            ->get();

        $totalOrder = $reports->sum('total_order');
        $totalPendapatan = $reports->sum('total_pendapatan');
        $totalKomisi = $reports->sum('total_komisi_kasir');
        $totalKeuntungan = $reports->sum('total_keuntungan_bersih');

        // Ambil data paginasi menu, order, dan detail order (10 per halaman)
        $menus = Menu::paginate(10);
        $orders = Order::paginate(10);
        $detailOrders = DetailOrder::paginate(10);

        return view('dashboard.index', compact(
            'totalOrder',
            'totalPendapatan',
            'totalKomisi',
            'totalKeuntungan',
            'menus',
            'orders',
            'detailOrders'
        ));
    }
}
