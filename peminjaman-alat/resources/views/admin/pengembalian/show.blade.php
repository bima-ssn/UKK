@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Detail Pengembalian</h2>
            <p class="text-gray-600 mt-1">ID: #{{ $pengembalian->id }}</p>
        </div>
        <a href="{{ route('admin.pengembalian.edit', $pengembalian) }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 px-5 rounded-lg shadow-md transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h5 class="text-xl font-bold text-gray-900">Informasi Pengembalian</h5>
            </div>
            <dl class="space-y-4">
                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">ID Pengembalian</dt>
                    <dd class="text-base font-semibold text-gray-900">#{{ $pengembalian->id }}</dd>
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">ID Peminjaman</dt>
                    <dd class="text-base font-semibold text-gray-900">#{{ $pengembalian->peminjaman_id }}</dd>
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Peminjam</dt>
                    <dd class="text-base text-gray-900">{{ $pengembalian->peminjaman->user->name ?? 'N/A' }}</dd>
                    <dd class="text-sm text-gray-500 mt-0.5">{{ $pengembalian->peminjaman->user->email ?? '' }}</dd>
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Pinjam</dt>
                    <dd class="text-base text-gray-900">{{ $pengembalian->peminjaman->tanggal_pinjam->format('d/m/Y') }}</dd>
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Kembali (Plan)</dt>
                    <dd class="text-base text-gray-900">{{ $pengembalian->peminjaman->tanggal_kembali->format('d/m/Y') }}</dd>
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Dikembalikan</dt>
                    <dd class="text-base text-gray-900">{{ $pengembalian->tanggal_dikembalikan->format('d/m/Y') }}</dd>
                </div>

                <div class="pb-4 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Denda</dt>
                    <dd class="mt-1">
                        @if($pengembalian->denda > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                Tidak ada denda
                            </span>
                        @endif
                    </dd>
                </div>

                @if($pengembalian->keterangan)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Keterangan</dt>
                        <dd class="text-base text-gray-900">{{ $pengembalian->keterangan }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h5 class="text-xl font-bold text-gray-900">Detail Alat yang Dikembalikan</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Alat</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengembalian->peminjaman->detailPeminjaman ?? [] as $detail)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $detail->alat->nama_alat ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $detail->alat->kategori->nama_kategori ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $detail->jumlah }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Tidak ada detail alat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.pengembalian.index') }}" class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2.5 px-5 rounded-lg shadow-md transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>
@endsection
