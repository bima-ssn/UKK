@extends('layouts.app')

@section('title', 'Tambah Pengembalian')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Tambah Pengembalian</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('petugas.pengembalian.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Peminjaman</label>
                <select name="peminjaman_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('peminjaman_id') border-red-500 @enderror" required id="peminjamanSelect">
                    <option value="">Pilih Peminjaman</option>
                    @foreach($peminjamans as $p)
                        <option value="{{ $p->id }}" data-tanggal="{{ $p->tanggal_kembali->format('Y-m-d') }}">
                            ID: {{ $p->id }} - {{ $p->user->name }} ({{ $p->tanggal_pinjam->format('d/m/Y') }} - {{ $p->tanggal_kembali->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('peminjaman_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Dikembalikan</label>
                <input type="date" name="tanggal_dikembalikan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_dikembalikan') border-red-500 @enderror" value="{{ old('tanggal_dikembalikan', date('Y-m-d')) }}" required id="tanggalDikembalikan">
                @error('tanggal_dikembalikan')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Denda (Otomatis dihitung jika terlambat)</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100" id="dendaDisplay" readonly value="Rp 0">
                <small class="text-gray-500 text-xs">Denda: Rp 10.000 per hari keterlambatan</small>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                <textarea name="keterangan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('keterangan') border-red-500 @enderror" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
                <a href="{{ route('petugas.pengembalian.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
