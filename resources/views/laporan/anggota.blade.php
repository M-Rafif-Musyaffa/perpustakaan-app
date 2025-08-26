@extends('layouts.dashboard')

@section('content')
<style>
    /* Sembunyikan elemen yang tidak perlu saat mencetak */
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn, .filter-form { /* Sembunyikan tombol dan form filter saat cetak */
            display: none;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Laporan Data Anggota</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer-fill me-2"></i>
            Cetak Laporan
        </button>
    </div>

    <!-- Formulir Filter -->
    <div class="card mb-4 filter-form">
        <div class="card-body">
            <form action="{{ route('laporan.anggota') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="kelas" class="form-label">Filter Berdasarkan Kelas</label>
                    <select class="form-select" id="kelas" name="kelas">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="jurusan" class="form-label">Filter Berdasarkan Jurusan</label>
                    <select class="form-select" id="jurusan" name="jurusan">
                        <option value="">Semua Jurusan</option>
                         @foreach($jurusanList as $jurusan)
                            <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporan.anggota') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" id="print-area">
        <div class="card-header text-center">
            <h4 class="mb-0">Laporan Data Anggota</h4>
            <p class="mb-0">Perpustakaan App</p>
            <small>Kelas: {{ request('kelas', 'Semua') }} | Jurusan: {{ request('jurusan', 'Semua') }}</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>No. Telepon</th>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data anggota yang cocok dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
