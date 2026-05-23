@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User Account')
@section('page-subtitle', $user->name)

@section('content')
<div class="py-4 max-w-xl space-y-5">

    @if($user->id === auth()->id())
    <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center gap-3 text-sm text-blue-700">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        You are editing your own account. Role and status cannot be changed.
    </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}" novalidate>
        @csrf @method('PUT')

        {{-- Account Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-semibold text-gray-700 text-sm">Account Details</h3>
                <span class="text-xs text-gray-400 font-mono">{{ $user->email }}</span>
            </div>
            <div class="p-6 space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                           required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                           required>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Role selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                    @if($user->id === auth()->id())
                        {{-- Self: locked, can't change own role --}}
                        <div class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                            {{ ucfirst($user->role) }} <span class="text-xs text-gray-400">(cannot change own role)</span>
                        </div>
                        <input type="hidden" name="role" value="{{ $user->role }}">
                    @else
                        <div class="grid grid-cols-2 gap-3 mt-1">
                            <label class="role-card flex items-start gap-3 border-2 rounded-xl p-3.5 cursor-pointer transition-colors
                                {{ old('role', $user->role) === 'staff' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="role" value="staff" class="mt-0.5 accent-blue-600"
                                       {{ old('role', $user->role) === 'staff' ? 'checked' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Staff</p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-snug">Standard access.</p>
                                </div>
                            </label>
                            <label class="role-card flex items-start gap-3 border-2 rounded-xl p-3.5 cursor-pointer transition-colors
                                {{ old('role', $user->role) === 'admin' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="role" value="admin" class="mt-0.5 accent-blue-600"
                                       {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Administrator</p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-snug">Full access.</p>
                                </div>
                            </label>
                        </div>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @endif
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Account Status</label>
                    @if($user->id === auth()->id())
                        <div class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                            Active <span class="text-xs text-gray-400">(cannot deactivate own account)</span>
                        </div>
                        <input type="hidden" name="is_active" value="1">
                    @else
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="1" class="accent-green-600"
                                       {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <span class="inline-flex items-center gap-1.5 text-sm">
                                    <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>Active
                                </span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_active" value="0" class="accent-red-500"
                                       {{ old('is_active', $user->is_active ? '1' : '0') == '0' ? 'checked' : '' }}>
                                <span class="inline-flex items-center gap-1.5 text-sm">
                                    <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>Inactive
                                </span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Change Password</h3>
                <p class="text-xs text-gray-400 mt-0.5">Leave blank to keep the current password unchanged.</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                    <input type="password" name="password" minlength="8" placeholder="Minimum 8 characters"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" minlength="8" placeholder="Re-enter new password"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition-colors">
                Save Changes
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
