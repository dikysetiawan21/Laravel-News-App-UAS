@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Berita Terkini</h1>

    <div class="row">
        @forelse ($publishedNews as $item)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="Gambar Berita" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('img/no-image.png') }}" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;"> {{-- Pastikan Anda punya gambar no-image.png di public/img --}}
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $item->title }}</h5>
                        <p class="card-text text-muted small mb-2">
                            <i class="fas fa-tag"></i> {{ $item->category->name }} &bull;
                            <i class="fas fa-user"></i> {{ $item->user->name }} &bull;
                            <i class="fas fa-calendar-alt"></i> {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}
                        </p>
                        <p class="card-text">{{ Str::limit(strip_tags($item->content), 100, '...') }}</p> {{-- Potong konten dan hapus tag HTML --}}
                        <a href="{{ route('news.show', $item->slug) }}" class="btn btn-info btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">Tidak ada berita yang diterbitkan saat ini.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $publishedNews->links() }}
    </div>
@endsection