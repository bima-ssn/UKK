@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Detail Peminjaman</h2>
        <div class="flex gap-2">
            @if($peminjaman->status === 'menunggu')
                <form action="{{ route('petugas.peminjaman.approve', $peminjaman) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Setujui peminjaman ini?')">
                        Setujui
                    </button>
                </form>
                <form action="{{ route('petugas.peminjaman.reject', $peminjaman) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Tolak peminjaman ini?')">
                        Tolak
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Informasi Peminjaman</h5>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">ID Peminjaman</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->id }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Peminjam</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->user->name }} ({{ $peminjaman->user->email }})</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Kembali</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 text-xs rounded-full {{ $peminjaman->status === 'disetujui' ? 'bg-green-100 text-green-800' : ($peminjaman->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : ($peminjaman->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($peminjaman->status) }}
                        </span>
                    </dd>
                </div>

                @if($peminjaman->keterangan)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->keterangan }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Detail Alat yang Dipinjam</h5>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($peminjaman->detailPeminjaman as $detail)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->alat->nama_alat }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->alat->kategori->nama_kategori }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->jumlah }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $detail->alat->stok >= $detail->jumlah ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $detail->alat->stok }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('petugas.peminjaman.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>
</div>
@endsection
