@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Detail Kategori</h2>
        <a href="{{ route('admin.kategori.edit', $kategori) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Edit
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kategori->id }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Nama Kategori</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kategori->nama_kategori }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kategori->keterangan ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Jumlah Alat</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $kategori->alat->count() }}</dd>
            </div>
        </dl>

        <div class="mt-6">
            <a href="{{ route('admin.kategori.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
