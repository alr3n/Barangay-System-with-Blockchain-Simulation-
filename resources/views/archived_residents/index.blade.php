@extends('layouts.app')
@section('title', 'Archived Residents')
@section('page-title', 'Archived Residents')
@section('page-subtitle', 'Audit trail of deceased, inactive, transferred, and removed resident records')

@section('content')
<div class="py-4 space-y-5">

    {{-- Stats cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Archived</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-red-600 uppercase tracking-wide font-medium">Deceased</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['deceased']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-gray-600 uppercase tracking-wide font-medium">Inactive</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['inactive']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-yellow-700 uppercase tracking-wide font-medium">Transferred</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['transferred']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4">
            <p class="text-xs text-purple-700 uppercase tracking-wide font-medium">Deleted</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['deleted']) }}</p>
        </div>
    </div>

    {{-- Filter toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('archived_residents.index') }}" class="flex flex-wrap gap-2" data-auto-search>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name or code..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-60">

            <select name="reason" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Reasons</option>
                <option value="deceased"    @selected(request('reason')=='deceased')>Deceased</option>
                <option value="inactive"    @selected(request('reason')=='inactive')>Inactive</option>
                <option value="transferred" @selected(request('reason')=='transferred')>Transferred</option>
                <option value="deleted"     @selected(request('reason')=='deleted')>Deleted</option>
            </select>

            <input type="date" name="from" value="{{ request('from') }}" placeholder="From"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="to" value="{{ request('to') }}" placeholder="To"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            @if(request()->hasAny(['search','reason','from','to']))
                <a href="{{ route('archived_residents.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
            <noscript>
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
            </noscript>
        </form>

        <a href="{{ route('archived_residents.export', request()->query()) }}"
           class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Audit notice --}}
    <div class="bg-purple-50 border border-purple-200 rounded-xl px-5 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-purple-800">Read-Only Audit Archive</p>
            <p class="text-xs text-purple-700 mt-0.5">These records are snapshots created at the moment of status change or removal. They are preserved permanently for reporting and audit. Records cannot be edited from this view.</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Resident Code</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Full Name</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Age</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Reason</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Archived On</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Archived By</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($archived as $a)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $a->resident_code }}</td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $a->full_name }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst($a->gender) }} · {{ ucfirst($a->civil_status) }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $a->age }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $a->reason_badge_class }}">
                                {{ ucfirst($a->archive_reason) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600 text-xs">
                            <p>{{ $a->archived_at->format('M d, Y') }}</p>
                            <p class="text-gray-400">{{ $a->archived_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-600 text-xs">{{ $a->archivedBy?->name ?? 'System' }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('archived_residents.show', $a) }}" class="text-blue-600 hover:text-blue-800 text-xs">View Snapshot</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            No archived records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($archived->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">
                Showing {{ $archived->firstItem() }}–{{ $archived->lastItem() }} of {{ $archived->total() }} archived records
            </p>
            {{ $archived->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
