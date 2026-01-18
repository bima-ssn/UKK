@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Ajukan Peminjaman</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" id="peminjamanForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_pinjam') border-red-500 @enderror" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                    @error('tanggal_pinjam')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_kembali') border-red-500 @enderror" value="{{ old('tanggal_kembali') }}" required>
                    @error('tanggal_kembali')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                <textarea name="keterangan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('keterangan') border-red-500 @enderror" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="my-6">
            <h5 class="text-lg font-semibold mb-4">Pilih Alat</h5>
            <div id="alatContainer">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-3 alat-row">
                    <div class="md:col-span-5">
                        <select name="alat[0][id]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline alat-select" required>
                            <option value="">Pilih Alat</option>
                            @foreach($alats as $alat)
                                @if($alat->stok > 0)
                                    <option value="{{ $alat->id }}" data-stok="{{ $alat->stok }}" {{ request('alat_id') == $alat->id ? 'selected' : '' }}>
                                        {{ $alat->nama_alat }} - Stok: {{ $alat->stok }} ({{ $alat->kategori->nama_kategori }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-5">
                        <input type="number" name="alat[0][jumlah]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline jumlah-input" min="1" placeholder="Jumlah" required>
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded remove-alat hidden">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm" id="tambahAlat">
                + Tambah Alat
            </button>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let alatIndex = 1;
    document.getElementById('tambahAlat').addEventListener('click', function() {
        const container = document.getElementById('alatContainer');
        const newRow = container.firstElementChild.cloneNode(true);
        newRow.querySelectorAll('select, input').forEach(el => {
            el.name = el.name.replace('[0]', `[${alatIndex}]`);
            el.value = '';
        });
        newRow.querySelector('.remove-alat').classList.remove('hidden');
        container.appendChild(newRow);
        alatIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-alat')) {
            if (document.querySelectorAll('.alat-row').length > 1) {
                e.target.closest('.alat-row').remove();
            }
        }
    });
</script>
@endpush
@endsection
