@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')
<div>
    <h2 class="text-2xl font-bold mb-6">Detail Log Aktivitas</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <dl class="space-y-3">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $log->id }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">User</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'System' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Aktivitas</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $log->aktivitas }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $log->waktu->format('d/m/Y H:i:s') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $log->ip_address ?? '-' }}</dd>
            </div>

            @if($log->user_agent)
                <div>
                    <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $log->user_agent }}</dd>
                </div>
            @endif
        </dl>

        <div class="mt-6">
            <a href="{{ route('admin.log.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
