<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk Kartu Informasi
        $totalAnggota = Anggota::count();
        $totalJudulBuku = Buku::count();
        $totalEksemplar = Buku::sum('jumlah_stok');
        $peminjamanAktif = Peminjaman::where('status', 'Dipinjam')->count();

        // Data untuk Grafik Kunjungan (7 Hari Terakhir)
        $kunjunganData = Kunjungan::select(DB::raw('DATE(waktu_masuk) as tanggal'), DB::raw('count(*) as jumlah'))
            ->where('waktu_masuk', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $kunjunganLabels = $kunjunganData->pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });
        $kunjunganCounts = $kunjunganData->pluck('jumlah');

        // Data for Grafik Kategori Buku (Top 5)
        $kategoriData = Buku::select('kategori', DB::raw('count(*) as jumlah'))
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->groupBy('kategori')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();

        $kategoriLabels = $kategoriData->pluck('kategori');
        $kategoriCounts = $kategoriData->pluck('jumlah');

        // Data untuk tabel Peminjaman Terbaru
        $peminjamanTerbaru = Peminjaman::with('anggota')->latest()->limit(5)->get();

        return view('dashboard.index', compact(
            'totalAnggota',
            'totalJudulBuku',
            'totalEksemplar',
            'peminjamanAktif',
            'kunjunganLabels',
            'kunjunganCounts',
            'kategoriLabels',
            'kategoriCounts',
            'peminjamanTerbaru'
        ));
    }
}
