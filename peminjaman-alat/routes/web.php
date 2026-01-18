<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\PengembalianController as AdminPengembalianController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\Peminjam\AlatController as PeminjamAlatController;
use App\Http\Controllers\Peminjam\PeminjamanController as PeminjamPeminjamanController;
use App\Http\Controllers\Peminjam\PengembalianController as PeminjamPengembalianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isPetugas()) {
        return redirect()->route('petugas.dashboard');
    } else {
        return redirect()->route('peminjam.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'users' => \App\Models\User::count(),
            'alats' => \App\Models\Alat::count(),
            'peminjamans' => \App\Models\Peminjaman::count(),
            'pengembalians' => \App\Models\Pengembalian::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    // Users
    Route::resource('users', UserController::class);

    // Kategori
    Route::resource('kategori', KategoriController::class);

    // Alat
    Route::resource('alat', AlatController::class);

    // Peminjaman
    Route::resource('peminjaman', AdminPeminjamanController::class);
    Route::post('peminjaman/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');

    // Pengembalian
    Route::resource('pengembalian', AdminPengembalianController::class);

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::post('laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
    Route::get('laporan/alat', [LaporanController::class, 'alat'])->name('laporan.alat');

    // Log Aktivitas
    Route::get('log', [LogAktivitasController::class, 'index'])->name('log.index');
    Route::get('log/{log}', [LogAktivitasController::class, 'show'])->name('log.show');
});

// Petugas Routes
Route::middleware(['auth', 'petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'peminjamans' => \App\Models\Peminjaman::where('status', 'menunggu')->count(),
            'pengembalians' => \App\Models\Peminjaman::where('status', 'disetujui')->whereDoesntHave('pengembalian')->count(),
        ];
        return view('petugas.dashboard', compact('stats'));
    })->name('dashboard');

    // Peminjaman
    Route::get('peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/{peminjaman}', [PetugasPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('peminjaman/{peminjaman}/approve', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{peminjaman}/reject', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.reject');

    // Pengembalian
    Route::get('pengembalian', [PetugasPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/create', [PetugasPengembalianController::class, 'create'])->name('pengembalian.create');
    Route::post('pengembalian', [PetugasPengembalianController::class, 'store'])->name('pengembalian.store');
    Route::get('pengembalian/{pengembalian}', [PetugasPengembalianController::class, 'show'])->name('pengembalian.show');

    // Alat (view only)
    Route::get('alat', [PeminjamAlatController::class, 'index'])->name('alat.index');
    Route::get('alat/{alat}', [PeminjamAlatController::class, 'show'])->name('alat.show');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
    Route::post('laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
    Route::get('laporan/alat', [LaporanController::class, 'alat'])->name('laporan.alat');
});

// Peminjam Routes
Route::middleware(['auth', 'peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'peminjamans' => \App\Models\Peminjaman::where('user_id', auth()->id())->count(),
            'pengembalians' => \App\Models\Pengembalian::whereHas('peminjaman', function($q) {
                $q->where('user_id', auth()->id());
            })->count(),
            'alats' => \App\Models\Alat::count(),
            'menunggu' => \App\Models\Peminjaman::where('user_id', auth()->id())->where('status', 'menunggu')->count(),
            'disetujui' => \App\Models\Peminjaman::where('user_id', auth()->id())->where('status', 'disetujui')->count(),
        ];
        return view('peminjam.dashboard', compact('stats'));
    })->name('dashboard');

    // Alat
    Route::get('alat', [PeminjamAlatController::class, 'index'])->name('alat.index');
    Route::get('alat/{alat}', [PeminjamAlatController::class, 'show'])->name('alat.show');

    // Peminjaman
    Route::resource('peminjaman', PeminjamPeminjamanController::class);

    // Pengembalian
    Route::get('pengembalian', [PeminjamPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{pengembalian}', [PeminjamPengembalianController::class, 'show'])->name('pengembalian.show');

    // Riwayat (History - alias for pengembalian)
    Route::get('riwayat', [PeminjamPengembalianController::class, 'index'])->name('riwayat.index');
});

require __DIR__.'/auth.php';
