@extends('layouts.dashboard')

@section('content')
<style>
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .btn { display: none; }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Laporan Buku Paling Sering Dipinjam</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <div class="card" id="print-area">
        <div class="card-header text-center">
            <h4 class="mb-0">20 Judul Buku Terpopuler</h4>
            <p class="mb-0">Perpustakaan App</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Peringkat</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th class="text-center">Total Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukus as $buku)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td class="text-center">{{ $buku->total_dipinjam }} kali</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data peminjaman buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
