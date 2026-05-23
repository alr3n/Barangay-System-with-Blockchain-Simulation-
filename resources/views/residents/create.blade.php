@extends('layouts.app')
@section('title', 'Add Resident')
@section('page-title', 'Add New Resident')
@section('page-subtitle', 'Fill in the form below to register a new resident')

@section('content')
<div class="py-4 max-w-4xl">
    <form method="POST" action="{{ route('residents.store') }}" novalidate>
        @csrf

        <div class="space-y-5">

            {{-- Personal Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Personal Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-400 @enderror" required>
                        @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-400 @enderror" required>
                        @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Birthdate <span class="text-red-500">*</span></label>
                        <input type="date" name="birthdate" value="{{ old('birthdate') }}" max="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('birthdate') border-red-400 @enderror" required>
                        @error('birthdate')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select gender</option>
                            <option value="male"   @selected(old('gender')=='male')>Male</option>
                            <option value="female" @selected(old('gender')=='female')>Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Civil Status <span class="text-red-500">*</span></label>
                        <select name="civil_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select status</option>
                            @foreach(['single','married','widowed','separated','annulled'] as $cs)
                            <option value="{{ $cs }}" @selected(old('civil_status')==$cs)>{{ ucfirst($cs) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Contact & Address --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Contact & Address</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-400 @enderror" required>
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Number</label>
                        <input
                            type="tel"
                            name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="09XXXXXXXXX"
                            pattern="09[0-9]{9}"
                            maxlength="11"
                            minlength="11"
                            inputmode="numeric"
                            data-ph-mobile
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contact_number') border-red-400 @enderror"
                        >
                        <p class="text-xs text-gray-400 mt-1">Must be exactly 11 digits and start with 09 (e.g., 09123456789)</p>
                        @error('contact_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- First-Time Job Seeker (RA 11261) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
                    <h3 class="font-semibold text-blue-800 text-sm">First-Time Job Seeker Status</h3>
                    <p class="text-xs text-blue-600 mt-0.5">Under RA 11261, qualified first-time job seekers (18–30 years old) are entitled to free barangay certificates.</p>
                </div>
                <div class="p-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_first_time_job_seeker" value="1" {{ old('is_first_time_job_seeker') ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Mark as First-Time Job Seeker</p>
                            <p class="text-xs text-gray-500 mt-0.5">Eligible residents can request a Certificate of Employment free of charge under RA 11261. Eligibility: 18–30 years old, has not been formally employed before.</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Household & Status --}}
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
                            <option value="{{ $hh->id }}" @selected(old('household_id')==$hh->id)>{{ $hh->household_code }} – {{ Str::limit($hh->address, 40) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_household_head" value="1" {{ old('is_household_head') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Set as Household Head
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Resident Status <span class="text-red-500">*</span></label>
                        <select name="resident_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @foreach(['active','inactive','deceased','transferred'] as $s)
                            <option value="{{ $s }}" @selected(old('resident_status', 'active')==$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Non-active status will create an archive snapshot.</p>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Remarks</label>
                        <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('remarks') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900">Save Resident</button>
                <a href="{{ route('residents.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Enforce digits-only input live on PH mobile fields
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
</script>
@endpush
@endsection
