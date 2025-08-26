@extends('layouts.dashboard')

@section('content')
<style>
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .btn, .filter-form { display: none; }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Laporan Kunjungan Anggota</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <!-- Formulir Filter -->
    <div class="card mb-4 filter-form">
        <div class="card-body">
            <form action="{{ route('laporan.kunjungan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporan.kunjungan') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" id="print-area">
        <div class="card-header text-center">
            <h4 class="mb-0">Laporan Kunjungan Anggota</h4>
            <p class="mb-0">Perpustakaan App</p>
            <small>Periode: {{ request('tanggal_mulai', 'Semua') }} s/d {{ request('tanggal_akhir', 'Semua') }}</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Anggota</th>
                            <th>Kelas</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kunjungans as $kunjungan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kunjungan->anggota->nama }}</td>
                            <td>{{ $kunjungan->anggota->kelas }} - {{ $kunjungan->anggota->jurusan }}</td>
                            <td>{{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('d M Y, H:i') }}</td>
                            <td>{{ $kunjungan->waktu_keluar ? \Carbon\Carbon::parse($kunjungan->waktu_keluar)->format('d M Y, H:i') : 'Masih di dalam' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data kunjungan yang cocok dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
