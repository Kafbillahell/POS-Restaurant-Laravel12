<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Models\DetailOrder;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function checkRole()
    {
        if (auth()->guest() || !in_array(auth()->user()->role, ['kasir', 'user'])) {
            return redirect()->route('no-access');
        }
        return null;
    }

   public function index(Request $request)
{
    $query = Menu::query();

    // Filter search nama menu
    if ($request->has('search') && !empty($request->search)) {
        $query->where('nama_menu', 'like', '%' . $request->search . '%');
    }

    // Filter kategori jika dipilih
    if ($request->has('kategori') && !empty($request->kategori)) {
        $query->whereHas('kategori', function($q) use ($request) {
            $q->where('nama_kategori', $request->kategori);
        });
    }

    $menus = $query->paginate(10); // atau get(), tergantung kebutuhan

    $kategoris = Kategori::all();

    return view('orders.index', compact('menus', 'kategoris'));
}



    public function create(Request $request)
    {
        if ($redirect = $this->checkRole()) return $redirect;

        $menus = Menu::all();
        $kasirs = auth()->user()->role === 'kasir' ? User::where('role', 'kasir')->get() : [];
        $cart = session()->get('cart', []);

        return view('orders.create', compact('menus', 'kasirs', 'cart'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkRole()) return $redirect;

        $rules = [
            'nama_pemesan' => 'required|string|max:255',
            'jumlah_bayar' => 'required|numeric|min:0',
        ];

        if (auth()->user()->role === 'kasir') {
            $rules['nama_kasir'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong, silakan tambah menu terlebih dahulu.');
        }

        $menusCache = [];
        $totalHarga = 0;

        foreach ($cart as $menuId => $item) {
            $menu = Menu::find($menuId);
            if (!$menu) return back()->with('error', "Menu ID $menuId tidak ditemukan.");
            if ($menu->stok < $item['quantity']) return back()->with('error', "Stok tidak cukup untuk {$menu->nama_menu}.");

            $menusCache[$menuId] = $menu;
            $totalHarga += $menu->harga * $item['quantity'];
        }

        if ($request->jumlah_bayar < $totalHarga) {
            return back()->with('error', 'Jumlah bayar tidak boleh kurang dari total harga.');
        }

        DB::transaction(function () use ($request, $cart, $menusCache, $totalHarga) {
            $user = auth()->user();
            $namaKasir = $user->role === 'kasir' ? $request->nama_kasir : $user->name;

            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $request->jumlah_bayar - $totalHarga,
                'user_id' => $user->id,
                'nama_kasir' => $namaKasir,
                'total_harga' => $totalHarga,
            ]);

            foreach ($cart as $menuId => $item) {
                $menu = $menusCache[$menuId];
                $qty = $item['quantity'];

                DetailOrder::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $qty,
                    'subtotal' => $menu->harga * $qty,
                ]);

                $menu->decrement('stok', $qty);
            }
        });

        session()->forget('cart');
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan dan stok diperbarui.');
    }

    public function edit(Order $order)
    {
        if ($redirect = $this->checkRole()) return $redirect;

        return view('orders.edit', [
            'order' => $order->load('detailOrders.menu'),
            'menus' => Menu::all(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        if ($redirect = $this->checkRole()) return $redirect;

        $request->validate([
            'menu_id' => 'required|array|min:1',
            'menu_id.*' => 'exists:menus,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'integer|min:1',
        ]);

        if (count($request->menu_id) !== count($request->jumlah)) {
            return back()->with('error', 'Data menu dan jumlah tidak sesuai.');
        }

        DB::transaction(function () use ($request, $order) {
            // Restock old menu items
            foreach ($order->detailOrders as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu) $menu->increment('stok', $detail->jumlah);
            }

            $order->detailOrders()->delete();

            $totalHarga = 0;

            foreach ($request->menu_id as $index => $menuId) {
                $menu = Menu::findOrFail($menuId);
                $qty = (int)$request->jumlah[$index];

                if ($menu->stok < $qty) {
                    throw new \Exception("Stok tidak cukup untuk menu {$menu->nama_menu}.");
                }

                DetailOrder::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $qty,
                    'subtotal' => $menu->harga * $qty,
                ]);

                $menu->decrement('stok', $qty);
                $totalHarga += $menu->harga * $qty;
            }

            $order->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil diupdate');
    }

    public function destroy(Order $order)
    {
        if ($redirect = $this->checkRole()) return $redirect;

        DB::transaction(function () use ($order) {
            foreach ($order->detailOrders as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu) $menu->increment('stok', $detail->jumlah);
            }

            $order->detailOrders()->delete();
            $order->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }

    // Perbarui addToCart dengan validasi stok dan sesi keranjang
public function addToCart(Request $request)
{
    $menu = Menu::findOrFail($request->menu_id);
    $cart = session()->get('cart', []);
    $id = $menu->id;

    // Cek apakah sudah ada di cart
    if (isset($cart[$id])) {
        // Hitung stok sisa dengan mengurangi quantity di cart dari stok awal
        $stokSisa = $menu->stok - $cart[$id]['quantity'];

        if ($stokSisa > 0) {
            // Tambah quantity di cart
            $cart[$id]['quantity']++;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi untuk "' . $menu->nama_menu . '".',
                'cart' => $cart,
                'new_stok' => max(0, $stokSisa),
            ]);
        }
    } else {
        // Jika belum ada di cart, cek stok tersedia
        if ($menu->stok < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi untuk "' . $menu->nama_menu . '".',
                'cart' => $cart,
                'new_stok' => 0,
            ]);
        }
        // Masukkan item baru ke cart dengan quantity 1
        $cart[$id] = [
            'nama_menu' => $menu->nama_menu,
            'harga' => $menu->harga,
            'gambar' => $menu->gambar,
            'stok' => $menu->stok,
            'quantity' => 1,
        ];
    }

    // Simpan cart yang sudah diupdate ke session
    session()->put('cart', $cart);

    // Hitung stok sisa setelah ditambahkan ke cart
    $newStok = $menu->stok - $cart[$id]['quantity'];

    return response()->json([
        'status' => 'success',
        'message' => 'Menu "' . $menu->nama_menu . '" berhasil ditambahkan ke keranjang!',
        'cart' => $cart,
        'new_stok' => max(0, $newStok),
    ]);
}





public function removeFromCart(Request $request)
{
    $menuId = $request->input('menu_id');
    $cart = session()->get('cart', []);

    $menu = Menu::find($menuId);
    if (!$menu) {
        return response()->json([
            'status' => 'error',
            'message' => 'Menu tidak ditemukan',
            'cart' => $cart,
        ]);
    }

    if (isset($cart[$menuId])) {
        if ($cart[$menuId]['quantity'] > 1) {
            $cart[$menuId]['quantity']--;
        } else {
            unset($cart[$menuId]);
        }

        session()->put('cart', $cart);

        // Hitung kembali jumlah item ini dalam keranjang
        $jumlahDiKeranjang = $cart[$menuId]['quantity'] ?? 0;

        // Stok yang bisa ditampilkan ke pengguna adalah stok asli - jumlah di keranjang
        $newStok = $menu->stok - $jumlahDiKeranjang;

        return response()->json([
            'status' => 'success',
            'cart' => $cart,
            'new_stok' => max(0, $newStok),
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Item tidak ditemukan di keranjang',
        'cart' => $cart,
    ]);
}




    public function show(Order $order)
    {
        if ($redirect = $this->checkRole()) return $redirect;

        $order->load('detailOrders.menu.kategori');

        return view('detail_orders.show', compact('order'));
    }

    public function getMenus(Request $request)
{
    $query = Menu::with('kategori');

    if ($request->filled('search')) {
        $query->where('nama_menu', 'like', '%'.$request->search.'%');
    }

    if ($request->filled('kategori')) {
        $query->whereHas('kategori', function ($q) use ($request) {
            $q->where('nama_kategori', $request->kategori);
        });
    }

    return response()->json($query->get());
}

public function resetCart(Request $request)
{
    session()->forget('cart');
    return response()->json(['status' => 'success', 'message' => 'Keranjang telah direset']);
}


    
}
