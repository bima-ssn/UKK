<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    use LogActivity;

    public function index()
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
            ->with(['detailPeminjaman.alat.kategori', 'pengembalian'])
            ->latest()
            ->paginate(10);
        return view('peminjam.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $alats = Alat::where('stok', '>', 0)->with('kategori')->get();
        return view('peminjam.peminjaman.create', compact('alats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'alat' => 'required|array|min:1',
            'alat.*.id' => 'required|exists:alat,id',
            'alat.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($validated) {
            // Check stock availability dengan lock
            $alatIds = array_column($validated['alat'], 'id');
            $alats = Alat::whereIn('id', $alatIds)
                ->where('stok', '>', 0)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($validated['alat'] as $item) {
                $alat = $alats->get($item['id']);
                if (!$alat || $alat->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->withErrors(['alat' => "Stok {$alat->nama_alat} tidak mencukupi." . ($alat->nama_alat === null ? 'alat' : '')])->withInput();
                }
            }

            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'status' => 'menunggu',
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Create detail peminjaman (stok belum dikurangi, akan dikurangi saat disetujui)
            foreach ($validated['alat'] as $item) {
                $peminjaman->detailPeminjaman()->create([
                    'alat_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            $this->logActivity("Mengajukan peminjaman baru ID: {$peminjaman->id}");

            return redirect()->route('peminjam.peminjaman.index')
                ->with('success', 'Peminjaman berhasil diajukan. Menunggu persetujuan.');
        });
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        $peminjaman->load(['detailPeminjaman.alat.kategori', 'pengembalian']);
        return view('peminjam.peminjaman.show', compact('peminjaman'));
    }
}
