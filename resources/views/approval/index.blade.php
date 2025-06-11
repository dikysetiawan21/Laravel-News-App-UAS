@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Manajemen Approval Berita</h1>

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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Berita Menunggu Persetujuan</h6>
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
                        @forelse ($pendingNews as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td><span class="badge badge-warning">{{ ucfirst($item->status) }}</span></td>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('news.show', $item->slug) }}" class="btn btn-info btn-sm mb-1">Lihat Detail</a>
                                    @if($item->status == 'draft')
                                        <form action="{{ route('approval.approve', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm mb-1" onclick="return confirm('Setujui berita ini?')">Setujui</button>
                                        </form>
                                    @else {{-- Jika statusnya published, beri opsi unpublish --}}
                                        <form action="{{ route('approval.unpublish', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm mb-1" onclick="return confirm('Tarik kembali berita ini (ubah ke Draft)?')">Tarik (Draft)</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada berita yang menunggu persetujuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $pendingNews->links() }}
            </div>
        </div>
    </div>
@endsection