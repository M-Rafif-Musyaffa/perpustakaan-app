<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    public function index()
    {
        // Ambil data kunjungan hari ini saja, urutkan dari yang terbaru
        $kunjungans = Kunjungan::with('anggota')
                                ->whereDate('waktu_masuk', Carbon::today())
                                ->latest()
                                ->paginate(15);
        return view('kunjungan.index', compact('kunjungans'));
    }

    public function checkIn(Request $request)
    {
        $request->validate(['nis' => 'required|exists:anggotas,nis']);

        $anggota = Anggota::where('nis', $request->nis)->first();

        // Cek apakah anggota ini sudah check-in tapi belum check-out
        $kunjunganAktif = Kunjungan::where('anggota_id', $anggota->id)
                                   ->whereNull('waktu_keluar')
                                   ->first();

        if ($kunjunganAktif) {
            return redirect()->route('kunjungan.index')
                             ->with('error', 'Anggota ini sudah tercatat masuk dan belum keluar.');
        }

        Kunjungan::create([
            'anggota_id' => $anggota->id,
            'waktu_masuk' => now(),
        ]);

        return redirect()->route('kunjungan.index')
                         ->with('success', $anggota->nama . ' berhasil check-in.');
    }

    public function checkOut(Kunjungan $kunjungan)
    {
        $kunjungan->update(['waktu_keluar' => now()]);

        return redirect()->route('kunjungan.index')
                         ->with('success', $kunjungan->anggota->nama . ' berhasil check-out.');
    }
}
