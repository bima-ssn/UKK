<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->latest()
            ->paginate(12);
        return view('peminjam.alat.index', compact('alats'));
    }

    public function show(Alat $alat)
    {
        $alat->load('kategori');
        return view('peminjam.alat.show', compact('alat'));
    }
}
