Perpustakaan App
Selamat datang di Perpustakaan App, sebuah sistem informasi manajemen perpustakaan sekolah yang modern, sederhana, dan fungsional. Aplikasi ini dibangun dari awal menggunakan Laravel 10 dan Bootstrap 5, dirancang untuk memudahkan petugas perpustakaan dalam mengelola data buku, anggota, serta berbagai transaksi harian.

📸 Screenshot Aplikasi
Berikut adalah beberapa tampilan dari aplikasi Perpustakaan App:

Halaman Login

Dashboard Utama

Halaman Kelola Buku

Halaman Laporan

✨ Fitur Utama
Aplikasi ini dilengkapi dengan berbagai fitur komprehensif untuk memenuhi kebutuhan operasional perpustakaan sekolah.

📊 Dashboard Interaktif
Pusat informasi utama yang menampilkan ringkasan data penting secara visual:

Kartu Informasi: Total anggota, total judul buku, total eksemplar, dan jumlah peminjaman aktif.

Grafik Kunjungan: Visualisasi jumlah pengunjung perpustakaan selama 7 hari terakhir.

Diagram Kategori: Diagram lingkaran yang menampilkan 5 kategori buku paling populer.

Peminjaman Terbaru: Daftar 5 transaksi peminjaman terakhir yang terjadi.

📚 Manajemen Data (Master)
Kelola Anggota: Fitur CRUD (Create, Read, Update, Delete) lengkap untuk data siswa/anggota. Dilengkapi validasi untuk memastikan NIS tidak duplikat.

Kelola Buku: Fitur CRUD lengkap untuk data koleksi buku perpustakaan.

transact Transaksi Perpustakaan
Buku Kunjungan: Petugas dapat mencatat kehadiran anggota dengan mudah.

Fitur autocomplete untuk mencari anggota berdasarkan NIS atau nama.

Pencatatan waktu masuk dan keluar secara otomatis.

Peminjaman Buku:

Memungkinkan peminjaman beberapa judul buku dalam satu transaksi.

Mendukung peminjaman lebih dari satu eksemplar untuk judul buku yang sama.

Pengurangan stok buku secara otomatis saat peminjaman dicatat.

Pengembalian Buku:

Proses pengembalian dengan satu klik.

Penambahan stok buku secara otomatis saat buku dikembalikan.

Status peminjaman diperbarui secara real-time.

📄 Pelaporan
Modul laporan yang lengkap dengan fitur filter dan siap cetak untuk memudahkan analisis data.

Laporan Anggota: Daftar semua anggota dengan filter berdasarkan Kelas dan Jurusan.

Laporan Buku: Daftar semua koleksi buku dengan filter berdasarkan Penulis, Penerbit, Kategori, dan Tahun Terbit.

Laporan Kunjungan: Riwayat kunjungan anggota dengan filter berdasarkan rentang tanggal.

Laporan Peminjaman: Riwayat transaksi peminjaman dengan filter berdasarkan rentang tanggal dan status (Belum Kembali, Sudah Kembali, Terlambat).

Laporan Buku Terpopuler: Peringkat 20 judul buku yang paling sering dipinjam.

Laporan Anggota Teraktif: Peringkat 10 anggota yang paling aktif, baik berdasarkan jumlah peminjaman maupun jumlah kunjungan.

Laporan Inventaris Buku: Ringkasan total koleksi (judul dan eksemplar) serta daftar buku yang stoknya menipis.

⚙️ Fitur Lainnya
Sistem Otentikasi: Halaman login dan register yang aman.

Manajemen Profil: Pengguna dapat memperbarui informasi pribadi (nama, email) dan mengubah kata sandi.

Desain Responsif: Tampilan yang optimal di berbagai perangkat, mulai dari desktop hingga mobile.

🚀 Teknologi yang Digunakan
Backend: Laravel 10

Frontend: Bootstrap 5, Vite

Database: MySQL

Lainnya: Chart.js untuk visualisasi data
