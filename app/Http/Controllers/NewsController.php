<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category; // Import Model Category
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Untuk menghandle file storage

class NewsController extends Controller
{
    // ... (metode index, create, store, show, edit, update, destroy yang sudah ada) ...

    /**
     * Display a listing of published news for public view.
     * Bisa diakses oleh semua user yang login.
     */
    public function publicIndex()
    {
        $publishedNews = News::where('status', 'published')
                            ->with('user', 'category')
                            ->latest('published_at') // Urutkan berdasarkan tanggal publish terbaru
                            ->paginate(10);

        return view('news.public', compact('publishedNews'));
    }

    /**
     * Display a listing of the resource.
     * Hanya menampilkan berita yang dibuat oleh wartawan/admin yang login
     */
    public function index()
    {
        // Ambil berita sesuai role: Admin lihat semua, Wartawan lihat beritanya sendiri
        if (Auth::user()->role === 'admin') {
            $news = News::with('user', 'category')->latest()->paginate(10);
        } else { // Jika wartawan
            $news = Auth::user()->news()->with('category')->latest()->paginate(10);
        }

        return view('news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        return view('news.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'content' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan gambar ke storage/app/public/news_images
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        News::create([
            'user_id' => Auth::id(), // ID user yang sedang login
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title), // Otomatis dibuat oleh mutator di model
            'image' => $imagePath,
            'content' => $request->content,
            'status' => 'draft', // Default status adalah draft
        ]);

        return redirect()->route('news.index')->with('success', 'Berita berhasil dibuat dan berstatus DRAFT. Menunggu persetujuan editor.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        // Untuk melihat detail berita. Bisa untuk halaman publik nanti.
        // Untuk saat ini, kita akan redirect ke halaman edit jika yang melihat adalah pengirimnya atau admin.
        // Atau buat view terpisah untuk show.
        return view('news.show', compact('news')); // Kita akan buat view show.blade.php
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        // Pastikan hanya pengirim (wartawan/admin) atau admin yang bisa mengedit
        if (Auth::user()->role !== 'admin' && $news->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit berita ini.');
        }

        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        // Pastikan hanya pengirim (wartawan/admin) atau admin yang bisa mengedit
        if (Auth::user()->role !== 'admin' && $news->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate berita ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required|string',
        ]);

        $imagePath = $news->image; // Pertahankan gambar lama jika tidak ada upload baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $news->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title), // Otomatis dibuat oleh mutator di model
            'image' => $imagePath,
            'content' => $request->content,
            // Status tidak diubah di sini, hanya editor yang bisa mengubah status ke published
        ]);

        return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        // Hanya pengirim (wartawan/admin) atau admin yang bisa menghapus
        if (Auth::user()->role !== 'admin' && $news->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus berita ini.');
        }

        // Hapus gambar terkait jika ada
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();
        return redirect()->route('news.index')->with('success', 'Berita berhasil dihapus.');
    }
}