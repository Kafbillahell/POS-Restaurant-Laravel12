<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Member;

use App\Models\Menu;
use App\Models\User;
use App\Models\DetailOrder;
use App\Models\Kategori;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


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

        if (!session()->has('success') && !session()->has('error') && !session()->has('menuBaru')) {
        session()->forget('cart'); // Menjamin session cart bersih saat memuat halaman
    }

        // Filter kategori jika dipilih
        if ($request->has('kategori') && !empty($request->kategori)) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', $request->kategori);
            });
        }

        $menus = $query->paginate(10);
        $kategoris = Kategori::all();

        // Susun ulang menu menjadi grup berdasarkan kategori
        $menusGrouped = $menus->groupBy(function ($menu) {
            return optional($menu->kategori)->nama_kategori ?? 'Tanpa Kategori';
        });

        $kategoriOrder = []; // Tambahkan ini jika diperlukan untuk pengecualian kategori

        // Jika AJAX, kembalikan hanya bagian HTML menu (tanpa layout)
        if ($request->ajax()) {
            return response()->view('orders.index', compact(
                'menus',
                'menusGrouped',
                'kategoriOrder',
                'kategoris'
            ));
        }

        // Jika bukan AJAX, render halaman penuh
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

        $menus = Menu::all();
        $kasirs = auth()->user()->role === 'kasir' ? User::where('role', 'kasir')->get() : [];
        $cart = session()->get('cart', []);

        $member = null;
        if ($request->has('member_id')) {
            $member = Member::find($request->member_id);
        }

        return view('orders.create', compact('menus', 'kasirs', 'cart', 'member'));
    }


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

        $menusCache = [];
        $totalHarga = 0;

        foreach ($cart as $menuId => $item) {
            $menu = Menu::find($menuId);
            if (!$menu)
                return back()->with('error', "Menu ID $menuId tidak ditemukan.");
            if ($menu->stok < $item['quantity'])
                return back()->with('error', "Stok tidak cukup untuk {$menu->nama_menu}.");

            $menusCache[$menuId] = $menu;
            $totalHarga += $menu->harga * $item['quantity'];
        }

        // Persiapkan variabel poin
        $member = null;
        $potongan = 0;
        $pointsUsed = 0;

        if ($request->member_id) {
            $member = Member::find($request->member_id);

            if ($request->use_points && $member && $member->points >= 10) {
                $pointsUsed = floor($member->points / 10) * 10;
                $potongan = $pointsUsed * 7500 / 10;  // 7500 per 10 poin

                $totalHarga -= $potongan;
            }
        }

        if ($request->jumlah_bayar < $totalHarga) {
            return back()->with('error', 'Jumlah bayar tidak boleh kurang dari total harga setelah potongan.');
        }

        DB::transaction(function () use ($request, $cart, $menusCache, $totalHarga, $member, $potongan, $pointsUsed, &$order) {
            $user = auth()->user();
            $namaKasir = $user->role === 'kasir' ? $request->nama_kasir : $user->name;

            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $request->jumlah_bayar - $totalHarga,
                'user_id' => $user->id,
                'nama_kasir' => $namaKasir,
                'total_harga' => $totalHarga,
                'potongan' => $potongan,
                'member_id' => $member?->id,
            ]);

            foreach ($cart as $menuId => $item) {
                $menu = $menusCache[$menuId];
                $qty = $item['quantity'];

                DetailOrder::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'harga_menu' => $menu->harga,
                    'jumlah' => $qty,
                    'subtotal' => $menu->harga * $qty,
                ]);

                $menu->decrement('stok', $qty);
            }

            if ($member) {
                // Kurangi poin yang dipakai
                $member->decrement('points', $pointsUsed);

                // Hitung poin didapat dari total harga sebelum potongan
                $pointsEarned = floor(($totalHarga + $potongan) / 3000);

                // Tambah poin didapat
                $member->increment('points', $pointsEarned);
            }

            $order->load('detailOrders');
        });


        session()->forget('cart');

        if ($order) {
            $kitchenNumber = Setting::where('key', 'wa_kitchen_number')->value('value');
            
            if (!empty($kitchenNumber)) {
                $message = "🔔 *PESANAN BARU DARI KASIR*\n";
                $message .= "===============================\n";
                $message .= "No. Order: #{$order->id}\n";
                $message .= "Nama Pemesan: {$order->nama_pemesan}\n";
                $message .= "Kasir: {$order->nama_kasir}\n";
                $message .= "Waktu: " . now()->format('H:i:s, d M Y') . "\n";
                $message .= "===============================\n";
                $message .= "*RINCIAN MENU:*\n";

                if ($order->relationLoaded('detailOrders')) {
                    foreach ($order->detailOrders as $detail) {
                        $message .= "➡️ {$detail->jumlah}x " . strtoupper($detail->nama_menu) . "\n";
                    }
                } else {
                    Log::warning("Order {$order->id}: DetailOrders not loaded for WA notification.");
                }
                
                $message .= "===============================\n";
                $message .= "*SEGERA DIPROSES!*";

                $this->sendWaNotification($kitchenNumber, $message); 
            } else {
                Log::warning('WA Kitchen number is not set in settings table.');
            }
        }

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
        ]);

        if (count($request->menu_id) !== count($request->jumlah)) {
            return back()->with('error', 'Data menu dan jumlah tidak sesuai.');
        }

        DB::transaction(function () use ($request, $order) {
            // Restock old menu items
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
            'message' => 'Menu "' . $menu->nama_menu . '"',
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

        // Temukan order berdasarkan ID
        $order = Order::find($orderId);

        if ($order) {
            // Temukan detailOrder berdasarkan menu_id yang ingin dihapus
            $detailOrder = $order->detailOrders()->where('menu_id', $menuId)->first();

            if ($detailOrder) {
                // Kembalikan stok menu yang dihapus
                $menu = Menu::find($menuId);
                if ($menu) {
                    $menu->increment('stok', $detailOrder->jumlah);
                }

                // Hapus detailOrder yang sesuai
                $detailOrder->delete();
            }
        }

        // Redirect kembali ke halaman order detail
        return redirect()->route('orders.show', $orderId)->with('success', 'Menu berhasil dihapus dari pesanan.');
    }

   public function checkout(Request $request)
{
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Keranjang kosong.');
    }

    $menuIds = array_keys($cart);
    $menusCache = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

    $totalHarga = 0;
    foreach ($cart as $menuId => $item) {
        $menu = $menusCache->get($menuId);
        if (!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }
        $totalHarga += $menu->harga * $item['quantity'];
    }

    $member = null;
    if ($request->filled('member_id')) {
        $member = Member::find($request->member_id);
    }

    // Potongan dari poin jika pakai poin dan cukup poin
    $potongan = 0;
    $pointsUsed = 0;
    if ($member && $request->use_points && $member->points >= 10) {
        // Pakai kelipatan 10 poin
        $pointsUsed = floor($member->points / 10) * 10;
        $potongan = ($pointsUsed / 10) * 7500;
    }

    $totalBayar = max($totalHarga - $potongan, 0);

    if ($request->jumlah_bayar < $totalBayar) {
        return redirect()->back()->with('error', 'Jumlah bayar kurang.');
    }

    DB::transaction(function () use ($request, $cart, $menusCache, $totalBayar, $totalHarga, $potongan, $member, $pointsUsed, &$order) {
        $user = auth()->user();
        $namaKasir = $user->role === 'kasir' ? $request->nama_kasir : $user->name;

        $order = Order::create([
            'nama_pemesan' => $request->nama_pemesan,
            'jumlah_bayar' => $request->jumlah_bayar,
            'kembalian' => $request->jumlah_bayar - $totalBayar,
            'user_id' => $user->id,
            'nama_kasir' => $namaKasir,
            'total_harga' => $totalHarga,
            'potongan' => $potongan,
            'member_id' => $member?->id,
        ]);

        foreach ($cart as $menuId => $item) {
            $menu = $menusCache->get($menuId);
            $qty = $item['quantity'];

            DetailOrder::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'harga_menu' => $menu->harga,
                'jumlah' => $qty,
                'subtotal' => $menu->harga * $qty,
            ]);

            $menu->decrement('stok', $qty);
        }

        if ($member) {
            // Kurangi poin yang dipakai
            if ($pointsUsed > 0) {
                $member->decrement('points', $pointsUsed);
            }

            // Tambah poin baru sesuai total harga sebelum potongan
            $pointsEarned = floor($totalHarga / 3000);
            // Jika ingin minimal dapat 1 poin setiap order, gunakan:
            // $pointsEarned = max(1, floor($totalHarga / 3000));
            $member->increment('points', $pointsEarned);
        }

        $order->load('detailOrders');
    });

    session()->forget('cart');

    if ($order) {
        $kitchenNumber = \App\Models\Setting::where('key', 'wa_kitchen_number')->value('value');
        
        if (!empty($kitchenNumber)) {
            $message = "🔔 *PESANAN BARU DARI KASIR (CHECKOUT)*\n";
            $message .= "===============================\n";
            $message .= "No. Order: #{$order->id}\n";
            $message .= "Nama Pemesan: {$order->nama_pemesan}\n";
            $message .= "Kasir: {$order->nama_kasir}\n";
            $message .= "Waktu: " . now()->format('H:i:s, d M Y') . "\n";
            $message .= "===============================\n";
            $message .= "*RINCIAN MENU:*\n";

            if ($order->relationLoaded('detailOrders')) {
                foreach ($order->detailOrders as $detail) {
                    $message .= "➡️ {$detail->jumlah}x " . strtoupper($detail->nama_menu) . "\n";
                }
            }
            
            $message .= "===============================\n";
            $message .= "*SEGERA DIPROSES!*";

            $this->sendWaNotification($kitchenNumber, $message); 
        }
    }

    return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan dan stok diperbarui.');
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

private function sendWaNotification(string $number, string $message): bool
    {
        $apiKey = "T0xbzseNkWKsmfS8daKAOh8JiKrrj6hB"; 
        $sender = "6287731288218"; // Nomor Pengirim

        $raw = preg_replace('/\D+/', '', $number);
        if (str_starts_with($raw, '0')) {
            $raw = substr($raw, 1);
        }
        $e164 = '62' . $raw;
        
        if (strlen($e164) < 10) {
            Log::error('Invalid WA Kitchen number after normalization: ' . $number);
            return false;
        }

        try {
            $payload = [
                "api_key" => $apiKey,
                "sender"  => $sender,
                "number"  => $e164,
                "message" => $message,
            ];

            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'User-Agent'   => 'order-notification',
            ])
            ->timeout(20)
            ->post('http://premiumwa.sasweblabs.com/send-message', $payload);

            Log::info('Order WA Notification', ['status' => $res->status(), 'body' => $res->body(), 'number' => $number]);
            $json = $res->json();

            return $res->successful() && (!isset($json['status']) || $json['status'] === true);
        } catch (\Throwable $e) {
            Log::error('Order WA Notification exception: ' . $e->getMessage());
            return false;
        }

    }

    
}




