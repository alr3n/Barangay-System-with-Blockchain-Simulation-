@extends('layouts.app')
@section('title', 'Blotter Details')
@section('page-title', 'Blotter Record')
@section('page-subtitle', $blotter->blotter_number)

@section('content')
<div class="py-4 max-w-4xl space-y-5">

    {{-- Header actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('blotter.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to list
        </a>
        <div class="flex gap-2">
            <a href="{{ route('blotter.print', $blotter) }}" target="_blank"
               class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </a>

            {{-- Edit and Delete are HIDDEN when status is resolved --}}
            @if($blotter->status !== 'resolved')
                <a href="{{ route('blotter.edit', $blotter) }}"
                   class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Update Status
                </a>
                <form method="POST" action="{{ route('blotter.destroy', $blotter) }}"
                      onsubmit="return confirm('Delete this blotter record? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            @else
                {{-- Resolved: only show why actions are unavailable --}}
                <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-500 px-4 py-2 rounded-lg text-sm cursor-not-allowed" title="Resolved cases are read-only">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Read-Only (Resolved)
                </span>
            @endif
        </div>
    </div>

    {{-- Resolved banner --}}
    @if($blotter->status === 'resolved')
    <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-green-800">Case Resolved — Record Locked</p>
            <p class="text-xs text-green-700 mt-0.5">
                This blotter was resolved on
                <strong>{{ $blotter->resolved_date?->format('F d, Y') ?? 'unknown date' }}</strong>
                and is now permanently read-only. No further modifications or deletions are permitted.
            </p>
        </div>
    </div>
    @endif

    {{-- Pending/Ongoing notice --}}
    @if($blotter->status !== 'resolved')
    <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-blue-800">Incident details are locked</p>
            <p class="text-xs text-blue-700 mt-0.5">After creation, only the case <strong>status</strong> and resolution notes can be updated. Incident details remain immutable for audit integrity.</p>
        </div>
    </div>
    @endif

    {{-- Record header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700 text-sm">Case Information</h3>
            @php
                $sc = match($blotter->status) {
                    'pending'  => 'bg-yellow-100 text-yellow-700',
                    'ongoing'  => 'bg-blue-100 text-blue-700',
                    'resolved' => 'bg-green-100 text-green-700',
                    default    => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $sc }}">{{ ucfirst($blotter->status) }}</span>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 mb-1">Blotter Number</p>
                <p class="font-mono font-semibold text-gray-800">{{ $blotter->blotter_number }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Filed By</p>
                <p class="font-medium text-gray-800">{{ $blotter->handler?->name ?? 'System' }}</p>
                <p class="text-xs text-gray-400">{{ $blotter->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Incident Date & Time</p>
                <p class="font-medium text-gray-800">
                    {{ $blotter->incident_date->format('F d, Y') }}
                    @if($blotter->incident_time) at {{ \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') }}@endif
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Incident Type</p>
                <p class="font-medium text-gray-800">{{ $blotter->incident_type }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 mb-1">Location</p>
                <p class="font-medium text-gray-800">{{ $blotter->incident_location }}</p>
            </div>
        </div>
    </div>

    {{-- Parties --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                <h3 class="font-semibold text-blue-800 text-sm">Complainant</h3>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Name</p>
                    <p class="font-medium text-gray-800">{{ $blotter->complainant_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Address</p>
                    <p class="text-gray-700">{{ $blotter->complainant_address }}</p>
                </div>
                @if($blotter->complainant_contact)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Contact</p>
                    <p class="font-mono text-gray-700">{{ $blotter->complainant_contact }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50">
                <h3 class="font-semibold text-red-800 text-sm">Respondent</h3>
            </div>
            <div class="p-6 space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Name</p>
                    <p class="font-medium text-gray-800">{{ $blotter->respondent_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Address</p>
                    <p class="text-gray-700">{{ $blotter->respondent_address }}</p>
                </div>
                @if($blotter->respondent_contact)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Contact</p>
                    <p class="font-mono text-gray-700">{{ $blotter->respondent_contact }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Narration --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Incident Narration</h3>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg px-4 py-3 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $blotter->incident_details }}</div>
        </div>
    </div>

    {{-- Resolution --}}
    @if($blotter->resolution_notes || $blotter->resolved_date)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-green-50">
            <h3 class="font-semibold text-green-800 text-sm">Resolution</h3>
        </div>
        <div class="p-6 space-y-3 text-sm">
            @if($blotter->resolved_date)
            <div>
                <p class="text-xs text-gray-500 mb-1">Resolved On</p>
                <p class="font-medium text-gray-800">{{ $blotter->resolved_date->format('F d, Y') }}</p>
            </div>
            @endif
            @if($blotter->resolution_notes)
            <div>
                <p class="text-xs text-gray-500 mb-1">Resolution Notes</p>
                <div class="bg-gray-50 rounded-lg px-4 py-3 text-gray-700 leading-relaxed whitespace-pre-line">{{ $blotter->resolution_notes }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
