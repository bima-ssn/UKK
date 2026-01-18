<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeminjamanRequest;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
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
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $users = User::where('role', 'peminjam')->get();
        $alats = Alat::where('stok', '>', 0)->with('kategori')->get();
        return view('admin.peminjaman.create', compact('users', 'alats'));
    }

    public function store(StorePeminjamanRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            // Check stock availability dengan lock untuk prevent race condition
            $alatIds = array_column($validated['alat'], 'id');
            $alats = Alat::whereIn('id', $alatIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($validated['alat'] as $item) {
                $alat = $alats->get($item['id']);
                if (!$alat || $alat->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->withErrors(['alat' => "Stok " . ($alat->nama_alat ?? 'alat') . " tidak mencukupi."])->withInput();
                }
            }

            $peminjaman = Peminjaman::create([
                'user_id' => $validated['user_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'status' => 'disetujui',
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Create detail peminjaman and reduce stock
            foreach ($validated['alat'] as $item) {
                $peminjaman->detailPeminjaman()->create([
                    'alat_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);

                // Reduce stock dengan lock
                $alat = $alats->get($item['id']);
                $alat->decrement('stok', $item['jumlah']);
            }

            $this->logActivity("Membuat peminjaman baru ID: {$peminjaman->id}");

            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Peminjaman berhasil dibuat.');
        });
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'detailPeminjaman.alat.kategori', 'pengembalian']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $users = User::where('role', 'peminjam')->get();
        $peminjaman->load('detailPeminjaman.alat.kategori');
        return view('admin.peminjaman.edit', compact('peminjaman', 'users'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'status' => 'required|in:menunggu,disetujui,ditolak,dikembalikan',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman->update($validated);

        $this->logActivity("Mengupdate peminjaman ID: {$peminjaman->id}");

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diupdate.');
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

            return redirect()->route('admin.peminjaman.index')
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

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === 'disetujui' && !$peminjaman->pengembalian) {
                // Return stock if not yet returned
                $peminjaman->load('detailPeminjaman.alat');
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $detail->alat->lockForUpdate()->increment('stok', $detail->jumlah);
                }
            }

            $peminjamanId = $peminjaman->id;
            $peminjaman->delete();

            $this->logActivity("Menghapus peminjaman ID: {$peminjamanId}");

            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Peminjaman berhasil dihapus.');
        });
    }
}
