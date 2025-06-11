@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Manajemen Berita</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Berita Anda / Semua Berita</h6>
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'wartawan')
            <a href="{{ route('news.create') }}" class="btn btn-primary btn-sm">Tambah Berita</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Pengirim</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>
                                    <span class="badge {{ $item->status == 'draft' ? 'badge-warning' : 'badge-success' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('news.show', $item->slug) }}" class="btn btn-info btn-sm">Lihat</a>
                                    @if(Auth::user()->role == 'admin' || (Auth::user()->role == 'wartawan' && $item->user_id == Auth::id()))
                                        <a href="{{ route('news.edit', $item->slug) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('news.destroy', $item->slug) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada berita.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $news->links() }} {{-- Untuk pagination --}}
            </div>
        </div>
    </div>
@endsection