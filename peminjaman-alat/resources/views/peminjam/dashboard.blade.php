@extends('layouts.app')

@section('title', 'Dashboard Peminjam')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Dashboard Peminjam</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Peminjaman Aktif</h5>
            <h2 class="text-3xl font-bold">{{ $stats['peminjamans'] }}</h2>
        </div>
        <div class="bg-green-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Pengembalian Selesai</h5>
            <h2 class="text-3xl font-bold">{{ $stats['pengembalians'] }}</h2>
        </div>
        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Alat Tersedia</h5>
            <h2 class="text-3xl font-bold">{{ $stats['alats'] }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h5 class="text-lg font-semibold">Peminjaman Saya</h5>
        </div>
        <div class="p-6">
            @php
                $myPeminjamans = \App\Models\Peminjaman::where('user_id', auth()->id())
                    ->with(['detailPeminjaman.alat'])
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp
            @if($myPeminjamans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Alat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($myPeminjamans as $p)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->tanggal_kembali->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $p->detailPeminjaman->sum('jumlah') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $p->status === 'disetujui' ? 'bg-green-100 text-green-800' : ($p->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : ($p->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium">
                                        <a href="{{ route('peminjam.peminjaman.show', $p) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Belum ada peminjaman</p>
            @endif
        </div>
    </div>
</div>
@endsection
