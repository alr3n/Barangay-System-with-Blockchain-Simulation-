@extends('layouts.app')
@section('title', 'Add User')
@section('page-title', 'Add User Account')
@section('page-subtitle', 'Create a new system user')

@section('content')
<div class="py-4 max-w-xl">
    <form method="POST" action="{{ route('users.store') }}" novalidate>
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-700 text-sm">Account Information</h3>
                    <p class="text-xs text-gray-400">All fields marked * are required</p>
                </div>
            </div>
            <div class="p-6 space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Juan Dela Cruz"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                           required autofocus>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                           required>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3 mt-1">
                        <label class="role-card flex items-start gap-3 border-2 rounded-xl p-3.5 cursor-pointer transition-colors
                            {{ old('role','staff') === 'staff' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="role" value="staff" class="mt-0.5 accent-blue-600"
                                   {{ old('role','staff') === 'staff' ? 'checked' : '' }} required>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Staff</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-snug">Can manage residents, households, clearances, and blotters.</p>
                            </div>
                        </label>
                        <label class="role-card flex items-start gap-3 border-2 rounded-xl p-3.5 cursor-pointer transition-colors
                            {{ old('role') === 'admin' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="role" value="admin" class="mt-0.5 accent-blue-600"
                                   {{ old('role') === 'admin' ? 'checked' : '' }}>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Administrator</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-snug">Full access including user management and system settings.</p>
                            </div>
                        </label>
                    </div>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimum 8 characters"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                           required minlength="8">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" placeholder="Re-enter password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required minlength="8">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit"
                    class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition-colors">
                Create Account
            </button>
            <a href="{{ route('users.index') }}"
               class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.role-card').forEach(card => {
            const checked = card.querySelector('input').checked;
            card.classList.toggle('border-blue-500', checked);
            card.classList.toggle('bg-blue-50', checked);
            card.classList.toggle('border-gray-200', !checked);
        });
    });
});
</script>
@endpush
