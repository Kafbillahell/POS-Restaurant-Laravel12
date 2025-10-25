<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PromoController extends Controller
{
    protected function checkRole()
    {
        $userRole = Auth::user()->role ?? 'guest';

        if (!in_array($userRole, ['admin', 'pemilik'])) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk mengelola Promo.');
        }
    }

    public function index()
    {
        $this->checkRole();

        $menus = Menu::with('kategori')->get()->sortBy(fn($menu) => $menu->kategori->nama_kategori ?? 'Tanpa Kategori');
        
        return view('promo.index', compact('menus'));
    }

    public function update(Request $request)
    {
        $this->checkRole();

        $validationRules = [
            'harga_promo.*' => 'nullable|integer|min:0',
            'durasi_hari.*' => 'nullable|integer|min:0',
            'durasi_jam.*' => 'nullable|integer|min:0|max:23',
            'durasi_menit.*' => 'nullable|integer|min:0|max:59',
        ];

        try {
            $request->validate($validationRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
             if (!$request->ajax() && !$request->wantsJson()) {
                 return back()->with('error', 'Terdapat kesalahan pada input durasi promo.')->withInput();
             }
             return response()->json(['error' => 'Data input tidak valid.'], 422);
        }


        $successCount = 0;
        foreach ($request->menu_id as $menuId) {
            $menu = Menu::find($menuId);
            if ($menu) {
                $hargaPromo = (int) ($request->harga_promo[$menuId] ?? 0);
                $durasiHari = (int) ($request->durasi_hari[$menuId] ?? 0);
                $durasiJam = (int) ($request->durasi_jam[$menuId] ?? 0);
                $durasiMenit = (int) ($request->durasi_menit[$menuId] ?? 0);
                
                $finalHargaPromo = null;
                $finalDurasiHari = 0;
                $finalDurasiJam = 0;
                $finalDurasiMenit = 0;
                $finalPromoStartAt = null;

                if ($hargaPromo > 0 && $hargaPromo >= $menu->harga && $menu->harga > 0) {
                    $errorMessage = "Harga promo untuk **{$menu->nama_menu}** harus lebih rendah dari harga normal (Rp" . number_format($menu->harga) . ")!";
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['error' => $errorMessage], 400);
                    }
                    return back()->with('error', $errorMessage)->withInput();
                }

                $isDiscountValid = ($hargaPromo > 0 && $hargaPromo < $menu->harga);
                $isDurationValid = ($durasiHari > 0 || $durasiJam > 0 || $durasiMenit > 0);

                if ($isDiscountValid && $isDurationValid) {
                    
                    $isNewPromo = is_null($menu->promo_start_at);
                    
                    $isDurationChanged = $durasiHari != ($menu->durasi_promo_hari ?? 0) || 
                                         $durasiJam != ($menu->durasi_promo_jam ?? 0) || 
                                         $durasiMenit != ($menu->durasi_promo_menit ?? 0);
                    $isDiscountChanged = $hargaPromo != ($menu->harga_promo ?? 0);
                    
                    if ($isNewPromo || $isDurationChanged || $isDiscountChanged) {
                        $finalPromoStartAt = Carbon::now();
                    } else {
                        $finalPromoStartAt = $menu->promo_start_at;
                    }

                    $finalHargaPromo = $hargaPromo;
                    $finalDurasiHari = $durasiHari;
                    $finalDurasiJam = $durasiJam;
                    $finalDurasiMenit = $durasiMenit;

                } else {
                    $finalHargaPromo = null;
                    $finalDurasiHari = 0;
                    $finalDurasiJam = 0;
                    $finalDurasiMenit = 0;
                    $finalPromoStartAt = null;
                }
                
                $menu->update([
                    'harga_promo' => $finalHargaPromo,
                    'stok_promo' => null, 
                    'durasi_promo_hari' => $finalDurasiHari, 
                    'durasi_promo_jam' => $finalDurasiJam,
                    'durasi_promo_menit' => $finalDurasiMenit,
                    'promo_start_at' => $finalPromoStartAt,
                ]);

                $successCount++;
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => "Promo Diterapkan! {$successCount} menu berhasil diperbarui."]);
        }

        return redirect()->route('promo.index')->with('success', "Pengaturan Promo menu berhasil diperbarui!");
    }
}