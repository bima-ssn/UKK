@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Dashboard Admin</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-blue-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Total User</h5>
            <h2 class="text-3xl font-bold">{{ $stats['users'] }}</h2>
        </div>
        <div class="bg-green-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Total Alat</h5>
            <h2 class="text-3xl font-bold">{{ $stats['alats'] }}</h2>
        </div>
        <div class="bg-yellow-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Total Peminjaman</h5>
            <h2 class="text-3xl font-bold">{{ $stats['peminjamans'] }}</h2>
        </div>
        <div class="bg-cyan-500 text-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-2">Total Pengembalian</h5>
            <h2 class="text-3xl font-bold">{{ $stats['pengembalians'] }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h5 class="text-lg font-semibold">Peminjaman Terbaru</h5>
            </div>
            <div class="p-6">
                @php
                    $recentPeminjamans = \App\Models\Peminjaman::with('user')->latest()->limit(5)->get();
                @endphp
                @if($recentPeminjamans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentPeminjamans as $p)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $p->id }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $p->user->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $p->status === 'disetujui' ? 'bg-green-100 text-green-800' : ($p->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($p->status) }}
                                            </span>
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

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h5 class="text-lg font-semibold">Alat dengan Stok Rendah</h5>
            </div>
            <div class="p-6">
                @php
                    $lowStock = \App\Models\Alat::where('stok', '<=', 5)->with('kategori')->get();
                @endphp
                @if($lowStock->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($lowStock as $a)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $a->nama_alat }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $a->kategori->nama_kategori }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">{{ $a->stok }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Semua alat memiliki stok cukup</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
