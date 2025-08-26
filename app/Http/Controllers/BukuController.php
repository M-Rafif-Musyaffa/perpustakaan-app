<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::latest()->paginate(10);
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_stok' => 'required|integer|min:0',
            'kategori' => 'nullable|string|max:100',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_stok' => 'required|integer|min:0',
            'kategori' => 'nullable|string|max:100',
        ]);

        $buku->update($request->all());

        return redirect()->route('buku.index')
                         ->with('success', 'Data buku berhasil diperbarui.');
    }

    // app/Http/Controllers/BukuController.php
    public function search(Request $request)
    {
        $query = $request->get('term');
        // Cari buku yang stoknya lebih dari 0
        $data = Buku::where('judul', 'LIKE', '%' . $query . '%')
                ->where('jumlah_stok', '>', 0)
                ->limit(10)
                ->get(['id', 'judul', 'penulis']);

        return response()->json($data);
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
                         ->with('success', 'Buku berhasil dihapus.');
    }
}
