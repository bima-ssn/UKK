@extends('layouts.app')

@section('title', 'Edit Alat')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Edit Alat</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.alat.update', $alat) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                <select name="kategori_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kategori_id') border-red-500 @enderror" required>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $alat->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Alat</label>
                <input type="text" name="nama_alat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama_alat') border-red-500 @enderror" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
                @error('nama_alat')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                <input type="number" name="stok" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stok') border-red-500 @enderror" value="{{ old('stok', $alat->stok) }}" min="0" required>
                @error('stok')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi</label>
                <select name="kondisi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kondisi') border-red-500 @enderror" required>
                    <option value="baik" {{ old('kondisi', $alat->kondisi) === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak" {{ old('kondisi', $alat->kondisi) === 'rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="perbaikan" {{ old('kondisi', $alat->kondisi) === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
                @error('kondisi')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update
                </button>
                <a href="{{ route('admin.alat.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
