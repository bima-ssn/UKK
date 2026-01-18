<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Traits\LogActivity;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    use LogActivity;

    public function index()
    {
        $alats = Alat::with('kategori')->latest()->paginate(10);
        return view('admin.alat.index', compact('alats'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.alat.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_alat' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
        ]);

        $alat = Alat::create($validated);

        $this->logActivity("Menambah alat baru: {$alat->nama_alat}");

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function show(Alat $alat)
    {
        $alat->load('kategori');
        return view('admin.alat.show', compact('alat'));
    }

    public function edit(Alat $alat)
    {
        $kategoris = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_alat' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,perbaikan',
        ]);

        $alat->update($validated);

        $this->logActivity("Mengupdate alat: {$alat->nama_alat}");

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diupdate.');
    }

    public function destroy(Alat $alat)
    {
        $namaAlat = $alat->nama_alat;
        $alat->delete();

        $this->logActivity("Menghapus alat: {$namaAlat}");

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
