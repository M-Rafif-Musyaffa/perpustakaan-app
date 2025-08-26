@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Dashboard</h1>

    <!-- Baris Kartu Informasi -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Anggota</h5>
                            <p class="card-text fs-2 fw-bold">{{ $totalAnggota }}</p>
                        </div>
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Judul Buku</h5>
                            <p class="card-text fs-2 fw-bold">{{ $totalJudulBuku }}</p>
                        </div>
                        <i class="bi bi-journal-bookmark-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Eksemplar</h5>
                            <p class="card-text fs-2 fw-bold">{{ $totalEksemplar }}</p>
                        </div>
                        <i class="bi bi-stack fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Pinjaman Aktif</h5>
                            <p class="card-text fs-2 fw-bold">{{ $peminjamanAktif }}</p>
                        </div>
                        <i class="bi bi-arrow-down-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Grafik -->
    <div class="row mt-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    Grafik Kunjungan 7 Hari Terakhir
                </div>
                <div class="card-body">
                    <canvas id="kunjunganChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    5 Kategori Buku Teratas
                </div>
                <div class="card-body">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Peminjaman Terbaru -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Peminjaman Terbaru
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Peminjam</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamanTerbaru as $peminjaman)
                                <tr>
                                    <td>{{ $peminjaman->anggota->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>
                                        @if ($peminjaman->status == 'Dipinjam')
                                            <span class="badge bg-warning text-dark">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Kembali</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data peminjaman.</td>
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
@endsection

@push('scripts')
<!-- Memuat library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Grafik Kunjungan
    const ctxKunjungan = document.getElementById('kunjunganChart').getContext('2d');
    new Chart(ctxKunjungan, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kunjunganLabels) !!},
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: {!! json_encode($kunjunganCounts) !!},
                backgroundColor: 'rgba(22, 163, 74, 0.5)',
                borderColor: 'rgba(22, 163, 74, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Kategori
    const ctxKategori = document.getElementById('kategoriChart').getContext('2d');
    new Chart(ctxKategori, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriLabels) !!},
            datasets: [{
                label: 'Jumlah Judul',
                data: {!! json_encode($kategoriCounts) !!},
                backgroundColor: [
                    'rgba(22, 163, 74, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(74, 222, 128, 0.8)',
                    'rgba(134, 239, 172, 0.8)',
                    'rgba(187, 247, 208, 0.8)'
                ],
                hoverOffset: 4
            }]
        }
    });
});
</script>
@endpush
