@extends('layouts.app')
@section('title', 'Clearances')
@section('page-title', 'Clearance & Certificate Management')
@section('page-subtitle', 'Manage all issued barangay documents')

@section('content')
<div class="py-4 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <form method="GET" action="{{ route('clearances.index') }}" class="flex flex-wrap gap-2" data-auto-search>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search control no., resident, purpose..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Types</option>
                <option value="barangay_clearance"        @selected(request('type')=='barangay_clearance')>Barangay Clearance</option>
                <option value="residency_certificate"     @selected(request('type')=='residency_certificate')>Residency Certificate</option>
                <option value="indigency_certificate"     @selected(request('type')=='indigency_certificate')>Indigency Certificate</option>
                <option value="certificate_of_employment" @selected(request('type')=='certificate_of_employment')>Certificate of Employment</option>
            </select>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="active"  @selected(request('status')=='active')>Active</option>
                <option value="revoked" @selected(request('status')=='revoked')>Revoked</option>
            </select>
            @if(request()->hasAny(['search','type','status']))
                <a href="{{ route('clearances.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
            <noscript>
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">Filter</button>
            </noscript>
        </form>
        <a href="{{ route('clearances.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Issue Document
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Control No.</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Resident</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Document Type</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Purpose</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Issued Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Fee</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($clearances as $c)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $c->control_number }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $c->resident->full_name }}</td>
                        <td class="px-5 py-3">
                            @php
                                $typeColor = match($c->document_type) {
                                    'barangay_clearance'        => 'bg-blue-100 text-blue-700',
                                    'residency_certificate'     => 'bg-green-100 text-green-700',
                                    'indigency_certificate'     => 'bg-orange-100 text-orange-700',
                                    'certificate_of_employment' => 'bg-purple-100 text-purple-700',
                                    default                     => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColor }}">{{ $c->document_type_label }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $c->purpose }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $c->issued_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-gray-700 font-medium">₱{{ number_format($c->fee, 2) }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $c->status==='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($c->status) }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('clearances.show', $c) }}" class="text-blue-600 hover:text-blue-800 p-1 rounded" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                {{-- Hide print button for revoked clearances --}}
                                @if($c->status === 'active')
                                <a href="{{ route('clearances.print', $c) }}" target="_blank" class="text-gray-500 hover:text-gray-700 p-1 rounded" title="Print">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('clearances.revoke', $c) }}" onsubmit="return confirm('Revoke this clearance?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800 p-1 rounded" title="Revoke">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                </form>
                                @else
                                    <span class="text-xs text-gray-300 italic px-1" title="Revoked documents cannot be printed">no print</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">No clearances found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($clearances->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Showing {{ $clearances->firstItem() }}–{{ $clearances->lastItem() }} of {{ $clearances->total() }}</p>
            {{ $clearances->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
