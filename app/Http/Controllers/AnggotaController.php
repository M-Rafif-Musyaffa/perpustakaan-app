<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotas = Anggota::latest()->paginate(10);
        return view('anggota.index', compact('anggotas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:anggotas,nis|numeric',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'no_telp' => 'nullable|numeric',
        ]);

        Anggota::create($request->all());

        return redirect()->route('anggota.index')
                         ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     * UBAH DISINI: Pastikan variabelnya adalah $anggota
     */
    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update the specified resource in storage.
     * UBAH DISINI: Pastikan variabelnya adalah $anggota
     */
    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'nis' => 'required|numeric|unique:anggotas,nis,' . $anggota->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'no_telp' => 'nullable|numeric',
        ]);

        $anggota->update($request->all());

        return redirect()->route('anggota.index')
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function search(Request $request)
    {
        $query = $request->get('term');
        $data = Anggota::where('nis', 'LIKE', '%' . $query . '%')
                   ->orWhere('nama', 'LIKE', '%' . $query . '%')
                   ->limit(10)
                   ->get(['id', 'nis', 'nama']);

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     * UBAH DISINI: Pastikan variabelnya adalah $anggota
     */
    public function destroy(Anggota $anggota)
    {
        $anggota->delete();

        return redirect()->route('anggota.index')
                         ->with('success', 'Anggota berhasil dihapus.');
    }
}
