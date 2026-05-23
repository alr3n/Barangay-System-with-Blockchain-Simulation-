@extends('layouts.app')
@section('title', 'Edit Resident')
@section('page-title', 'Edit Resident')
@section('page-subtitle', 'Update resident information')

@section('content')
<div class="py-4 max-w-4xl">
    <form method="POST" action="{{ route('residents.update', $resident) }}" novalidate>
        @csrf @method('PUT')

        <div class="space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700 text-sm">Personal Information</h3>
                    <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $resident->resident_code }}</span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $resident->first_name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $resident->last_name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Birthdate <span class="text-red-500">*</span></label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $resident->birthdate->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="male"   @selected(old('gender',$resident->gender)=='male')>Male</option>
                            <option value="female" @selected(old('gender',$resident->gender)=='female')>Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Civil Status</label>
                        <select name="civil_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['single','married','widowed','separated','annulled'] as $cs)
                            <option value="{{ $cs }}" @selected(old('civil_status',$resident->civil_status)==$cs)>{{ ucfirst($cs) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $resident->address) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Number</label>
                        <input type="tel" name="contact_number" value="{{ old('contact_number', $resident->contact_number) }}"
                               placeholder="09XXXXXXXXX" pattern="09[0-9]{9}" maxlength="11" minlength="11" inputmode="numeric" data-ph-mobile
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contact_number') border-red-400 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Format: 09XXXXXXXXX (11 digits, starts with 09)</p>
                        @error('contact_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $resident->occupation) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- First-Time Job Seeker --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                    <h3 class="font-semibold text-blue-800 text-sm">First-Time Job Seeker (RA 11261)</h3>
                </div>
                <div class="p-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_first_time_job_seeker" value="1" {{ old('is_first_time_job_seeker', $resident->is_first_time_job_seeker) ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">First-Time Job Seeker</p>
                            <p class="text-xs text-gray-500 mt-0.5">Eligible for free Certificate of Employment under RA 11261. Eligibility: 18–30 years old.</p>
                            @if($resident->first_time_job_seeker_certified_at)
                                <p class="text-xs text-green-600 mt-1">✓ Certified on {{ $resident->first_time_job_seeker_certified_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Household & Status</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Household</label>
                        <select name="household_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">None / Independent</option>
                            @foreach($households as $hh)
                            <option value="{{ $hh->id }}" @selected(old('household_id',$resident->household_id)==$hh->id)>{{ $hh->household_code }} – {{ Str::limit($hh->address, 35) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_household_head" value="1" {{ old('is_household_head', $resident->is_household_head) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Household Head
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Resident Status</label>
                        <select name="resident_status" id="status-select" data-current-status="{{ $resident->resident_status }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['active','inactive','deceased','transferred'] as $s)
                            <option value="{{ $s }}" @selected(old('resident_status',$resident->resident_status)==$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Archive warning shown when changing from active to non-active --}}
                    @if($resident->resident_status === 'active')
                    <div id="archive-warning" class="md:col-span-3 hidden bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3">
                        <div class="flex gap-2">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">A snapshot will be archived</p>
                                <p class="text-xs text-yellow-700 mt-0.5">Changing status from <strong>active</strong> to non-active creates a permanent record in the archive table. The resident's data is preserved for audit and reporting.</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Remarks</label>
                        <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('remarks', $resident->remarks) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900">Update Resident</button>
                <a href="{{ route('residents.show', $resident) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // PH mobile live filter + validation
    document.querySelectorAll('[data-ph-mobile]').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
        input.addEventListener('blur', function () {
            if (this.value && !/^09\d{9}$/.test(this.value)) {
                this.setCustomValidity('Must be exactly 11 digits starting with 09 (e.g., 09123456789).');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
    });

    // Archive warning toggle
    const statusSelect = document.getElementById('status-select');
    const warning = document.getElementById('archive-warning');
    if (statusSelect && warning) {
        const currentStatus = statusSelect.dataset.currentStatus;
        statusSelect.addEventListener('change', function () {
            const willArchive = currentStatus === 'active' && ['deceased', 'inactive', 'transferred'].includes(this.value);
            warning.classList.toggle('hidden', !willArchive);
        });
    }
</script>
@endpush
@endsection
