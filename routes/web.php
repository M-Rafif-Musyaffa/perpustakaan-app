<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Arahkan halaman utama langsung ke halaman login
    return redirect()->route('login');
});

// Daftarkan semua route otentikasi (login, register, logout, dll.)
Auth::routes();

// Grup route yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('anggota', AnggotaController::class)->parameters([
        'anggota' => 'anggota'
    ]);

    Route::resource('buku', BukuController::class);

    Route::get('kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::post('kunjungan/check-in', [KunjunganController::class, 'checkIn'])->name('kunjungan.check-in');
    Route::put('kunjungan/check-out/{kunjungan}', [KunjunganController::class, 'checkOut'])->name('kunjungan.check-out');
    Route::get('/search-anggota', [App\Http\Controllers\AnggotaController::class, 'search'])->name('anggota.search');

    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);
    Route::put('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::get('/search-buku', [BukuController::class, 'search'])->name('buku.search');

    Route::get('laporan/anggota', [LaporanController::class, 'anggota'])->name('laporan.anggota');
    Route::get('laporan/buku', [LaporanController::class, 'buku'])->name('laporan.buku');
    Route::get('laporan/kunjungan', [LaporanController::class, 'kunjungan'])->name('laporan.kunjungan');
    Route::get('laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::get('laporan/buku-populer', [LaporanController::class, 'bukuPopuler'])->name('laporan.buku-populer');
    Route::get('laporan/anggota-aktif', [LaporanController::class, 'anggotaAktif'])->name('laporan.anggota-aktif');
    Route::get('laporan/inventaris-buku', [LaporanController::class, 'inventarisBuku'])->name('laporan.inventaris-buku');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});
