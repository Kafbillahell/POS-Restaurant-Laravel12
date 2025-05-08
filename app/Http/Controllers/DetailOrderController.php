<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class DetailOrderController extends Controller
{
    public function index()
    {
        $detailOrders = DetailOrder::with(['order', 'menu'])->latest()->get();
        return view('detail_orders.index', compact('detailOrders'));
    }

    public function create()
    {
        $orders = Order::all();
        $menus = Menu::all();
        return view('detail_orders.create', compact('orders', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        DetailOrder::create($request->all());
        return redirect()->route('detail_orders.index')->with('success', 'Detail order berhasil ditambahkan.');
    }

    public function edit(DetailOrder $detailOrder)
    {
        $orders = Order::all();
        $menus = Menu::all();
        return view('detail_orders.edit', compact('detailOrder', 'orders', 'menus'));
    }

    public function update(Request $request, DetailOrder $detailOrder)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detailOrder->update($request->all());
        return redirect()->route('detail_orders.index')->with('success', 'Detail order berhasil diperbarui.');
    }

    public function destroy(DetailOrder $detailOrder)
    {
        $detailOrder->delete();
        return redirect()->route('detail_orders.index')->with('success', 'Detail order berhasil dihapus.');
    }
}
