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
        // Mengubah if statement agar tetap bisa diakses kasir/user lain jika ada logic di bawahnya.
        // Asumsi: Admin melihat laporan, Kasir melihat menu/quick actions.
        if ($user->role === 'admin') {
            
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

            // Buat array untuk chart pendapatan per kasir
            $labels = $reports->pluck('kasir_name');
            $pendapatan = $reports->pluck('total_pendapatan');
            $komisi = $reports->pluck('total_komisi_kasir');
            $keuntungan = $reports->pluck('total_keuntungan_bersih');

            // Total ringkasan
            $totalOrder = $reports->sum('total_order');
            $totalPendapatan = $reports->sum('total_pendapatan');
            $totalKomisi = $reports->sum('total_komisi_kasir');
            $totalKeuntungan = $reports->sum('total_keuntungan_bersih');

            // Data pendapatan harian selama 7 hari terakhir
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

            // Data stok menu yang hampir habis (misal stok <= 5)
            $lowStockMenus = Menu::where('stok', '<=', 5)->get();

            // ==============================
            // 🚀 MODIFIKASI: Data Menu Best Seller
            // Mengambil 5 menu terlaris, total terjual, dan total pendapatan.
            // ==============================
            $bestSellers = DetailOrder::select(
                    'menus.nama_menu as product_name', // Ambil nama menu
                    DB::raw('SUM(detail_orders.qty) as total_quantity'), // Total Terjual
                    DB::raw('SUM(detail_orders.subtotal) as total_revenue') // Total Pendapatan
                )
                ->join('menus', 'detail_orders.menu_id', '=', 'menus.id')
                // Filter berdasarkan bulan ini (opsional, jika ingin laporan best seller bulanan)
                ->join('orders', 'detail_orders.order_id', '=', 'orders.id')
                ->whereRaw("DATE_FORMAT(orders.created_at, '%Y-%m') = ?", [$bulanIni]) 
                
                ->groupBy('menus.nama_menu')
                ->orderByDesc('total_quantity')
                ->take(5) // Ambil 5 menu terlaris
                ->get();

            // Ambil data paginasi menu, order, dan detail order (untuk Admin, mungkin tidak perlu paginasi)
            // Hanya untuk memastikan variabel ada saat dikirim ke compact
            $menus = Menu::all();
            $orders = Order::all();
            $detailOrders = DetailOrder::all();
            
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

        } else {
            // Logic untuk Kasir/User Biasa
            
            // Data menu (untuk tampilan di dashboard non-admin)
            $menus = Menu::with('kategori')->get();
            
            // Variabel untuk Kasir
            if ($user->role === 'kasir') {
                $lowStockMenus = Menu::where('stok', '<=', 5)->get();
                return view('dashboard.index', compact('menus', 'lowStockMenus'));
            }

            // Variabel untuk User Lain
            return view('dashboard.index', compact('menus'));
        }
    }
}