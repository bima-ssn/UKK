<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    use LogActivity;

    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'detailPeminjaman.alat.kategori'])
            ->latest()
            ->paginate(10);
        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'detailPeminjaman.alat.kategori', 'pengembalian']);
        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->withErrors(['error' => 'Hanya peminjaman dengan status menunggu yang dapat disetujui.']);
        }

        return DB::transaction(function () use ($peminjaman) {
            // Load detail dengan alat dan lock untuk prevent race condition
            $peminjaman->load(['detailPeminjaman' => function ($query) {
                $query->with(['alat' => function ($q) {
                    $q->lockForUpdate();
                }]);
            }]);

            // Check stock availability
            foreach ($peminjaman->detailPeminjaman as $detail) {
                if ($detail->alat->stok < $detail->jumlah) {
                    DB::rollBack();
                    return back()->withErrors(['error' => "Stok {$detail->alat->nama_alat} tidak mencukupi."]);
                }
            }

            // Update status - trigger akan mengurangi stok otomatis
            $peminjaman->update(['status' => 'disetujui']);

            $this->logActivity("Menyetujui peminjaman ID: {$peminjaman->id}");

            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Peminjaman berhasil disetujui.');
        });
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->withErrors(['error' => 'Hanya peminjaman dengan status menunggu yang dapat ditolak.']);
        }

        $peminjaman->update(['status' => 'ditolak']);

        $this->logActivity("Menolak peminjaman ID: {$peminjaman->id}");

        return redirect()->route('petugas.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }
}
