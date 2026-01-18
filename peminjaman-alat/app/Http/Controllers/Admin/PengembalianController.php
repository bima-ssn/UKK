<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengembalianRequest;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    use LogActivity;

    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.detailPeminjaman.alat.kategori'])
            ->latest()
            ->paginate(10);
        return view('admin.pengembalian.index', compact('pengembalians'));
    }

    public function create()
    {
        $peminjamans = Peminjaman::where('status', 'disetujui')
            ->whereDoesntHave('pengembalian')
            ->with('user')
            ->get();
        return view('admin.pengembalian.create', compact('peminjamans'));
    }

    public function store(StorePengembalianRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($validated['peminjaman_id']);

            if ($peminjaman->status !== 'disetujui') {
                DB::rollBack();
                return back()->withErrors(['error' => 'Peminjaman harus dalam status disetujui.'])->withInput();
            }

            // Calculate denda menggunakan database function
            $denda = DB::selectOne("
                SELECT hitung_denda(?, ?) as denda
            ", [$peminjaman->tanggal_kembali->format('Y-m-d'), $validated['tanggal_dikembalikan']]);

            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $validated['peminjaman_id'],
                'tanggal_dikembalikan' => $validated['tanggal_dikembalikan'],
                'denda' => $denda->denda,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Trigger akan mengembalikan stok dan update status peminjaman otomatis

            $this->logActivity("Mencatat pengembalian untuk peminjaman ID: {$peminjaman->id}");

            return redirect()->route('admin.pengembalian.index')
                ->with('success', 'Pengembalian berhasil dicatat.');
        });
    }

    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['peminjaman.user', 'peminjaman.detailPeminjaman.alat.kategori']);
        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    public function edit(Pengembalian $pengembalian)
    {
        return view('admin.pengembalian.edit', compact('pengembalian'));
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $validated = $request->validate([
            'tanggal_dikembalikan' => 'required|date',
            'denda' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $pengembalian->update($validated);

        $this->logActivity("Mengupdate pengembalian ID: {$pengembalian->id}");

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diupdate.');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        return DB::transaction(function () use ($pengembalian) {
            $peminjaman = $pengembalian->peminjaman;

            // Reduce stock back
            $peminjaman->load('detailPeminjaman.alat');
            foreach ($peminjaman->detailPeminjaman as $detail) {
                $detail->alat->lockForUpdate()->decrement('stok', $detail->jumlah);
            }

            // Update peminjaman status
            $peminjaman->update(['status' => 'disetujui']);

            $pengembalianId = $pengembalian->id;
            $pengembalian->delete();

            $this->logActivity("Menghapus pengembalian ID: {$pengembalianId}");

            return redirect()->route('admin.pengembalian.index')
                ->with('success', 'Pengembalian berhasil dihapus.');
        });
    }
}
