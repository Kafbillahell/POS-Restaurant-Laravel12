<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\DetailOrder;

class OrderController extends Controller
{
    public function index()
{
    $orders = Order::all();
    $menus = Menu::latest()->get(); // Ambil semua menu (termasuk yang baru saja dibuat)

    return view('orders.index', compact('orders', 'menus'));
}

    public function create(Request $request)
    {
        $menus = Menu::all();
        $selectedMenu = null;
        $selectedPrice = null;

        if ($request->has('menu_id')) {
            $selectedMenu = Menu::find($request->menu_id);
            if ($selectedMenu) {
                $selectedPrice = $selectedMenu->harga;
            }
        }

        return view('orders.create', compact('menus', 'selectedMenu', 'selectedPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'nama_pemesan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($request->menu_id);

        $order = new Order();
        $order->menu_id = $menu->id;
        $order->nama_menu = $menu->nama_menu;
        $order->harga_menu = $menu->harga;
        $order->gambar_menu = $menu->gambar;
        $order->nama_pemesan = $request->nama_pemesan;
        $order->save();

        DetailOrder::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'jumlah' => $request->jumlah,
            'subtotal' => $menu->harga * $request->jumlah,
        ]);

        return redirect()->route('detail_orders.index')->with('success', 'Order dan detail order berhasil ditambahkan');
    }

    public function edit(Order $order)
    {
        $menus = Menu::all();
        return view('orders.edit', compact('order', 'menus'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        $menu = Menu::find($request->menu_id);
        $order->menu_id = $menu->id;
        $order->nama_menu = $menu->nama_menu;
        $order->harga_menu = $menu->harga;
        $order->gambar_menu = $menu->gambar;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order berhasil diupdate');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }
}
