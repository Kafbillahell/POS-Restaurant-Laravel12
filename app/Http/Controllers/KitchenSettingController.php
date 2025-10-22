<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class KitchenSettingController extends Controller
{
    const WA_KEY = 'wa_kitchen_number';

    private function checkAdminRole()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('no-access');
        }
        return null;
    }

    public function index()
    {

        if ($redirect = $this->checkAdminRole()) {
            return $redirect;
        }

        $setting = Setting::firstOrCreate(
            ['key' => self::WA_KEY],
            ['value' => '']
        );

        return view('settings.kitchen', compact('setting'));
    }

    public function update(Request $request)
    {
        
        if ($redirect = $this->checkAdminRole()) {
            return $redirect;
        }

        $request->validate([
            'wa_kitchen_number' => 'required|string|min:8|max:20',
        ]);

        $normalizedNumber = preg_replace('/\D+/', '', $request->wa_kitchen_number);

        Setting::updateOrCreate(
            ['key' => self::WA_KEY],
            ['value' => $normalizedNumber]
        );

        return redirect()->route('settings.kitchen.index')->with('success', 'Nomor WhatsApp Kitchen berhasil diperbarui.');
    }
}