@extends('layouts.app')
@section('title', 'File Blotter')
@section('page-title', 'File Blotter Record')
@section('page-subtitle', 'Record a new barangay incident (details are locked after creation)')

@section('content')
<div class="py-4 max-w-4xl">
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-3 mb-5 flex items-start gap-3">
        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-yellow-800">Important: Records are immutable after creation</p>
            <p class="text-xs text-yellow-700 mt-0.5">Once filed, blotter details cannot be edited. Only the case STATUS may be updated by authorized staff. Resolved cases become permanently read-only.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('blotter.store') }}" novalidate>
        @csrf

        <div class="space-y-5">

            {{-- Complainant --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                    <h3 class="font-semibold text-blue-800 text-sm">Complainant Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="complainant_name" value="{{ old('complainant_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('complainant_name') border-red-400 @enderror" required>
                        @error('complainant_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Number</label>
                        <input type="tel" name="complainant_contact" value="{{ old('complainant_contact') }}" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}" maxlength="11" minlength="11" inputmode="numeric" data-ph-mobile class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('complainant_contact') border-red-400 @enderror">
                        @error('complainant_contact')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                        <input type="text" name="complainant_address" value="{{ old('complainant_address') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('complainant_address') border-red-400 @enderror" required>
                        @error('complainant_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Respondent --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-red-50">
                    <h3 class="font-semibold text-red-800 text-sm">Respondent Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="respondent_name" value="{{ old('respondent_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Number</label>
                        <input type="tel" name="respondent_contact" value="{{ old('respondent_contact') }}" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}" maxlength="11" minlength="11" inputmode="numeric" data-ph-mobile class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('respondent_contact') border-red-400 @enderror">
                        @error('respondent_contact')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                        <input type="text" name="respondent_address" value="{{ old('respondent_address') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>

            {{-- Incident --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Incident Details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Incident Date <span class="text-red-500">*</span></label>
                        <input type="date" name="incident_date" value="{{ old('incident_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Incident Time</label>
                        <input type="time" name="incident_time" value="{{ old('incident_time') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Incident Type <span class="text-red-500">*</span></label>
                        <select name="incident_type" data-conditional-target="#incident-type-other-group" data-conditional-value="Others" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select type...</option>
                            @foreach($incidentTypes as $type)
                                <option value="{{ $type }}" @selected(old('incident_type')==$type)>{{ $type }}</option>
                            @endforeach
                            <option value="Others" @selected(old('incident_type')=='Others')>Others (specify)</option>
                        </select>
                    </div>
                    <div id="incident-type-other-group" class="md:col-span-3 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Specify Incident Type <span class="text-red-500">*</span></label>
                        <input type="text" name="incident_type_other" value="{{ old('incident_type_other') }}" placeholder="Describe the type of incident" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('incident_type_other') border-red-400 @enderror">
                        @error('incident_type_other')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Incident Location <span class="text-red-500">*</span></label>
                        <input type="text" name="incident_location" value="{{ old('incident_location') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Incident Details / Narration <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-400 font-normal">(minimum 50 characters)</span>
                        </label>
                        <textarea name="incident_details" id="incident_details" rows="5" minlength="50" maxlength="5000" data-min-chars="50"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('incident_details') border-red-400 @enderror"
                                  required placeholder="Provide a complete description of the incident. Include time, location, parties involved, sequence of events, and any other relevant details...">{{ old('incident_details') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('incident_details')<p class="text-red-500 text-xs">{{ $message }}</p>@else<p class="text-xs text-gray-400">Provide enough detail for proper review.</p>@enderror
                            <p class="text-xs text-gray-400"><span id="char-count">0</span> / 50 minimum</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900">File Blotter Record</button>
                <a href="{{ route('blotter.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // PH mobile validation
    document.querySelectorAll('[data-ph-mobile]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
        input.addEventListener('blur', function () {
            if (this.value && !/^09\d{9}$/.test(this.value)) {
                this.setCustomValidity('Must be exactly 11 digits starting with 09.');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
    });

    // Live char-count for incident details
    const details = document.getElementById('incident_details');
    const counter = document.getElementById('char-count');
    if (details && counter) {
        const update = () => {
            const len = details.value.trim().length;
            counter.textContent = len;
            counter.classList.toggle('text-green-600', len >= 50);
            counter.classList.toggle('text-red-500', len > 0 && len < 50);
            if (len < 50) {
                details.setCustomValidity('Please provide at least 50 characters for a complete description.');
            } else {
                details.setCustomValidity('');
            }
        };
        details.addEventListener('input', update);
        update();
    }
</script>
@endpush
@endsection
