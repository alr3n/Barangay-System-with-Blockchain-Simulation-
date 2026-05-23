@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Manage system users and access levels')

@section('content')
<div class="py-4 space-y-5">

    {{-- Stats row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Users</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $users->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-xs text-blue-600 uppercase tracking-wide font-medium">Admins</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $adminCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Staff</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $staffCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-xs text-green-600 uppercase tracking-wide font-medium">Active</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activeCount }}</p>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-2" data-auto-search>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name or email…"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
            <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="admin" @selected(request('role')=='admin')>Admin</option>
                <option value="staff" @selected(request('role')=='staff')>Staff</option>
            </select>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="1" @selected(request('status')==='1')>Active</option>
                <option value="0" @selected(request('status')==='0')>Inactive</option>
            </select>
            @if(request()->hasAny(['search','role','status']))
                <a href="{{ route('users.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
            <noscript>
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
            </noscript>
        </form>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">User</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Created</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50/60 transition-colors {{ $u->id === auth()->id() ? 'bg-blue-50/30' : '' }}">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                     style="background:{{ $u->isAdmin() ? '#1E3A5F' : '#64748B' }}">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 leading-tight">
                                        {{ $u->name }}
                                        @if($u->id === auth()->id())
                                            <span class="ml-1 text-xs text-blue-500 font-normal">(You)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $u->email }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $u->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $u->isAdmin() ? 'Admin' : 'Staff' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-green-500' : 'bg-red-400' }} inline-block"></span>
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $u->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('users.edit', $u) }}"
                                   class="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg transition-colors"
                                   title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                @if($u->id !== auth()->id())
                                <button type="button"
                                        class="inline-flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-lg transition-colors"
                                        onclick="openDeleteModal('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ $u->email }}')"
                                        title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                                @else
                                <span class="inline-flex items-center gap-1.5 text-xs text-gray-300 bg-gray-50 px-2.5 py-1.5 rounded-lg cursor-not-allowed select-none"
                                      title="Cannot delete your own account">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-6V9m0 0V7m0 2h2m-2 0H10"/>
                                    </svg>
                                    Protected
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-gray-400">No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users</p>
            {{ $users->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    {{-- Dialog --}}
    <div class="relative flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 relative">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Delete User Account</h3>
                    <p class="text-xs text-gray-500 mt-0.5">This action cannot be undone.</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl px-4 py-3 mb-5">
                <p class="text-sm font-medium text-gray-800" id="modal-user-name"></p>
                <p class="text-xs text-gray-500 mt-0.5" id="modal-user-email"></p>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to permanently delete this user account?
                All associated activity logs will be retained for audit purposes.
            </p>

            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <form id="delete-form" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-red-700 transition-colors">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDeleteModal(userId, userName, userEmail) {
    document.getElementById('modal-user-name').textContent = userName;
    document.getElementById('modal-user-email').textContent = userEmail;
    document.getElementById('delete-form').action = '/users/' + userId;
    document.getElementById('delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDeleteModal(); });
</script>
@endpush
