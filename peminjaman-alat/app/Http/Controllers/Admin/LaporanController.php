<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function peminjaman(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $peminjamans = Peminjaman::with(['user', 'detailPeminjaman.alat.kategori'])
            ->whereBetween('tanggal_pinjam', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();

        $pdf = Pdf::loadView('admin.laporan.peminjaman', [
            'peminjamans' => $peminjamans,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    public function pengembalian(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $pengembalians = Pengembalian::with(['peminjaman.user', 'peminjaman.detailPeminjaman.alat.kategori'])
            ->whereBetween('tanggal_dikembalikan', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pengembalian', [
            'pengembalians' => $pengembalians,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }

    public function alat()
    {
        $alats = Alat::with('kategori')->get();

        $pdf = Pdf::loadView('admin.laporan.alat', [
            'alats' => $alats,
        ]);

        return $pdf->download('laporan-alat-' . date('Y-m-d') . '.pdf');
    }
}
