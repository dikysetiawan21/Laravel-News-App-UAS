<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Untuk membuat password acak

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Jika user sudah ada, update avatar jika diperlukan dan login
                // Anda bisa tambahkan kolom google_id di tabel users jika ingin mengaitkan
                $user->update([
                    'google_id' => $googleUser->getId(), // Tambahkan kolom google_id di tabel users
                    // 'avatar' => $googleUser->getAvatar(), // Jika ingin menyimpan avatar
                ]);
                Auth::login($user);
                return redirect('/dashboard');
            } else {
                // Jika user belum ada, buat user baru
                // Pastikan Anda menangani kasus jika nama tidak tersedia atau email sudah terdaftar tapi tanpa google_id
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(16)), // Buat password acak yang kuat
                    'role' => 'user', // Default role untuk user yang mendaftar via Google
                    // 'email_verified_at' => now(), // Opsional: anggap email sudah terverifikasi
                ]);

                Auth::login($newUser);
                return redirect('/dashboard');
            }

        } catch (\Exception $e) {
            // Tangani error, misal: user membatalkan login, atau masalah koneksi
            return redirect('/login')->with('error', 'Login dengan Google gagal. Silakan coba lagi. ' . $e->getMessage());
        }
    }
}
