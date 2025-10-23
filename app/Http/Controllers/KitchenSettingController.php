<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class KitchenSettingController extends Controller
{
    const TELEGRAM_CHAT_ID = 'telegram_kitchen_chat_id';

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
            ['key' => self::TELEGRAM_CHAT_ID],
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
            'telegram_kitchen_chat_id' => 'required|string|min:4|max:50',
        ]);

        Setting::updateOrCreate(
            ['key' => self::TELEGRAM_CHAT_ID],
            ['value' => $request->telegram_kitchen_chat_id] 
        );

        return redirect()->route('settings.kitchen.index')->with('success', 'Chat ID Telegram Kitchen berhasil diperbarui.');
    }
}