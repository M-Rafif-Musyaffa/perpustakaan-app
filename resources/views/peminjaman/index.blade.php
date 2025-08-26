@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Daftar Peminjaman</h1>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-2"></i>
            Buat Peminjaman Baru
        </a>
    </div>

    <!-- Tombol Filter -->
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ route('peminjaman.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} me-2">Semua</a>
        <a href="{{ route('peminjaman.index', ['status' => 'Dipinjam']) }}" class="btn {{ request('status') == 'Dipinjam' ? 'btn-primary' : 'btn-outline-primary' }} me-2">Belum Kembali</a>
        <a href="{{ route('peminjaman.index', ['status' => 'Kembali']) }}" class="btn {{ request('status') == 'Kembali' ? 'btn-primary' : 'btn-outline-primary' }}">Sudah Kembali</a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type-="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Peminjam</th>
                            <th>Buku Dipinjam</th>
                            <th>Tgl. Pinjam</th>
                            <th>Tgl. Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $peminjaman)
                        <tr>
                            <td>{{ ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + $loop->iteration }}</td>
                            <td>
                                <!-- UBAH DISINI: Jadikan nama sebagai pemicu modal -->
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#anggotaDetailModal"
                                   data-nis="{{ $peminjaman->anggota->nis }}"
                                   data-nama="{{ $peminjaman->anggota->nama }}"
                                   data-kelas="{{ $peminjaman->anggota->kelas }}"
                                   data-jurusan="{{ $peminjaman->anggota->jurusan }}"
                                   data-no_telp="{{ $peminjaman->anggota->no_telp ?? '-' }}">
                                    {{ $peminjaman->anggota->nama }}
                                </a>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($peminjaman->detailPeminjaman as $detail)
                                        <li>- {{ $detail->buku->judul }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                            <td>
                                @if ($peminjaman->status == 'Kembali')
                                    <span class="badge bg-success">Kembali</span>
                                @elseif (\Carbon\Carbon::now()->gt($peminjaman->tanggal_kembali))
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                @if ($peminjaman->status == 'Dipinjam')
                                    <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Konfirmasi pengembalian buku?')">
                                            <i class="bi bi-check-circle-fill"></i> Kembalikan
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data peminjaman yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Memastikan filter tetap ada saat pindah halaman -->
            {!! $peminjamans->appends(request()->query())->links() !!}
        </div>
    </div>
</div>

<!-- UBAH DISINI: Tambahkan struktur Modal -->
<div class="modal fade" id="anggotaDetailModal" tabindex="-1" aria-labelledby="anggotaDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="anggotaDetailModalLabel">Detail Anggota Peminjam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
            <tr>
                <th width="30%">NIS</th>
                <td id="modal-nis"></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td id="modal-nama"></td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td id="modal-kelas"></td>
            </tr>
            <tr>
                <th>Jurusan</th>
                <td id="modal-jurusan"></td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td id="modal-no_telp"></td>
            </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- UBAH DISINI: Tambahkan JavaScript untuk mengisi data modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var anggotaDetailModal = document.getElementById('anggotaDetailModal');
    anggotaDetailModal.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        var button = event.relatedTarget;

        // Ekstrak info dari atribut data-*
        var nis = button.getAttribute('data-nis');
        var nama = button.getAttribute('data-nama');
        var kelas = button.getAttribute('data-kelas');
        var jurusan = button.getAttribute('data-jurusan');
        var no_telp = button.getAttribute('data-no_telp');

        // Perbarui konten modal
        var modalNis = anggotaDetailModal.querySelector('#modal-nis');
        var modalNama = anggotaDetailModal.querySelector('#modal-nama');
        var modalKelas = anggotaDetailModal.querySelector('#modal-kelas');
        var modalJurusan = anggotaDetailModal.querySelector('#modal-jurusan');
        var modalNoTelp = anggotaDetailModal.querySelector('#modal-no_telp');

        modalNis.textContent = nis;
        modalNama.textContent = nama;
        modalKelas.textContent = kelas;
        modalJurusan.textContent = jurusan;
        modalNoTelp.textContent = no_telp;
    });
});
</script>
@endpush
