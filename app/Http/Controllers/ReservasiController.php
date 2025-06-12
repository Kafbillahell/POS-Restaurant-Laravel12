<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Member;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservasis = Reservasi::with('member')->latest()->paginate(10);
        return view('reservasis.index', compact('reservasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        return view('reservasis.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'nama_pemesan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'tanggal_reservasi' => 'required|date',
            'jumlah_orang' => 'required|integer|min:1',
            'down_payment' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        Reservasi::create($validated);

        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservasi $reservasi)
    {
        return view('reservasis.show', compact('reservasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservasi $reservasi)
    {
        $members = Member::all();
        return view('reservasis.edit', compact('reservasi', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'nama_pemesan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'tanggal_reservasi' => 'required|date',
            'jumlah_orang' => 'required|integer|min:1',
            'down_payment' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $reservasi->update($validated);

        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();

        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil dihapus.');
    }
}
