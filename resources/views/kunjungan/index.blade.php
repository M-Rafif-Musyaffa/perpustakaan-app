@extends('layouts.dashboard')

@section('content')
<style>
    /* Style untuk kontainer rekomendasi */
    .autocomplete-container {
        position: relative;
    }
    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        top: 100%;
        left: 0;
        right: 0;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 0 0 .25rem .25rem;
    }
    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }
    .autocomplete-items div:hover {
        background-color: #e9e9e9;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-3">Buku Kunjungan Perpustakaan</h1>

    {{-- Notifikasi --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form Check-in --}}
    <div class="card mb-4">
        <div class="card-header">
            Catat Kunjungan Baru
        </div>
        <div class="card-body">
            <form action="{{ route('kunjungan.check-in') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-10">
                        <label for="nis" class="form-label">Masukkan NIS atau Nama Anggota</label>
                        {{-- UBAH DISINI: Tambahkan div wrapper untuk autocomplete --}}
                        <div class="autocomplete-container">
                            <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" placeholder="Ketik NIS atau Nama untuk mencari..." required autocomplete="off">
                            <div class="autocomplete-items" id="nis-list"></div>
                        </div>
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Check-in</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Kunjungan Hari Ini --}}
    <div class="card">
        <div class="card-header">Daftar Pengunjung Hari Ini</div>
        <div class="card-body">
            {{-- ... (kode tabel tidak berubah) ... --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Anggota</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kunjungans as $kunjungan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kunjungan->anggota->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('H:i:s') }}</td>
                            <td>{{ $kunjungan->waktu_keluar ? \Carbon\Carbon::parse($kunjungan->waktu_keluar)->format('H:i:s') : '-' }}</td>
                            <td>
                                @if ($kunjungan->waktu_keluar)
                                    <span class="badge bg-secondary">Sudah Keluar</span>
                                @else
                                    <span class="badge bg-success">Di Dalam</span>
                                @endif
                            </td>
                            <td>
                                @if (!$kunjungan->waktu_keluar)
                                    <form action="{{ route('kunjungan.check-out', $kunjungan->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-warning">Check-out</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada pengunjung hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $kunjungans->links() !!}
        </div>
    </div>
</div>

{{-- UBAH DISINI: Tambahkan script untuk autocomplete --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nisInput = document.getElementById('nis');
        const nisList = document.getElementById('nis-list');

        nisInput.addEventListener('keyup', function() {
            const query = nisInput.value;

            if (query.length < 2) {
                nisList.innerHTML = '';
                return;
            }

            fetch(`{{ route('anggota.search') }}?term=${query}`)
                .then(response => response.json())
                .then(data => {
                    nisList.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.innerHTML = `<strong>${item.nis}</strong> - ${item.nama}`;
                        div.addEventListener('click', function() {
                            nisInput.value = item.nis;
                            nisList.innerHTML = '';
                        });
                        nisList.appendChild(div);
                    });
                });
        });

        // Sembunyikan rekomendasi jika klik di luar
        document.addEventListener('click', function(e) {
            if (e.target.id !== 'nis') {
                nisList.innerHTML = '';
            }
        });
    });
</script>
@endsection
