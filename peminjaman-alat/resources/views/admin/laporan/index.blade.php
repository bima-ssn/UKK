@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Laporan</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Laporan Peminjaman -->
        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Laporan Peminjaman</h5>
            <form action="{{ route('admin.laporan.peminjaman') }}" method="POST" target="_blank">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Cetak PDF
                </button>
            </form>
        </div>

        <!-- Laporan Pengembalian -->
        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Laporan Pengembalian</h5>
            <form action="{{ route('admin.laporan.pengembalian') }}" method="POST" target="_blank">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Cetak PDF
                </button>
            </form>
        </div>

        <!-- Laporan Alat -->
        <div class="bg-white rounded-lg shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Laporan Data Alat</h5>
            <form action="{{ route('admin.laporan.alat') }}" method="GET" target="_blank">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Cetak PDF
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
