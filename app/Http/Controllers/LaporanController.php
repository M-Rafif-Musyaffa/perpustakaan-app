<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // ... (fungsi-fungsi laporan lainnya tidak berubah)
    public function anggota(Request $request)
    {
        $kelasList = Anggota::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $jurusanList = Anggota::select('jurusan')->distinct()->orderBy('jurusan')->pluck('jurusan');
        $query = Anggota::query();
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }
        $anggotas = $query->orderBy('nama', 'asc')->get();
        return view('laporan.anggota', compact('anggotas', 'kelasList', 'jurusanList'));
    }

    public function buku(Request $request)
    {
        $penulisList = Buku::select('penulis')->distinct()->orderBy('penulis')->pluck('penulis');
        $penerbitList = Buku::select('penerbit')->distinct()->orderBy('penerbit')->pluck('penerbit');
        $kategoriList = Buku::select('kategori')->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $tahunList = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');
        $query = Buku::query();
        if ($request->filled('penulis')) {
            $query->where('penulis', $request->penulis);
        }
        if ($request->filled('penerbit')) {
            $query->where('penerbit', $request->penerbit);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('tahun_terbit')) {
            $query->where('tahun_terbit', $request->tahun_terbit);
        }
        $bukus = $query->orderBy('judul', 'asc')->get();
        return view('laporan.buku', compact('bukus', 'penulisList', 'penerbitList', 'kategoriList', 'tahunList'));
    }

    public function kunjungan(Request $request)
    {
        $query = Kunjungan::with('anggota');
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu_masuk', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu_masuk', '<=', $request->tanggal_akhir);
        }
        $kunjungans = $query->latest('waktu_masuk')->get();
        return view('laporan.kunjungan', compact('kunjungans'));
    }

    public function peminjaman(Request $request)
    {
        $displayQuery = Peminjaman::with(['anggota', 'detailPeminjaman.buku']);
        $countQuery = Peminjaman::query();
        if ($request->filled('tanggal_mulai')) {
            $displayQuery->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
            $countQuery->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $displayQuery->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
            $countQuery->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            if ($request->status == 'Terlambat') {
                $displayQuery->where('status', 'Dipinjam')->whereDate('tanggal_kembali', '<', Carbon::today());
                $countQuery->where('status', 'Dipinjam')->whereDate('tanggal_kembali', '<', Carbon::today());
            } elseif (in_array($request->status, ['Dipinjam', 'Kembali'])) {
                $displayQuery->where('status', $request->status);
                $countQuery->where('status', $request->status);
            }
        }
        $peminjamans = $displayQuery->latest('tanggal_pinjam')->get();
        $totalBelumKembali = $countQuery->where('status', 'Dipinjam')
                                        ->join('detail_peminjamans', 'peminjamans.id', '=', 'detail_peminjamans.peminjaman_id')
                                        ->sum('detail_peminjamans.jumlah');
        return view('laporan.peminjaman', compact('peminjamans', 'totalBelumKembali'));
    }

    public function bukuPopuler()
    {
        $bukus = Buku::select('bukus.judul', 'bukus.penulis', DB::raw('SUM(detail_peminjamans.jumlah) as total_dipinjam'))
            ->join('detail_peminjamans', 'bukus.id', '=', 'detail_peminjamans.buku_id')
            ->groupBy('bukus.id', 'bukus.judul', 'bukus.penulis')
            ->orderBy('total_dipinjam', 'desc')
            ->limit(20)
            ->get();
        return view('laporan.buku_populer', compact('bukus'));
    }

    public function anggotaAktif()
    {
        $aktifPinjam = Anggota::select('anggotas.nis', 'anggotas.nama', 'anggotas.kelas', 'anggotas.jurusan', DB::raw('COUNT(peminjamans.id) as total_peminjaman'))
            ->join('peminjamans', 'anggotas.id', '=', 'peminjamans.anggota_id')
            ->groupBy('anggotas.id', 'anggotas.nis', 'anggotas.nama', 'anggotas.kelas', 'anggotas.jurusan')
            ->orderBy('total_peminjaman', 'desc')
            ->limit(10)
            ->get();
        $aktifKunjung = Anggota::select('anggotas.nis', 'anggotas.nama', 'anggotas.kelas', 'anggotas.jurusan', DB::raw('COUNT(kunjungans.id) as total_kunjungan'))
            ->join('kunjungans', 'anggotas.id', '=', 'kunjungans.anggota_id')
            ->groupBy('anggotas.id', 'anggotas.nis', 'anggotas.nama', 'anggotas.kelas', 'anggotas.jurusan')
            ->orderBy('total_kunjungan', 'desc')
            ->limit(10)
            ->get();
        return view('laporan.anggota_aktif', compact('aktifPinjam', 'aktifKunjung'));
    }

    /**
     * UBAH DISINI: Tambahkan fungsi baru untuk laporan inventaris buku
     */
    public function inventarisBuku()
    {
        $totalJudul = Buku::count();
        $totalEksemplar = Buku::sum('jumlah_stok');
        $bukuStokMenipis = Buku::where('jumlah_stok', '<', 5)->orderBy('jumlah_stok', 'asc')->get();

        return view('laporan.inventaris_buku', compact('totalJudul', 'totalEksemplar', 'bukuStokMenipis'));
    }
}
