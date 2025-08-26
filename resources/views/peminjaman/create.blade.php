@extends('layouts.dashboard')

@section('content')
<style>
    .autocomplete-container { position: relative; }
    .autocomplete-items { position: absolute; border: 1px solid #d4d4d4; border-bottom: none; border-top: none; z-index: 99; top: 100%; left: 0; right: 0; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 0 0 .25rem .25rem; }
    .autocomplete-items div { padding: 10px; cursor: pointer; background-color: #fff; border-bottom: 1px solid #d4d4d4; }
    .autocomplete-items div:hover { background-color: #e9e9e9; }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-3">Form Peminjaman Buku</h1>

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

    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Kolom Kiri: Info Peminjam & Keranjang -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Informasi Peminjaman
                    </div>
                    <div class="card-body">
                        {{-- Cari Anggota --}}
                        <div class="mb-3">
                            <label for="search_anggota" class="form-label">Cari Anggota (NIS atau Nama)</label>
                            <div class="autocomplete-container">
                                <input type="text" class="form-control" id="search_anggota" placeholder="Ketik untuk mencari..." autocomplete="off">
                                <div class="autocomplete-items" id="anggota-list"></div>
                            </div>
                            <input type="hidden" name="anggota_id" id="anggota_id">
                        </div>

                        <hr>

                        {{-- Cari Buku --}}
                        <div class="mb-3">
                            <label for="search_buku" class="form-label">Cari Buku (Judul)</label>
                            <div class="autocomplete-container">
                                <input type="text" class="form-control" id="search_buku" placeholder="Ketik judul buku..." autocomplete="off">
                                <div class="autocomplete-items" id="buku-list"></div>
                            </div>
                        </div>

                        {{-- Keranjang Peminjaman --}}
                        <h5>Buku yang akan Dipinjam</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="keranjang-peminjaman">
                                <!-- Buku yang dipilih akan muncul di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail & Tombol Simpan -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Detail Transaksi
                    </div>
                    <div class="card-body">
                        <div id="detail-peminjam">
                            <p class="text-muted">Pilih anggota terlebih dahulu.</p>
                        </div>
                        <hr>
                        {{-- UBAH DISINI: Tambahkan input tanggal kembali --}}
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Harus Kembali</label>
                            <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali', \Carbon\Carbon::now()->addDays(7)->format('Y-m-d')) }}" required>
                             @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary btn-lg" id="btn-simpan" disabled>Simpan Peminjaman</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// ... (JavaScript tidak berubah)
document.addEventListener('DOMContentLoaded', function() {
    let keranjang = [];
    const searchAnggotaInput = document.getElementById('search_anggota');
    const anggotaList = document.getElementById('anggota-list');
    const anggotaIdInput = document.getElementById('anggota_id');
    const detailPeminjam = document.getElementById('detail-peminjam');
    const searchBukuInput = document.getElementById('search_buku');
    const bukuList = document.getElementById('buku-list');
    const keranjangTbody = document.getElementById('keranjang-peminjaman');
    const btnSimpan = document.getElementById('btn-simpan');

    searchAnggotaInput.addEventListener('keyup', function() {
        const query = this.value;
        if (query.length < 2) { anggotaList.innerHTML = ''; return; }
        fetch(`{{ route('anggota.search') }}?term=${query}`)
            .then(response => response.json())
            .then(data => {
                anggotaList.innerHTML = '';
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.innerHTML = `<strong>${item.nis}</strong> - ${item.nama}`;
                    div.addEventListener('click', function() {
                        searchAnggotaInput.value = `${item.nis} - ${item.nama}`;
                        anggotaIdInput.value = item.id;
                        detailPeminjam.innerHTML = `
                            <p class="mb-1"><strong>NIS:</strong> ${item.nis}</p>
                            <p class="mb-0"><strong>Nama:</strong> ${item.nama}</p>
                        `;
                        anggotaList.innerHTML = '';
                        validateForm();
                    });
                    anggotaList.appendChild(div);
                });
            });
    });

    searchBukuInput.addEventListener('keyup', function() {
        const query = this.value;
        if (query.length < 2) { bukuList.innerHTML = ''; return; }
        fetch(`{{ route('buku.search') }}?term=${query}`)
            .then(response => response.json())
            .then(data => {
                bukuList.innerHTML = '';
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.innerHTML = `<strong>${item.judul}</strong> (${item.penulis})`;
                    div.addEventListener('click', function() {
                        addBukuToKeranjang(item);
                        searchBukuInput.value = '';
                        bukuList.innerHTML = '';
                    });
                    bukuList.appendChild(div);
                });
            });
    });

    function addBukuToKeranjang(buku) {
        if (keranjang.find(item => item.id === buku.id)) {
            alert('Buku ini sudah ada di keranjang.');
            return;
        }
        keranjang.push(buku);
        renderKeranjang();
        validateForm();
    }

    function removeBukuFromKeranjang(bukuId) {
        keranjang = keranjang.filter(item => item.id !== bukuId);
        renderKeranjang();
        validateForm();
    }

    function renderKeranjang() {
        keranjangTbody.innerHTML = '';
        if (keranjang.length === 0) {
            keranjangTbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Keranjang masih kosong.</td></tr>';
        }
        keranjang.forEach(buku => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    ${buku.judul}
                    <input type="hidden" name="buku_ids[]" value="${buku.id}">
                </td>
                <td>${buku.penulis}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button>
                </td>
            `;
            tr.querySelector('.btn-remove').addEventListener('click', () => removeBukuFromKeranjang(buku.id));
            keranjangTbody.appendChild(tr);
        });
    }

    function validateForm() {
        if (anggotaIdInput.value && keranjang.length > 0) {
            btnSimpan.disabled = false;
        } else {
            btnSimpan.disabled = true;
        }
    }
    renderKeranjang();
});
</script>
@endsection
