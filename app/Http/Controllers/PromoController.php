<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Menu;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
    if (auth()->user()->role === 'user') {
        return view('promo.user_index', compact('promos'));
    } else {
        return view('promo.index', compact('promos'));
    }
    }

    public function create()
    {
    $menus = \App\Models\Menu::all();
    return view('promo.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'diskon_persen' => 'required|numeric|min:0|max:100',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        Promo::create($request->all());
        return redirect()->route('promos.index')->with('success', 'Promo berhasil ditambahkan!');
    }

    public function edit(Promo $promo)
    {
        $menus = Menu::all();
        return view('promo.edit', compact('promo', 'menus'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'diskon_persen' => 'required|numeric|min:0|max:100',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $promo->update($request->all());
        return redirect()->route('promos.index')->with('success', 'Promo berhasil diperbarui!');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('promos.index')->with('success', 'Promo berhasil dihapus!');
    }
}
