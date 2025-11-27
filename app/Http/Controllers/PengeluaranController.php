<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar semua pengeluaran dan form input.
     * Digunakan sebagai halaman utama (READ & CREATE Form).
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua data pengeluaran, diurutkan berdasarkan tanggal terbaru
        $pengeluarans = Pengeluaran::orderBy('tanggal', 'desc')->get();

        // Mengirim data ke view
        return view('pengeluaran.index', compact('pengeluarans'));
    }

    /**
     * Menyimpan data pengeluaran baru ke database (CREATE).
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            // Jumlah harus angka, minimal 100 (misalnya)
            'jumlah' => 'required|numeric|min:0', 
        ], [
            'tanggal.required' => 'Tanggal pengeluaran wajib diisi.',
            'deskripsi.required' => 'Deskripsi pengeluaran wajib diisi.',
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah pengeluaran minimal Rp 0.',
        ]);

        // 2. Simpan ke database
        Pengeluaran::create($request->all());

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('pengeluaran.index')
                         ->with('success', '✅ Pengeluaran berhasil dicatat!');
    }

    /**
     * Tidak digunakan untuk mengembalikan view karena CRUD dilakukan di index.
     * Namun, jika Anda ingin menggunakan ini untuk mengembalikan data JSON (misalnya untuk AJAX), Anda bisa melakukannya.
     */
    public function edit(Pengeluaran $pengeluaran)
    {
        // Dalam skenario CRUD satu halaman, fungsi ini seringkali tidak diperlukan
        // karena data diambil dan diisi oleh JavaScript di halaman index.
        return response()->json($pengeluaran);
    }

    /**
     * Memperbarui data pengeluaran di database (UPDATE).
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        // 1. Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0', 
        ], [
            'tanggal.required' => 'Tanggal pengeluaran wajib diisi.',
            'deskripsi.required' => 'Deskripsi pengeluaran wajib diisi.',
            'jumlah.required' => 'Jumlah pengeluaran wajib diisi.',
        ]);

        // 2. Perbarui data
        $pengeluaran->update($request->all());

        // 3. Redirect kembali ke halaman index
        return redirect()->route('pengeluaran.index')
                         ->with('success', '🔁 Pengeluaran berhasil diperbarui!');
    }

    /**
     * Menghapus data pengeluaran dari database (DELETE).
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        
        // Redirect kembali ke halaman index
        return redirect()->route('pengeluaran.index')
                         ->with('success', '🗑️ Pengeluaran berhasil dihapus.');
    }
}