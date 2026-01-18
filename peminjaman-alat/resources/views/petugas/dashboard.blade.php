@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Dashboard Petugas</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Peminjaman Menunggu</h5>
            <h2 class="text-3xl font-bold">{{ $stats['peminjamans'] }}</h2>
        </div>
        <div class="bg-cyan-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Pengembalian Menunggu</h5>
            <h2 class="text-3xl font-bold">{{ $stats['pengembalians'] }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h5 class="text-lg font-semibold">Peminjaman Menunggu Persetujuan</h5>
        </div>
        <div class="p-6">
            @php
                $pendingPeminjamans = \App\Models\Peminjaman::where('status', 'menunggu')
                    ->with(['user', 'detailPeminjaman.alat'])
                    ->latest()
                    ->get();
            @endphp
            @if($pendingPeminjamans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Alat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingPeminjamans as $p)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->user->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->detailPeminjaman->sum('jumlah') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium">
                                        <a href="{{ route('petugas.peminjaman.show', $p) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Tidak ada peminjaman yang menunggu persetujuan</p>
            @endif
        </div>
    </div>
</div>
@endsection
