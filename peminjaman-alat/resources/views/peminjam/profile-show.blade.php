@extends('layouts.app')

@section('title', 'Profile Peminjam')

@section('content')
@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
</style>
@endpush

<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Profile Header -->
    <div class="profile-header rounded-xl shadow-lg p-6 text-white mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-6 mb-4 md:mb-0">
                <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-3xl font-bold text-white border-4 border-white/30">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">{{ auth()->user()->name }}</h1>
                    <p class="text-blue-100 text-lg mt-1">Pengguna Peminjam Alat</p>
                    <span class="inline-block mt-2 px-4 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-6 py-2.5 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-300 shadow-md hover:shadow-lg">
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - User Info & Privileges -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">Informasi Personal</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="text-gray-900 font-semibold text-lg">{{ auth()->user()->name }}</p>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 font-semibold text-lg break-all">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-500">Role</label>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                            </svg>
                            <span class="text-gray-900 font-semibold">Peminjam</span>
                        </div>
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-gray-900 font-semibold">Aktif</span>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 space-y-1">
                        <label class="block text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                        <p class="text-gray-900 font-semibold text-lg">
                            {{ auth()->user()->created_at->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Peminjam Privileges -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">Hak & Akses Peminjam</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @php
                        $privileges = [
                            ['name' => 'Cari Alat', 'icon' => 'search', 'color' => 'blue'],
                            ['name' => 'Pinjam Alat', 'icon' => 'plus-circle', 'color' => 'green'],
                            ['name' => 'Kembalikan Alat', 'icon' => 'arrow-left-circle', 'color' => 'orange'],
                            ['name' => 'Status Peminjaman', 'icon' => 'check-circle', 'color' => 'purple'],
                            ['name' => 'Riwayat Pinjaman', 'icon' => 'history', 'color' => 'yellow'],
                            ['name' => 'Dashboard Pribadi', 'icon' => 'home', 'color' => 'indigo'],
                        ];
                    @endphp
                    
                    @foreach($privileges as $privilege)
                        <div class="flex items-center space-x-3 p-4 bg-{{ $privilege['color'] }}-50 rounded-lg border border-{{ $privilege['color'] }}-200 hover:border-{{ $privilege['color'] }}-300 transition-colors">
                            @switch($privilege['icon'])
                                @case('search')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    @break
                                @case('plus-circle')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    @break
                                @case('arrow-left-circle')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l-3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('check-circle')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('history')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('home')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    @break
                            @endswitch
                            <span class="font-semibold text-gray-800 text-sm">{{ $privilege['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column - Stats & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Borrowing Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Peminjaman</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Total Pinjaman</span>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">{{ $stats['total_pinjaman'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Selesai</span>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $stats['selesai'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Menunggu</span>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Diproses</span>
                        </div>
                        <span class="text-2xl font-bold text-orange-600">{{ $stats['diproses'] ?? 0 }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Alat Tersedia</span>
                        </div>
                        <span class="text-2xl font-bold text-purple-600">{{ $stats['alats_tersedia'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('peminjam.alat.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Cari Alat</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('peminjam.peminjaman.create') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Pinjam Alat</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('peminjam.peminjaman.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>Pinjaman Saya</span>
                        </div>
                    </a>

                    <a href="{{ route('peminjam.riwayat.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Riwayat</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Borrowing -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pinjaman Terbaru</h3>
                
                <div class="space-y-3">
                    @if(isset($recentBorrowings) && count($recentBorrowings) > 0)
                        @foreach($recentBorrowings as $borrowing)
                            <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $borrowing['alat'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $borrowing['tanggal'] }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $borrowing['status_color'] }}">
                                    {{ $borrowing['status'] }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <svg class="w-12 h-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 mt-2 text-sm">Belum ada pinjaman</p>
                            <a href="{{ route('peminjam.alat.index') }}" class="inline-block mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Cari alat untuk dipinjam →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection