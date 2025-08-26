@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Kelola Anggota</h1>
        <a href="{{ route('anggota.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-2"></i>
            Tambah Anggota
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
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>No. Telepon</th>
                            <th width="280px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $anggota)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $anggota->nis }}</td>
                            <td>{{ $anggota->nama }}</td>
                            <td>{{ $anggota->kelas }}</td>
                            <td>{{ $anggota->jurusan }}</td>
                            <td>{{ $anggota->no_telp ?? '-' }}</td>
                            <td>
                                <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('anggota.edit', $anggota->id) }}">
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
                            <td colspan="7" class="text-center">Belum ada data anggota.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $anggotas->links() !!}
        </div>
    </div>
</div>
@endsection
