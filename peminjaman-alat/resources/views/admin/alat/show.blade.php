@extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Detail Alat</h2>
        <a href="{{ route('admin.alat.edit', $alat) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Edit
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $alat->id }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Nama Alat</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $alat->nama_alat }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $alat->kategori->nama_kategori }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Stok</dt>
                <dd class="mt-1">
                    <span class="px-2 py-1 text-xs rounded-full {{ $alat->stok > 10 ? 'bg-green-100 text-green-800' : ($alat->stok > 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $alat->stok }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Kondisi</dt>
                <dd class="mt-1">
                    <span class="px-2 py-1 text-xs rounded-full {{ $alat->kondisi === 'baik' ? 'bg-green-100 text-green-800' : ($alat->kondisi === 'rusak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($alat->kondisi) }}
                    </span>
                </dd>
            </div>
        </dl>

        <div class="mt-6">
            <a href="{{ route('admin.alat.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
