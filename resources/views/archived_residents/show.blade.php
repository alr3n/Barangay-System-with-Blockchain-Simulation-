@extends('layouts.app')
@section('title', 'Archive Snapshot')
@section('page-title', 'Archive Snapshot')
@section('page-subtitle', $archive->resident_code)

@section('content')
<div class="py-4 max-w-4xl space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('archived_residents.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to archive
        </a>
        @if($archive->originalResident)
        <a href="{{ route('residents.show', $archive->originalResident) }}"
           class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
            View current record →
        </a>
        @endif
    </div>

    {{-- Read-only banner --}}
    <div class="bg-purple-50 border border-purple-200 rounded-xl px-5 py-3 flex items-start gap-3">
        <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-purple-800">Read-Only Snapshot</p>
            <p class="text-xs text-purple-700 mt-0.5">This is a permanent snapshot taken at the time of archiving. The data shown reflects the resident's information at that moment and cannot be modified.</p>
        </div>
    </div>

    {{-- Audit metadata --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Archive Information</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 mb-1">Archive Reason</p>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $archive->reason_badge_class }}">
                    {{ ucfirst($archive->archive_reason) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Archived On</p>
                <p class="font-medium text-gray-800">{{ $archive->archived_at->format('F d, Y') }}</p>
                <p class="text-xs text-gray-400">{{ $archive->archived_at->format('h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Archived By</p>
                <p class="font-medium text-gray-800">{{ $archive->archivedBy?->name ?? 'System' }}</p>
                @if($archive->archivedBy?->email)
                <p class="text-xs text-gray-400">{{ $archive->archivedBy->email }}</p>
                @endif
            </div>
            @if($archive->archive_notes)
            <div class="md:col-span-3">
                <p class="text-xs text-gray-500 mb-1">Notes</p>
                <div class="bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700">{{ $archive->archive_notes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Resident snapshot data --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-700 text-sm">Resident Snapshot</h3>
            <p class="text-xs text-gray-400 mt-0.5">Data as it was at the time of archiving</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 mb-1">Resident Code</p>
                <p class="font-mono font-semibold text-gray-800">{{ $archive->resident_code }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 mb-1">Full Name</p>
                <p class="font-medium text-gray-800">{{ $archive->full_name }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Birthdate</p>
                <p class="font-medium text-gray-800">{{ $archive->birthdate->format('F d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Age (at archive)</p>
                <p class="font-medium text-gray-800">{{ $archive->age }} years old</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Gender</p>
                <p class="font-medium text-gray-800 capitalize">{{ $archive->gender }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Civil Status</p>
                <p class="font-medium text-gray-800 capitalize">{{ $archive->civil_status }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Contact</p>
                <p class="font-mono text-gray-800">{{ $archive->contact_number ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Occupation</p>
                <p class="font-medium text-gray-800">{{ $archive->occupation ?? '—' }}</p>
            </div>

            <div class="md:col-span-3">
                <p class="text-xs text-gray-500 mb-1">Address</p>
                <p class="text-gray-700">{{ $archive->address }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Previous Household</p>
                <p class="font-medium text-gray-800">{{ $archive->previous_household_code ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">First-Time Job Seeker</p>
                @if($archive->is_first_time_job_seeker)
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Yes</span>
                @else
                    <span class="text-gray-400">No</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Link to live record if it still exists --}}
    @if($archive->originalResident)
    <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-3 text-sm">
        <p class="text-blue-800">
            <strong>Note:</strong> The resident record still exists in the main system.
            <a href="{{ route('residents.show', $archive->originalResident) }}" class="underline hover:no-underline">
                View current resident profile →
            </a>
        </p>
    </div>
    @endif
</div>
@endsection
