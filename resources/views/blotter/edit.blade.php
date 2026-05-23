@extends('layouts.app')
@section('title', 'Update Blotter Status')
@section('page-title', 'Update Blotter Status')
@section('page-subtitle', $blotter->blotter_number)

@section('content')
<div class="py-4 max-w-4xl">

    {{-- Notice: details are immutable --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-3 mb-5 flex items-start gap-3">
        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1z"/>
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-yellow-800">Incident details cannot be modified</p>
            <p class="text-xs text-yellow-700 mt-0.5">Blotter records are immutable after creation. Only the case STATUS and resolution notes are editable here. Once marked Resolved, the entire record becomes read-only.</p>
        </div>
    </div>

    {{-- Read-only summary of the blotter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700 text-sm">Incident Record (Read-Only)</h3>
            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $blotter->blotter_number }}</span>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <p class="text-xs text-gray-500 mb-1">Complainant</p>
                <p class="font-medium text-gray-800">{{ $blotter->complainant_name }}</p>
                <p class="text-xs text-gray-500">{{ $blotter->complainant_address }}</p>
                @if($blotter->complainant_contact)<p class="text-xs text-gray-500">{{ $blotter->complainant_contact }}</p>@endif
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Respondent</p>
                <p class="font-medium text-gray-800">{{ $blotter->respondent_name }}</p>
                <p class="text-xs text-gray-500">{{ $blotter->respondent_address }}</p>
                @if($blotter->respondent_contact)<p class="text-xs text-gray-500">{{ $blotter->respondent_contact }}</p>@endif
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Incident Date / Time</p>
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
            <div class="md:col-span-2">
                <p class="text-xs text-gray-500 mb-1">Narration</p>
                <div class="bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 leading-relaxed">{{ $blotter->incident_details }}</div>
            </div>
        </div>
    </div>

    {{-- Status update form — the only editable section --}}
    <form method="POST" action="{{ route('blotter.update', $blotter) }}" novalidate>
        @csrf @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border-2 border-blue-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-100 bg-blue-50">
                <h3 class="font-semibold text-blue-800 text-sm">Case Status (Editable)</h3>
                <p class="text-xs text-blue-600 mt-0.5">Update the case status. Marking as Resolved will lock this record permanently.</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pending"  @selected(old('status', $blotter->status)=='pending')>Pending</option>
                        <option value="ongoing"  @selected(old('status', $blotter->status)=='ongoing')>Ongoing</option>
                        <option value="resolved" @selected(old('status', $blotter->status)=='resolved')>Resolved (final)</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div id="resolved_date_group" class="{{ old('status', $blotter->status) !== 'resolved' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Resolved Date</label>
                    <input type="date" name="resolved_date" value="{{ old('resolved_date', $blotter->resolved_date?->format('Y-m-d') ?? date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('resolved_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Resolution Notes</label>
                    <textarea name="resolution_notes" rows="4" maxlength="2000" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Describe the actions taken, agreements reached, or current investigation progress...">{{ old('resolution_notes', $blotter->resolution_notes) }}</textarea>
                </div>

                <div id="resolved-warning" class="md:col-span-3 hidden bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">This will permanently lock the case</p>
                            <p class="text-xs text-red-700 mt-0.5">Once saved as <strong>Resolved</strong>, this blotter cannot be edited or deleted. Make sure all resolution notes are complete.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900">Update Status</button>
            <a href="{{ route('blotter.show', $blotter) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const statusSelect = document.getElementById('status');
    const resolvedGroup = document.getElementById('resolved_date_group');
    const resolvedWarning = document.getElementById('resolved-warning');

    const toggle = (val) => {
        const isResolved = val === 'resolved';
        resolvedGroup.classList.toggle('hidden', !isResolved);
        resolvedWarning.classList.toggle('hidden', !isResolved);
    };
    statusSelect.addEventListener('change', e => toggle(e.target.value));
    toggle(statusSelect.value);
</script>
@endpush
@endsection
