<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // Jika ingin membuat controller profile
use App\Http\Controllers\DashboardController; // Contoh controller dashboard
use App\Http\Controllers\CategoryController; // Import CategoryController
use App\Http\Controllers\NewsController; // Import NewsController
use App\Http\Controllers\ApprovalController; // Import ApprovalController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================== ROUTE GUEST (TANPA LOGIN) ==================
// ================== ROUTE GUEST (TANPA LOGIN) ==================
Route::get('/', function () {
    return view('welcome');
});

// ================== ROUTE SOCIALITE GOOGLE ===================
Route::get('/auth/google/redirect', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback']);

// ================== ROUTE AUTENTIKASI BREEZE ===================
require __DIR__.'/auth.php';

// ================== ROUTE KHUSUS YANG SUDAH LOGIN =============
Route::middleware(['auth'])->group(function () {
    // Route untuk Berita Publik (semua user login)
    Route::get('/public-news', [NewsController::class, 'publicIndex'])->name('public.news');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kategori (hanya admin)
    Route::middleware('can:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // Berita (admin & wartawan, kecuali show)
    Route::middleware('can:create-news')->group(function () {
        Route::resource('news', NewsController::class)->except(['show']);
    });

    // Show berita detail (semua user login)
    Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
});


// --- ROUTE YANG DILINDUNGI BERDASARKAN ROLE ---

// Route untuk Manajemen User (Hanya Admin)
use App\Http\Controllers\UserController;
Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'store']); // Tidak perlu create/store karena user daftar sendiri
});

// Route untuk Approval Berita (Admin dan Editor)
Route::middleware(['auth', 'can:approve-news'])->group(function () {
    Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('/approval/{news}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{news}/unpublish', [ApprovalController::class, 'unpublish'])->name('approval.unpublish');
});





// Route::get('/debug-user', function() {
//     return Auth::user();
// });

// Routes autentikasi dari Breeze (jangan diubah)
require __DIR__.'/auth.php';

// // Route debug session dan user
// Route::get('/test-approval', function() {
//     return 'OK '.json_encode(Auth::user());
// });