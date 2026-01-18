<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Traits\LogActivity;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    use LogActivity;

    public function index()
    {
        $kategoris = Kategori::latest()->paginate(10);
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::create($validated);

        $this->logActivity("Menambah kategori baru: {$kategori->nama_kategori}");

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Kategori $kategori)
    {
        return view('admin.kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $kategori->id,
            'keterangan' => 'nullable|string',
        ]);

        $kategori->update($validated);

        $this->logActivity("Mengupdate kategori: {$kategori->nama_kategori}");

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Kategori $kategori)
    {
        $namaKategori = $kategori->nama_kategori;
        $kategori->delete();

        $this->logActivity("Menghapus kategori: {$namaKategori}");

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
