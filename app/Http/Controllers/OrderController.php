<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Member;
use App\Models\Menu; 
use App\Models\User;
use App\Models\DetailOrder;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;


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
        if ($redirect = $this->checkRole())
            return $redirect;
            
        $query = Menu::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        if (!session()->has('success') && !session()->has('error') && !session()->has('menuBaru')) {
        session()->forget('cart'); 
        }

        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', $request->kategori);
            });
        }

        $menus = $query->get(); 
        $kategoris = Kategori::all();

        $cart = session('cart', []);
        $updatedCart = [];
        $menusCache = $menus->keyBy('id');
        $isPriceUpdated = false;

        foreach ($cart as $menuId => $item) {
            $menu = $menusCache->get($menuId);
            
            if ($menu) {
                $currentPrice = (float) $menu->harga_jual; 
                $itemPriceInCart = (float) $item['harga']; 

                if ($itemPriceInCart !== $currentPrice) {
                    $isPriceUpdated = true; 
                    $item['harga'] = $currentPrice;
                    
                    $currentNormalPrice = (float) $menu->harga;
                    
                    if ($currentNormalPrice > $currentPrice) {
                        $item['harga_normal'] = $currentNormalPrice;
                    } else {
                        if (isset($item['harga_normal'])) {
                            unset($item['harga_normal']);
                        }
                    }
                }
            } else {
                $isPriceUpdated = true; 
                continue; 
            }
            $updatedCart[$menuId] = $item;
        }
        
        if ($isPriceUpdated) {
            session(['cart' => $updatedCart]);
        }
        
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $pagedMenus = new LengthAwarePaginator(
            $menus->forPage($currentPage, $perPage),
            $menus->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );
        $menus = $pagedMenus; 
        

        $menusGrouped = $menus->groupBy(function ($menu) {
            return optional($menu->kategori)->nama_kategori ?? 'Tanpa Kategori';
        });

        $kategoriOrder = []; 

        if ($request->ajax()) {
            return response()->view('orders.index', compact(
                'menus',
                'menusGrouped',
                'kategoriOrder',
                'kategoris'
            ));
        }

        if ($isPriceUpdated && !$request->has('reloaded')) {
              return redirect()->route('orders.index', ['reloaded' => 1]);
        }


        return view('orders.index', compact(
            'menus',
            'menusGrouped',
            'kategoriOrder',
            'kategoris'
        ));
    }

    public function create(Request $request)
    {
        if ($redirect = $this->checkRole())
            return $redirect;

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders.index')->with('error', 'Keranjang kosong.');
        }

        $menuIds = array_keys($cart);
        $menusCache = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

        $updatedCart = [];
        $totalHargaMenu = 0; 
        $isPriceUpdated = false; 

        foreach ($cart as $menuId => $item) {
            $menu = $menusCache->get($menuId);
            
            if (!$menu) {
                unset($cart[$menuId]);
                $isPriceUpdated = true; 
                continue;
            }

            $currentPrice = (float) $menu->harga_jual; 
            $itemPriceInCart = (float) $item['harga']; 

            if ($itemPriceInCart !== $currentPrice) {
                
                $isPriceUpdated = true; 
                
                $item['harga'] = $currentPrice;
                
                $currentNormalPrice = (float) $menu->harga; 
                
                if ($currentNormalPrice > $currentPrice) {
                    $item['harga_normal'] = $currentNormalPrice;
                } else {
                    if (isset($item['harga_normal'])) {
                        unset($item['harga_normal']);
                    }
                }
            }
            
            $subtotal = $item['harga'] * $item['quantity'];
            $totalHargaMenu += $subtotal; 
            
            $updatedCart[$menuId] = $item;
        }

        session(['cart' => $updatedCart]);
        $cart = $updatedCart; 
        
        $member = null;
        $potongan = 0;
        
        if ($request->filled('member_id')) {
            $member = Member::find($request->member_id);
        }
        
        $totalBayar = max($totalHargaMenu - $potongan, 0);

        if ($isPriceUpdated && !$request->has('refreshed')) {
            return redirect()->route('orders.create', ['refreshed' => 1]);
        }

        $menus = Menu::all();
        $kasirs = auth()->user()->role === 'kasir' ? User::where('role', 'kasir')->get() : [];

        return view('orders.create', compact('menus', 'kasirs', 'cart', 'member', 'potongan', 'totalBayar'));
    }


    /**
     * Menyimpan pesanan ke database (Dilengkapi Re-validasi Harga Final)
     */
    public function store(Request $request)
    {
        if ($redirect = $this->checkRole())
            return $redirect;

        $rules = [
            'nama_pemesan' => 'required|string|max:255',
            'jumlah_bayar' => 'required|numeric|min:0',
        ];

        if (auth()->user()->role === 'kasir') {
            $rules['nama_kasir'] = 'required|string|max:255';
        }

        if ($request->member_id) {
            $rules['member_id'] = 'exists:members,id';
        }

        $request->validate($rules);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong, silakan tambah menu terlebih dahulu.');
        }

        $menuIds = array_keys($cart);
        $menusCache = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

        $updatedCart = [];
        $totalHarga = 0; 
        $pointsUsed = 0;
        $potongan = 0;


        foreach ($cart as $menuId => $item) {
            $menu = $menusCache->get($menuId);
            
            if (!$menu || $menu->stok < $item['quantity']) {
                return back()->with('error', "Stok tidak cukup atau menu tidak ditemukan untuk ID {$menuId}.");
            }

            $currentPrice = (float) $menu->harga_jual; 
            $itemPriceInCart = (float) $item['harga']; 

            if ($itemPriceInCart !== $currentPrice) {
                $item['harga'] = $currentPrice;
                
                $currentNormalPrice = (float) $menu->harga; 
                
                if ($currentNormalPrice > $currentPrice) {
                    $item['harga_normal'] = $currentNormalPrice;
                } else {
                    if (isset($item['harga_normal'])) {
                        unset($item['harga_normal']);
                    }
                }
            }
            
            $subtotal = $item['harga'] * $item['quantity'];
            $totalHarga += $subtotal; 
            
            $updatedCart[$menuId] = $item;
        }

        session(['cart' => $updatedCart]);
        $cart = $updatedCart; 
        
        
        $member = null;

        if ($request->member_id) {
            $member = Member::find($request->member_id);

            if ($request->use_points && $member && $member->points >= 10) {
                $pointsUsed = floor($member->points / 10) * 10;
                $potongan = $pointsUsed * 7500 / 10; 
            }
        }
        
        
        $totalHargaFinal = max($totalHarga - $potongan, 0);


        if ($request->jumlah_bayar < $totalHargaFinal) {
            return back()->with('error', 'Jumlah bayar tidak boleh kurang dari total harga setelah potongan.');
        }

        DB::transaction(function () use ($request, $cart, $menusCache, $totalHarga, $totalHargaFinal, $member, $potongan, $pointsUsed) {
            $user = auth()->user();
            $namaKasir = $user->role === 'kasir' ? $request->nama_kasir : $user->name;

            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $request->jumlah_bayar - $totalHargaFinal,
                'user_id' => $user->id,
                'nama_kasir' => $namaKasir,
                'total_harga' => $totalHargaFinal, 
                'potongan' => $potongan,
                'member_id' => $member?->id,
            ]);

            foreach ($cart as $menuId => $item) {
                $menu = $menusCache->get($menuId);
                $qty = $item['quantity'];
                
                $hargaFinalItem = $item['harga']; 

                DetailOrder::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'harga_menu' => $hargaFinalItem, 
                    'jumlah' => $qty,
                    'subtotal' => $hargaFinalItem * $qty, 
                ]);

                $menu->decrement('stok', $qty);
            }

            if ($member) {
                $member->decrement('points', $pointsUsed);
                
                $pointsEarned = floor($totalHarga / 3000); 

                $member->increment('points', $pointsEarned);
            }
        });

        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan dan stok diperbarui.');
    }
    
    public function edit(Order $order)
    {
        if ($redirect = $this->checkRole())
            return $redirect;

        return view('orders.edit', [
            'order' => $order->load('detailOrders.menu'),
            'menus' => Menu::all(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        if ($redirect = $this->checkRole())
            return $redirect;

        $request->validate([
            'menu_id' => 'required|array|min:1',
            'menu_id.*' => 'exists:menus,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'integer|min:1',
            
            'harga_satuan' => 'required|array|min:1', 
            'harga_satuan.*' => 'numeric|min:0', 
        ]);

        
        if (count($request->menu_id) !== count($request->jumlah) || count($request->menu_id) !== count($request->harga_satuan)) {
            return back()->with('error', 'Data menu, jumlah, dan harga tidak sesuai.');
        }

        DB::transaction(function () use ($request, $order) {
            
            foreach ($order->detailOrders as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu)
                    $menu->increment('stok', $detail->jumlah);
            }

            $order->detailOrders()->delete();

            $totalHarga = 0;

            foreach ($request->menu_id as $index => $menuId) {
                $menu = Menu::findOrFail($menuId);
                $qty = (int) $request->jumlah[$index];
                
                
                $hargaFinalItem = (float) $request->harga_satuan[$index]; 

                if ($menu->stok < $qty) {
                    
                    throw new \Exception("Stok tidak cukup untuk menu {$menu->nama_menu}.");
                }

                DetailOrder::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'nama_menu' => $menu->nama_menu, 
                    'harga_menu' => $hargaFinalItem, 
                    'jumlah' => $qty,
                    'subtotal' => $hargaFinalItem * $qty, 
                ]);

                $menu->decrement('stok', $qty);
                $totalHarga += $hargaFinalItem * $qty; 
            }

            $order->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil diupdate');
    }

    public function destroy(Order $order)
    {
        if ($redirect = $this->checkRole())
            return $redirect;

        DB::transaction(function () use ($order) {
            foreach ($order->detailOrders as $detail) {
                $menu = Menu::find($detail->menu_id);
                if ($menu)
                    $menu->increment('stok', $detail->jumlah);
            }

            $order->detailOrders()->delete();
            $order->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
    }

    
    public function addToCart(Request $request)
    {
        $menu = Menu::findOrFail($request->menu_id);
        $cart = session()->get('cart', []);
        $id = $menu->id;

        $hargaNormal = $menu->harga; 
        $stokTersedia = $menu->stok; 
        
        $hargaFinal = $menu->harga_jual; 
        $isPromo = $hargaFinal < $hargaNormal;

        $currentQuantity = $cart[$id]['quantity'] ?? 0;
        $newQuantity = $currentQuantity + 1;

        $stokUntukPengecekan = $stokTersedia; 

        if ($newQuantity > $stokUntukPengecekan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi untuk "' . $menu->nama_menu . '". Stok tersedia: ' . $stokUntukPengecekan,
                'cart' => $cart,
                'new_stok' => max(0, $stokUntukPengecekan - $currentQuantity),
            ]);
        }
        
        $cart[$id] = [
            'nama_menu' => $menu->nama_menu,
            'harga' => $hargaFinal, 
            'harga_normal' => $isPromo ? $hargaNormal : null,
            'gambar' => $menu->gambar,
            'stok' => $stokTersedia, 
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);

        $newStok = $stokTersedia - $newQuantity;

        $message = 'Menu "' . $menu->nama_menu . '" ditambahkan ke keranjang.' . ($isPromo ? ' (Harga Promo)' : '');

        return response()->json([
            'status' => 'success',
            'message' => $message,
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

            
            $jumlahDiKeranjang = $cart[$menuId]['quantity'] ?? 0;

            
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
        if ($redirect = $this->checkRole())
            return $redirect;

        $order->load('detailOrders.menu.kategori');

        return view('detail_orders.show', compact('order'));
    }

    public function getMenus(Request $request)
    {
        $query = Menu::with('kategori');

        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
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

    public function deleteMenu($orderId, $menuId)
    {
        if ($redirect = $this->checkRole())
            return $redirect;

        
        $order = Order::find($orderId);

        if ($order) {
            
            $detailOrder = $order->detailOrders()->where('menu_id', $menuId)->first();

            if ($detailOrder) {
                
                $menu = Menu::find($menuId);
                if ($menu) {
                    $menu->increment('stok', $detailOrder->jumlah);
                }

                
                $detailOrder->delete();
            }
        }

        
        return redirect()->route('orders.show', $orderId)->with('success', 'Menu berhasil dihapus dari pesanan.');
    }

    public function checkMember(Request $request)
    {
        $email = $request->query('email');

        $member = Member::where('email', $email)->first();

        if ($member) {
            return response()->json([
                'exists' => true,
                'member' => $member
            ]);
        } else {
            return response()->json([
                'exists' => false
            ]);
        }
    }

    public function syncPrice(Request $request)
    {
        if (!$request->expectsJson() || !$request->filled(['menu_id', 'harga'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request.'], 400);
        }

        $menuId = $request->input('menu_id');
        $newPrice = (float) $request->input('harga');

        $cart = session()->get('cart', []);

        if (isset($cart[$menuId])) {
            if ($cart[$menuId]['harga'] != $newPrice) {
                $cart[$menuId]['harga'] = $newPrice; 
                session()->put('cart', $cart);
                
                return response()->json([
                    'status' => 'success', 
                    'message' => "Harga menu ID {$menuId} berhasil disinkronkan ke harga normal: {$newPrice}."
                ]);
            }
            return response()->json(['status' => 'success', 'message' => 'Harga sudah sinkron.']);
        }

        return response()->json(['status' => 'success', 'message' => 'Menu tidak ditemukan di keranjang.']);
    }
}