@extends('layouts.app')

@section('title', 'Daftar Alat')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Daftar Alat Tersedia</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($alats as $alat)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ $alat->nama_alat }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $alat->kategori->nama_kategori }}</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-xs text-gray-500">Stok:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $alat->stok > 10 ? 'bg-green-100 text-green-800' : ($alat->stok > 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $alat->stok }}
                            </span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Kondisi:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $alat->kondisi === 'baik' ? 'bg-green-100 text-green-800' : ($alat->kondisi === 'rusak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($alat->kondisi) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('peminjam.alat.show', $alat) }}" class="block w-full bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">Tidak ada alat tersedia</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $alats->links() }}
    </div>
</div>
@endsection
