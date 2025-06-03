<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function create(Request $request)
    {
        // Simpan URL sebelumnya jika bukan halaman ini sendiri
        if (!$request->session()->has('previous_url') && url()->previous() !== url()->current()) {
            $request->session()->put('previous_url', url()->previous());
        }

        $user = Auth::user();
        $profilePhoto = $user->profilePhoto; // Ambil data foto dan filter yang tersimpan

        // Kirim data foto dan filter ke view
        return view('profile_photo.create', [
            'profilePhoto' => $profilePhoto,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $path = null;

        // Validasi tambahan untuk filter
        $request->validate([
            'filter' => 'nullable|string',
        ]);

        $filter = $request->input('filter', 'none');

        if ($request->hasFile('photo')) {
            // Validasi file upload biasa
            $request->validate([
                'photo' => 'required|image|max:2048', // max 2MB
            ]);

            // Upload file ke storage/app/public/profile_photos
            $path = $request->file('photo')->store('profile_photos', 'public');

        } elseif ($request->filled('photo_data')) {
            // Proses base64 image dari hasil crop
            $photoData = $request->input('photo_data');

            if (preg_match('/^data:image\/(\w+);base64,/', $photoData, $matches)) {
                $type = strtolower($matches[1]); // jpg, png, gif dll

                if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    return back()->withErrors(['photo' => 'Format gambar tidak didukung']);
                }

                $photoData = substr($photoData, strpos($photoData, ',') + 1);
                $photoData = base64_decode($photoData);

                if ($photoData === false) {
                    return back()->withErrors(['photo' => 'Gagal decode gambar']);
                }

                $fileName = 'profile_photos/' . uniqid('profile_', true) . '.' . $type;
                Storage::disk('public')->put($fileName, $photoData);

                $path = $fileName;
            } else {
                return back()->withErrors(['photo' => 'Format gambar tidak valid']);
            }
        } else {
            return back()->withErrors(['photo' => 'Foto harus diupload']);
        }

        // Simpan atau update foto profil + filter
        ProfilePhoto::updateOrCreate(
            ['user_id' => $user->id],
            [
                'photo_path' => $path,
                'filter' => $filter,
            ]
        );

        return redirect()->route('profile_photo.create')
                         ->with('success', 'Foto profil berhasil diupload');
    }
}
