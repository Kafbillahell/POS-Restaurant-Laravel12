<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('menus.create', compact('kategoris'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kategori_id' => 'required',
        'nama_menu' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'harga' => 'required|numeric',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $data = $request->all();

    if ($request->hasFile('gambar')) {
        $imageName = time().'.'.$request->gambar->extension();  
        $request->gambar->move(public_path('images'), $imageName);
        $data['gambar'] = 'images/'.$imageName;
    }

    $menuBaru = Menu::create($data);

    return redirect()->route('orders.index')->with('menuBaru', $menuBaru);
}


    public function edit(Menu $menu)
    {
        $kategoris = Kategori::all();
        return view('menus.edit', compact('menu', 'kategoris'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_menu' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu->kategori_id = $request->kategori_id;
        $menu->nama_menu = $request->nama_menu;
        $menu->deskripsi = $request->deskripsi;
        $menu->harga = $request->harga;

        if ($request->hasFile('gambar')) {
            $menu->gambar = $request->file('gambar')->store('images', 'public');
        }

        $menu->save();

        \App\Models\Order::where('menu_id', $menu->id)->update([
            'nama_menu' => $menu->nama_menu,
            'harga_menu' => $menu->harga,
            'gambar_menu' => $menu->gambar,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
