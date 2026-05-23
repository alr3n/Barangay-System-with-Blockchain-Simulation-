@extends('layouts.app')
@section('title', 'User Details')
@section('page-title', 'User Account')
@section('page-subtitle', $user->email)

@section('content')
<div class="py-4 max-w-3xl space-y-5">

    <div class="flex items-center justify-between">
        <a href="{{ route('users.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to users
        </a>
        <a href="{{ route('users.edit', $user) }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Account
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4"
                 style="background-color: {{ $user->isAdmin() ? '#1E3A5F' : '#64748B' }}">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
            <div class="mt-3 flex justify-center gap-2">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                    {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="mt-5 pt-5 border-t border-gray-100 text-left space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium text-gray-800">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Last updated</span>
                    <span class="font-medium text-gray-800">{{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Activity Log --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Recent Activity</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentLogs as $log)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @php
                            $dotColor = match($log->action) {
                                'create' => 'bg-green-400',
                                'update' => 'bg-blue-400',
                                'delete' => 'bg-red-400',
                                'login'  => 'bg-purple-400',
                                'logout' => 'bg-gray-400',
                                default  => 'bg-gray-300',
                            };
                        @endphp
                        <span class="w-2 h-2 rounded-full {{ $dotColor }} flex-shrink-0"></span>
                        <div class="min-w-0">
                            <p class="text-sm text-gray-700 truncate max-w-xs">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400">{{ $log->module }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 flex-shrink-0 ml-4">
                        {{ $log->created_at->diffForHumans() }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-gray-400">No activity logged for this user.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
