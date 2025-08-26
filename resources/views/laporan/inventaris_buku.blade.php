@extends('layouts.dashboard')

@section('content')
<style>
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .btn, .info-cards { display: none; }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Laporan Inventaris Buku</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <!-- Kartu Informasi -->
    <div class="row info-cards">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Judul Buku</h5>
                            <p class="card-text fs-2 fw-bold">{{ $totalJudul }}</p>
                        </div>
                        <i class="bi bi-journal-bookmark-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Eksemplar Buku</h5>
                            <p class="card-text fs-2 fw-bold">{{ $totalEksemplar }}</p>
                        </div>
                        <i class="bi bi-stack fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="print-area">
        <div class="card-header text-center">
            <h4 class="mb-0">Laporan Inventaris Buku</h4>
            <p class="mb-0">Perpustakaan App</p>
        </div>
        <div class="card-body">
            <h5 class="mb-3">Daftar Buku dengan Stok Menipis (Kurang dari 5)</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th class="text-center">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuStokMenipis as $buku)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td class="text-center fw-bold text-danger">{{ $buku->jumlah_stok }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada buku dengan stok menipis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
