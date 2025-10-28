<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:admin,kasir,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ];

        if ($user->role === 'admin') {
            $rules['role'] = 'required|in:admin';
        } else {
            $rules['role'] = 'required|in:admin,kasir';
        }

        $validatedData = $request->validate($rules);
        $roleUpdateBlocked = false;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($user->role === 'admin') {
            if ($validatedData['role'] !== 'admin') {
                $roleUpdateBlocked = true;
            }

        } else {
            $user->role = $validatedData['role'];
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($roleUpdateBlocked) {
            $message = 'Data pengguna berhasil diperbarui, namun **Role admin tidak dapat diubah** ke role lain.';
            return redirect()->route('users.index')->with('warning', $message);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}