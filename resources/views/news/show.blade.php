@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Detail Berita</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ $news->title }}</h6>
            <div>
                <a href="{{ route('news.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                @if(Auth::user()->role == 'admin' || (Auth::user()->role == 'wartawan' && $news->user_id == Auth::id()))
                    <a href="{{ route('news.edit', $news->slug) }}" class="btn btn-warning btn-sm">Edit</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <p><strong>Kategori:</strong> {{ $news->category->name }}</p>
            <p><strong>Pengirim:</strong> {{ $news->user->name }}</p>
            <p><strong>Status:</strong> <span class="badge {{ $news->status == 'draft' ? 'badge-warning' : 'badge-success' }}">{{ ucfirst($news->status) }}</span></p>
            <p><strong>Tanggal Dibuat:</strong> {{ $news->created_at ? \Carbon\Carbon::parse($news->created_at)->format('d M Y H:i') : '-' }}</p>
            @if ($news->published_at)
                <p><strong>Tanggal Publish:</strong> {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('d M Y H:i') : '-' }}</p>
            @endif

            @if ($news->image)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar Berita" class="img-fluid" style="max-height: 400px; object-fit: contain;">
                </div>
            @endif

            <hr>
            <div>
                {!! $news->content !!} {{-- Gunakan {!! !!} jika konten mengandung HTML --}}
            </div>
        </div>
    </div>
@endsection