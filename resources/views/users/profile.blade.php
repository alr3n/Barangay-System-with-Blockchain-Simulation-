@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your account information')

@section('content')
<div class="py-4 space-y-5 max-w-3xl">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
            <div class="mt-3">
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                    {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="mt-5 pt-5 border-t border-gray-100 text-left space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Member since</span>
                    <span class="font-medium text-gray-800">{{ $user->created_at->format('M Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Account status</span>
                    <span class="font-medium text-green-600">Active</span>
                </div>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="lg:col-span-2 space-y-5">
            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf @method('PATCH')

                {{-- Basic Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-700 text-sm">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                                   required>
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                                   required>
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-700 text-sm">Change Password</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Leave blank to keep current password</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-400 @enderror">
                            @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                            <input type="password" name="password" minlength="8"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                                   placeholder="Min. 8 characters">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" minlength="8"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition-colors">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm">My Recent Activity</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentLogs as $log)
            <div class="px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></span>
                    <div>
                        <p class="text-sm text-gray-700">{{ $log->description }}</p>
                        <p class="text-xs text-gray-400">{{ $log->module }}</p>
                    </div>
                </div>
                <span class="text-xs text-gray-400 flex-shrink-0 ml-4">{{ $log->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="px-6 py-8 text-sm text-gray-400 text-center">No activity recorded yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
