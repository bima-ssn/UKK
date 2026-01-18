@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Detail Pengembalian</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Informasi Pengembalian</h5>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">ID Pengembalian</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->id }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">ID Peminjaman</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->peminjaman_id }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->peminjaman->tanggal_pinjam->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Kembali (Plan)</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->peminjaman->tanggal_kembali->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Dikembalikan</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->tanggal_dikembalikan->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Denda</dt>
                    <dd class="mt-1">
                        @if($pengembalian->denda > 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Tidak ada denda
                            </span>
                        @endif
                    </dd>
                </div>

                @if($pengembalian->keterangan)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->keterangan }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Detail Alat yang Dikembalikan</h5>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Alat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengembalian->peminjaman->detailPeminjaman as $detail)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->alat->nama_alat }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->alat->kategori->nama_kategori }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $detail->jumlah }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('peminjam.pengembalian.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>
</div>
@endsection
