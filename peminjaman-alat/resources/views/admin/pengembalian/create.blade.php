@extends('layouts.app')

@section('title', 'Tambah Pengembalian')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Tambah Pengembalian</h2>
        <p class="text-gray-600 mt-1">Buat pengembalian baru untuk peminjaman</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.pengembalian.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Peminjaman
                    </span>
                </label>
                <select name="peminjaman_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('peminjaman_id') border-red-500 @enderror" required id="peminjamanSelect">
                    <option value="">Pilih Peminjaman</option>
                    @foreach($peminjamans as $p)
                        <option value="{{ $p->id }}" data-tanggal="{{ $p->tanggal_kembali->format('Y-m-d') }}">
                            ID: {{ $p->id }} - {{ $p->user->name ?? 'N/A' }} ({{ $p->tanggal_pinjam->format('d/m/Y') }} - {{ $p->tanggal_kembali->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('peminjaman_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal Dikembalikan
                    </span>
                </label>
                <input type="date" name="tanggal_dikembalikan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tanggal_dikembalikan') border-red-500 @enderror" value="{{ old('tanggal_dikembalikan', date('Y-m-d')) }}" required id="tanggalDikembalikan">
                @error('tanggal_dikembalikan')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Denda (Otomatis dihitung jika terlambat)
                    </span>
                </label>
                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-semibold" id="dendaDisplay" readonly value="Rp 0">
                <small class="text-gray-500 text-xs mt-1.5 block">Denda: Rp 10.000 per hari keterlambatan</small>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Keterangan
                    </span>
                </label>
                <textarea name="keterangan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('keterangan') border-red-500 @enderror" rows="3" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan
                </button>
                <a href="{{ route('admin.pengembalian.index') }}" class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('peminjamanSelect').addEventListener('change', calculateDenda);
    document.getElementById('tanggalDikembalikan').addEventListener('change', calculateDenda);

    function calculateDenda() {
        const peminjamanSelect = document.getElementById('peminjamanSelect');
        const tanggalDikembalikan = document.getElementById('tanggalDikembalikan').value;
        const dendaDisplay = document.getElementById('dendaDisplay');

        if (peminjamanSelect.value && tanggalDikembalikan) {
            const tanggalKembali = peminjamanSelect.options[peminjamanSelect.selectedIndex].dataset.tanggal;
            const tanggalKembaliDate = new Date(tanggalKembali);
            const tanggalDikembalikanDate = new Date(tanggalDikembalikan);

            if (tanggalDikembalikanDate > tanggalKembaliDate) {
                const diffTime = Math.abs(tanggalDikembalikanDate - tanggalKembaliDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const denda = diffDays * 10000;
                dendaDisplay.value = 'Rp ' + denda.toLocaleString('id-ID');
            } else {
                dendaDisplay.value = 'Rp 0';
            }
        } else {
            dendaDisplay.value = 'Rp 0';
        }
    }
</script>
@endpush
@endsection
