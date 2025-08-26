@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Kelola Buku</h1>
        <a href="{{ route('buku.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-2"></i>
            Tambah Buku
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th width="280px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukus as $buku)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td>{{ $buku->penerbit }}</td>
                            <td>{{ $buku->tahun_terbit }}</td>
                            <td>{{ $buku->jumlah_stok }}</td>
                            <td>
                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('buku.edit', $buku->id) }}">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $bukus->links() !!}
        </div>
    </div>
</div>
@endsection
