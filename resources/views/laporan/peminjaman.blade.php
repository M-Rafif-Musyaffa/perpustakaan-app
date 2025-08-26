@extends('layouts.dashboard')

@section('content')
<style>
    @media print {
        body * { visibility: hidden; }
        #print-area, #print-area * { visibility: visible; }
        #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .btn, .filter-form, .info-card { display: none; } /* Sembunyikan elemen tambahan saat cetak */
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Laporan Peminjaman Buku</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <!-- UBAH DISINI: Tambahkan Kartu Informasi -->
    <div class="card bg-light-warning text-dark mb-4 info-card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-2 me-3 text-warning"></i>
                <div>
                    <h5 class="card-title mb-0">Informasi</h5>
                    <p class="card-text mb-0">
                        Total buku yang saat ini berstatus <strong>Belum Kembali / Terlambat</strong> (berdasarkan filter) adalah: <strong>{{ $totalBelumKembali }} eksemplar</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulir Filter -->
    <div class="card mb-4 filter-form">
        <div class="card-body">
            <form action="{{ route('laporan.peminjaman') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Belum Kembali</option>
                        <option value="Kembali" {{ request('status') == 'Kembali' ? 'selected' : '' }}>Sudah Kembali</option>
                        <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporan.peminjaman') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" id="print-area">
        <div class="card-header text-center">
            <h4 class="mb-0">Laporan Peminjaman Buku</h4>
            <p class="mb-0">Perpustakaan App</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Peminjam</th>
                            <th>Buku Dipinjam</th>
                            <th>Tgl. Pinjam</th>
                            <th>Tgl. Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $peminjaman)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $peminjaman->anggota->nama }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($peminjaman->detailPeminjaman as $detail)
                                        <li>- {{ $detail->buku->judul }} (x{{ $detail->jumlah }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                            <td>
                                @if ($peminjaman->status == 'Kembali')
                                    <span class="badge bg-success">Kembali</span>
                                @elseif (\Carbon\Carbon::parse($peminjaman->tanggal_kembali)->isPast())
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data peminjaman yang cocok dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
