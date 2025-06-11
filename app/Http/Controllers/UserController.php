<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi unique email kecuali diri sendiri

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        $users = User::latest()->paginate(10); // Ambil semua user, dengan pagination
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Tidak diperlukan karena user mendaftar via register/Google.
     */
    public function create()
    {
        abort(404); // Atau redirect ke halaman lain
    }

    /**
     * Store a newly created resource in storage.
     * Tidak diperlukan karena user mendaftar via register/Google.
     */
    public function store(Request $request)
    {
        abort(404); // Atau redirect ke halaman lain
    }

    /**
     * Display the specified resource.
     * Bisa diabaikan atau arahkan ke halaman edit.
     */
    public function show(User $user)
    {
        return redirect()->route('users.edit', $user->id); // Arahkan ke edit
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'editor', 'wartawan', 'user']; // Daftar role yang tersedia
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     * Mengupdate informasi user (terutama role).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Email harus unik kecuali untuk user itu sendiri
            ],
            'role' => ['required', 'string', Rule::in(['admin', 'editor', 'wartawan', 'user'])], // Validasi role
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus user.
     */
    public function destroy(User $user)
    {
        // Pastikan admin tidak bisa menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Opsional: Hapus berita/kategori yang terkait dengan user ini
        // Karena kita sudah pakai onDelete('cascade') di migrasi berita, ini akan otomatis
        // Hapus user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}