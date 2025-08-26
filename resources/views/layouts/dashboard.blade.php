<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; background-color: #f8fafc; }
        .main-wrapper { display: flex; flex: 1; }
        .sidebar { width: 250px; min-height: 100vh; transition: all 0.3s; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: rgba(255, 255, 255, 0.15); border-radius: 0.5rem; transition: background-color 0.2s; }
        .navbar .form-control { background-color: #f8fafc; }
        .navbar .form-control:focus { background-color: #fff; box-shadow: none; border-color: #16a34a; }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app" class="main-wrapper">
        <nav class="sidebar bg-primary text-white p-3 d-flex flex-column">
            <div class="text-center pt-3 pb-4">
                <a class="text-white text-decoration-none" href="{{ url('/dashboard') }}">
                    <i class="bi bi-book-half" style="font-size: 2.5rem;"></i>
                    <h1 class="h5 mt-2 mb-0 fw-bold">Perpustakaan App</h1>
                </a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center" href="#masterDataCollapse" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="masterDataCollapse">
                        <span><i class="bi bi-collection-fill me-2"></i> Master Data</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="collapse" id="masterDataCollapse">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('anggota.*') ? 'active' : '' }}" href="{{ route('anggota.index') }}">Kelola Anggota</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('buku.*') ? 'active' : '' }}" href="{{ route('buku.index') }}">Kelola Buku</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center" href="#transaksiCollapse" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="transaksiCollapse">
                        <span><i class="bi bi-arrow-down-up me-2"></i> Transaksi</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="collapse" id="transaksiCollapse">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}" href="{{ route('kunjungan.index') }}">Buku Kunjungan</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">Peminjaman Buku</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center" href="#laporanCollapse" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="laporanCollapse">
                        <span><i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Laporan</span>
                        <i class="bi bi-chevron-down"></i>
                    </a>
                    <div class="collapse" id="laporanCollapse">
                        <ul class="nav flex-column ps-3">
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.anggota') ? 'active' : '' }}" href="{{ route('laporan.anggota') }}">Laporan Anggota</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.buku') ? 'active' : '' }}" href="{{ route('laporan.buku') }}">Laporan Buku</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.kunjungan') ? 'active' : '' }}" href="{{ route('laporan.kunjungan') }}">Laporan Kunjungan</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.peminjaman') ? 'active' : '' }}" href="{{ route('laporan.peminjaman') }}">Laporan Peminjaman</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.buku-populer') ? 'active' : '' }}" href="{{ route('laporan.buku-populer') }}">Buku Terpopuler</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.anggota-aktif') ? 'active' : '' }}" href="{{ route('laporan.anggota-aktif') }}">Anggota Teraktif</a>
                            </li>
                            <li class="nav-item mt-1">
                                <a class="nav-link text-white {{ request()->routeIs('laporan.inventaris-buku') ? 'active' : '' }}" href="{{ route('laporan.inventaris-buku') }}">Laporan Inventaris</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="main-content">
            <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom" style="padding-top: 1rem; padding-bottom: 1rem;">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Tombol Pencarian Dihapus -->
                        <ul class="navbar-nav me-auto"></ul>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=16a34a&color=fff&rounded=true&size=40" alt="Avatar" class="me-2">
                                    <span class="fw-bold d-none d-sm-block">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="navbarDropdown">
                                    <!-- Link Profil Saya Diarahkan ke Route 'profile.edit' -->
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i> Profil Saya</a>
                                    <a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-gear-fill me-2"></i> Pengaturan</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
