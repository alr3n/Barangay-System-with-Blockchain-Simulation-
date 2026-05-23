@extends('layouts.app')
@section('title', 'Residents')
@section('page-title', 'Resident Management')
@section('page-subtitle', 'Manage registered residents of Barangay San Jose')

@section('content')
<div class="py-4 space-y-4">

    {{-- Toolbar: auto-searching filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('residents.index') }}" class="flex flex-wrap gap-2" data-auto-search>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name, code, contact..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="active"      @selected(request('status')=='active')>Active</option>
                <option value="inactive"    @selected(request('status')=='inactive')>Inactive</option>
                <option value="deceased"    @selected(request('status')=='deceased')>Deceased</option>
                <option value="transferred" @selected(request('status')=='transferred')>Transferred</option>
            </select>
            <select name="gender" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Gender</option>
                <option value="male"   @selected(request('gender')=='male')>Male</option>
                <option value="female" @selected(request('gender')=='female')>Female</option>
            </select>
            @if(request()->hasAny(['search','status','gender']))
                <a href="{{ route('residents.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
            <noscript>
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">Filter</button>
            </noscript>
        </form>
        <a href="{{ route('residents.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Resident
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Resident Code</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Full Name</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Age / Gender</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Civil Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Contact</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Household</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($residents as $resident)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $resident->resident_code }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $resident->gender === 'male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                    {{ strtoupper(substr($resident->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $resident->full_name }}</p>
                                    <p class="text-xs text-gray-400 truncate max-w-xs">{{ $resident->address }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $resident->age }} / {{ ucfirst($resident->gender) }}</td>
                        <td class="px-5 py-3 text-gray-600 capitalize">{{ $resident->civil_status }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $resident->contact_number ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">
                            @if($resident->household)
                                <a href="{{ route('households.show', $resident->household) }}" class="text-blue-600 hover:underline text-xs">{{ $resident->household->household_code }}</a>
                                @if($resident->is_household_head) <span class="text-xs text-green-600 font-medium">(Head)</span> @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $statusColor = match($resident->resident_status) {
                                    'active'      => 'bg-green-100 text-green-700',
                                    'inactive'    => 'bg-gray-100 text-gray-600',
                                    'deceased'    => 'bg-red-100 text-red-700',
                                    'transferred' => 'bg-yellow-100 text-yellow-700',
                                    default       => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($resident->resident_status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('residents.show', $resident) }}" class="text-blue-600 hover:text-blue-800 p-1 rounded" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('residents.edit', $resident) }}" class="text-gray-500 hover:text-gray-700 p-1 rounded" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('residents.destroy', $resident) }}" onsubmit="return confirm('Delete this resident record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            No residents found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($residents->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Showing {{ $residents->firstItem() }}–{{ $residents->lastItem() }} of {{ $residents->total() }} residents</p>
            {{ $residents->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
