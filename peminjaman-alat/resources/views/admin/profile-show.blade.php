@extends('layouts.app')

@section('title', 'Profile Admin')

@section('content')
@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                    <p class="text-purple-100 text-lg mt-1">Administrator Sistem</p>
                    <span class="inline-block mt-2 px-4 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-6 py-2.5 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-300 shadow-md hover:shadow-lg">
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
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.951 1.488a1 1 0 001.169-1.409l-7-14z"></path>
                            </svg>
                            <span class="text-gray-900 font-semibold">Administrator</span>
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

            <!-- Admin Privileges -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">Akses & Hak Istimewa</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @php
                        $privileges = [
                            ['name' => 'Kelola Users', 'icon' => 'users', 'color' => 'blue'],
                            ['name' => 'Kelola Alat', 'icon' => 'tool', 'color' => 'green'],
                            ['name' => 'Kelola Kategori', 'icon' => 'folder', 'color' => 'purple'],
                            ['name' => 'Persetujuan Peminjaman', 'icon' => 'check-circle', 'color' => 'yellow'],
                            ['name' => 'Lihat Laporan', 'icon' => 'document-chart-bar', 'color' => 'red'],
                            ['name' => 'Log Aktivitas', 'icon' => 'clipboard-document-list', 'color' => 'indigo'],
                        ];
                    @endphp
                    
                    @foreach($privileges as $privilege)
                        <div class="flex items-center space-x-3 p-4 bg-{{ $privilege['color'] }}-50 rounded-lg border border-{{ $privilege['color'] }}-200 hover:border-{{ $privilege['color'] }}-300 transition-colors">
                            @switch($privilege['icon'])
                                @case('users')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.357a6 6 0 00-9.339-5.197"></path>
                                    </svg>
                                    @break
                                @case('tool')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    @break
                                @case('folder')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                    @break
                                @case('check-circle')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('document-chart-bar')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    @break
                                @case('clipboard-document-list')
                                    <svg class="w-6 h-6 text-{{ $privilege['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
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
            <!-- System Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Sistem</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.357a6 6 0 00-9.339-5.197"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Total Users</span>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">{{ $stats['users'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Total Alat</span>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $stats['alats'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Total Peminjaman</span>
                        </div>
                        <span class="text-2xl font-bold text-purple-600">{{ $stats['peminjamans'] ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Total Pengembalian</span>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600">{{ $stats['pengembalians'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.users.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.357a6 6 0 00-9.339-5.197"></path>
                            </svg>
                            <span>Kelola Users</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.alat.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Kelola Alat</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.log.index') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>Lihat Log Aktivitas</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection