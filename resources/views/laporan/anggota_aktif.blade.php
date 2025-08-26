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
        <h1 class="h3">Laporan Anggota Teraktif</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <div id="print-area">
        <div class="row">
            <!-- Kolom Kiri: Teraktif Meminjam -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">10 Anggota Teraktif (Berdasarkan Peminjaman)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Anggota</th>
                                        <th>Kelas</th>
                                        <th class="text-center">Total Pinjam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($aktifPinjam as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>{{ $anggota->kelas }} - {{ $anggota->jurusan }}</td>
                                        <td class="text-center">{{ $anggota->total_peminjaman }} kali</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data peminjaman.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Teraktif Berkunjung -->
            <div class="col-lg-6">
                 <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0">10 Anggota Teraktif (Berdasarkan Kunjungan)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Anggota</th>
                                        <th>Kelas</th>
                                        <th class="text-center">Total Kunjung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($aktifKunjung as $anggota)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $anggota->nama }}</td>
                                        <td>{{ $anggota->kelas }} - {{ $anggota->jurusan }}</td>
                                        <td class="text-center">{{ $anggota->total_kunjungan }} kali</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data kunjungan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
