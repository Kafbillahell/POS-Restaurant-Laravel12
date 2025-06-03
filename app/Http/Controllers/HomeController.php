<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\DetailOrder;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $bulanIni = date('Y-m');

        // Ambil laporan ringkasan untuk admin
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

        $totalOrder = $reports->sum('total_order');
        $totalPendapatan = $reports->sum('total_pendapatan');
        $totalKomisi = $reports->sum('total_komisi_kasir');
        $totalKeuntungan = $reports->sum('total_keuntungan_bersih');

        // Ambil data stok menipis, misal stok kurang dari 5
        $lowStockMenus = Menu::where('stok', '<', 5)->get();

        // Pagination data untuk tampilan umum
        $menus = Menu::paginate(10);
        $orders = Order::paginate(10);
        $detailOrders = DetailOrder::paginate(10);

        return view('dashboard.index', compact(
            'menus', 
            'orders', 
            'detailOrders', 
            'totalOrder', 
            'totalPendapatan', 
            'totalKomisi', 
            'totalKeuntungan',
            'lowStockMenus'
        ));
    }
}
