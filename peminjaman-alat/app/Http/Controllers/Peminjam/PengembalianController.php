<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Pengembalian::whereHas('peminjaman', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->with(['peminjaman.detailPeminjaman.alat'])
        ->latest()
        ->paginate(10);

        return view('peminjam.pengembalian.index', compact('pengembalians'));
    }

    public function show(Pengembalian $pengembalian)
    {
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        $pengembalian->load(['peminjaman.user', 'peminjaman.detailPeminjaman.alat.kategori']);
        return view('peminjam.pengembalian.show', compact('pengembalian'));
    }
}
