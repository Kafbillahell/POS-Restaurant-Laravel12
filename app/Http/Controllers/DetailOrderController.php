<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use Illuminate\Http\Request;

class DetailOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $detailOrders = DetailOrder::with(['order', 'menu'])
            ->when($user->role === 'kasir', function ($query) use ($user) {
                $query->whereHas('order', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->get();

        return view('detail_orders.index', compact('detailOrders'));
    }

    public function show($id)
    {
        $detailOrder = DetailOrder::with(['order', 'menu'])->findOrFail($id);

        return view('detail_orders.show', compact('detailOrder'));
    }

    // Nonaktifkan metode CRUD yang tidak digunakan
    public function create()
    {
        abort(403, 'Akses tidak diizinkan.');
    }

    public function store(Request $request)
    {
        abort(403, 'Akses tidak diizinkan.');
    }

    public function edit($id)
    {
        abort(403, 'Akses tidak diizinkan.');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'Akses tidak diizinkan.');
    }

    public function destroy($id)
    {
        abort(403, 'Akses tidak diizinkan.');
    }
}
