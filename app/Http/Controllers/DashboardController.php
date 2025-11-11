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

        // =============================
// 📊 Data Best Seller (Sudah dimodifikasi untuk 1 Hari Terakhir)
// =============================
$bestSellers = DetailOrder::select(
    'detail_orders.menu_id', // Gunakan alias tabel untuk kejelasan
    DB::raw('SUM(detail_orders.quantity) as total_sold')
)
// Tambahkan filter waktu di sini
->whereHas('order', function ($query) {
    $query->where('orders.created_at', '>=', now()->subHour());
})
// Lanjutkan grouping dan ordering seperti sebelumnya
->groupBy('detail_orders.menu_id')
->orderByDesc('total_sold')
->with('menu')
->take(5)
->get();

        // =============================
        // 💰 Data Ringkasan Bulanan
        // =============================
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

        // Data untuk grafik pendapatan per kasir
        $labels = $reports->pluck('kasir_name');
        $pendapatan = $reports->pluck('total_pendapatan');
        $komisi = $reports->pluck('total_komisi_kasir');
        $keuntungan = $reports->pluck('total_keuntungan_bersih');

        // =============================
        // 🔢 Total Ringkasan
        // =============================
        $totalOrder = $reports->sum('total_order');
        $totalPendapatan = $reports->sum('total_pendapatan');
        $totalKomisi = $reports->sum('total_komisi_kasir');
        $totalKeuntungan = $reports->sum('total_keuntungan_bersih');

        // =============================
        // 📆 Pendapatan 7 Hari Terakhir
        // =============================
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

        // =============================
        // 🍜 Data Menu, Order, Detail Order
        // =============================
        $menus = Menu::paginate(10);
        $orders = Order::paginate(10);
        $detailOrders = DetailOrder::paginate(10);

        // =============================
        // ⚠️ Menu Stok Hampir Habis
        // =============================
        $lowStockMenus = Menu::where('stok', '<=', 5)->get();

        // =============================
        // 🧭 Return View
        // =============================
        return view('dashboard.index', compact(
            'reports',
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
            'earningValues',
            'bestSellers' 
        ));
    }
}
