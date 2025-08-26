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
        <h1 class="h3">Laporan Data Buku</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <!-- Formulir Filter -->
    <div class="card mb-4 filter-form">
        <div class="card-body">
            <form action="{{ route('laporan.buku') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="penulis" class="form-label">Filter Penulis</label>
                    <select class="form-select" id="penulis" name="penulis">
                        <option value="">Semua Penulis</option>
                        @foreach($penulisList as $penulis)
                            <option value="{{ $penulis }}" {{ request('penulis') == $penulis ? 'selected' : '' }}>{{ $penulis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="penerbit" class="form-label">Filter Penerbit</label>
                    <select class="form-select" id="penerbit" name="penerbit">
                        <option value="">Semua Penerbit</option>
                         @foreach($penerbitList as $penerbit)
                            <option value="{{ $penerbit }}" {{ request('penerbit') == $penerbit ? 'selected' : '' }}>{{ $penerbit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="kategori" class="form-label">Filter Kategori</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                         @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- UBAH DISINI: Tambahkan filter tahun -->
                <div class="col-md-2">
                    <label for="tahun_terbit" class="form-label">Filter Tahun</label>
                    <select class="form-select" id="tahun_terbit" name="tahun_terbit">
                        <option value="">Semua Tahun</option>
                         @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun_terbit') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('laporan.buku') }}" class="btn btn-secondary w-100 mt-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" id="print-area">
        <div class="card-header text-center">
            <h4 class="mb-0">Laporan Data Buku</h4>
            <p class="mb-0">Perpustakaan App</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Stok</th>
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
                            <td>{{ $buku->kategori ?? '-' }}</td>
                            <td>{{ $buku->jumlah_stok }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data buku yang cocok dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
