<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request; // <-- Jangan lupa tambahkan ini
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * UBAH DISINI: Tambahkan Request $request dan logika filter
     */
    public function index(Request $request)
    {
        // Mulai query dasar
        $query = Peminjaman::with(['anggota', 'detailPeminjaman.buku']);

        // Terapkan filter berdasarkan status jika ada di URL
        if ($request->has('status') && in_array($request->status, ['Dipinjam', 'Kembali'])) {
            $query->where('status', $request->status);
        }

        // Selesaikan query dan ambil data
        $peminjamans = $query->latest()->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        return view('peminjaman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'buku_ids' => 'required|array|min:1',
            'buku_ids.*' => 'exists:bukus,id',
            'tanggal_kembali' => 'required|date|after_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'anggota_id' => $request->anggota_id,
                'tanggal_pinjam' => Carbon::today(),
                'tanggal_kembali' => $request->tanggal_kembali,
            ]);

            foreach ($request->buku_ids as $buku_id) {
                $peminjaman->detailPeminjaman()->create(['buku_id' => $buku_id]);
                Buku::find($buku_id)->decrement('jumlah_stok');
            }

            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'Dipinjam') {
            return redirect()->route('peminjaman.index')->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $peminjaman->update(['status' => 'Kembali']);
            foreach ($peminjaman->detailPeminjaman as $detail) {
                Buku::find($detail->buku_id)->increment('jumlah_stok');
            }
            DB::commit();
            return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('peminjaman.index')->with('error', 'Terjadi kesalahan saat proses pengembalian.');
        }
    }
}
