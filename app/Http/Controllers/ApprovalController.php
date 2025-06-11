<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Display a listing of news awaiting approval.
     * Hanya untuk Admin dan Editor.
     */
    public function index()
    {
        // Hanya tampilkan berita dengan status 'draft'
        $pendingNews = News::where('status', 'draft')
                           ->with('user', 'category') // Ambil data user dan kategori juga
                           ->latest()
                           ->paginate(10);

        return view('approval.index', compact('pendingNews'));
    }

    /**
     * Approve a specific news item (change status to 'published').
     */
    public function approve($news)
    {
        $news = News::findOrFail($news);
        // Pastikan hanya admin atau editor yang bisa melakukan approval
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'editor') {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui berita.');
        }

        // Pastikan berita belum published
        if ($news->status === 'draft') {
            $news->update([
                'status' => 'published',
                'published_at' => now(), // Set tanggal publish ke waktu sekarang
            ]);
            return redirect()->route('approval.index')->with('success', 'Berita "' . $news->title . '" berhasil disetujui dan diterbitkan!');
        }

        return redirect()->route('approval.index')->with('error', 'Berita sudah diterbitkan atau statusnya tidak valid.');
    }

    /**
     * Reject/Unpublish a specific news item (change status to 'draft' or mark as rejected).
     * Opsional: Anda bisa membuat status 'rejected' terpisah jika diperlukan.
     * Untuk UAS, kita akan ubah kembali ke 'draft' atau biarkan di 'draft'.
     * Atau kita buat ini untuk unpublish dari 'published' ke 'draft'.
     */
    public function unpublish($news)
    {
        $news = News::findOrFail($news);
        // Pastikan hanya admin atau editor yang bisa melakukan unpublish
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'editor') {
            abort(403, 'Anda tidak memiliki akses untuk menarik berita.');
        }

        // Hanya unpublish jika statusnya published
        if ($news->status === 'published') {
            $news->update([
                'status' => 'draft',
                'published_at' => null, // Kosongkan tanggal publish jika di-unpublish
            ]);
            return redirect()->route('approval.index')->with('success', 'Berita "' . $news->title . '" berhasil ditarik dan kembali menjadi DRAFT.');
        }

        return redirect()->route('approval.index')->with('error', 'Berita belum diterbitkan.');
    }


    // Optional: Metode untuk melihat detail berita pending (jika diperlukan)
    // public function show(News $news)
    // {
    //     if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'editor') {
    //         abort(403, 'Anda tidak memiliki akses untuk melihat detail berita ini.');
    //     }
    //     return view('approval.show', compact('news'));
    // }
}