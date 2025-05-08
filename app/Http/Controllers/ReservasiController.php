<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::with('member')->latest()->get();
        return view('reservasis.index', compact('reservasis'));
    }

    public function create()
    {
        $members = Member::all();
        return view('reservasis.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'jumlah_orang' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ]);
    
        Reservasi::create([
            'member_id' => $request->member_id,
            'tanggal_reservasi' => Carbon::now(), // otomatis pakai waktu saat ini
            'jumlah_orang' => $request->jumlah_orang,
            'catatan' => $request->catatan,
        ]);
    
        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil ditambahkan.');
    }

    public function edit(Reservasi $reservasi)
    {
        $members = Member::all();
        return view('reservasis.edit', compact('reservasi', 'members'));
    }

    public function update(Request $request, Reservasi $reservasi)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'tanggal_reservasi' => 'required|date',
            'jumlah_orang' => 'required|integer|min:1',
            'catatan' => 'nullable|string'
        ]);

        $reservasi->update($request->all());
        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();
        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil dihapus.');
    }
}
