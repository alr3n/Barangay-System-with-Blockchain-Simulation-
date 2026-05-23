@extends('layouts.app')
@section('title', 'Add Household')
@section('page-title', 'Add Household')
@section('page-subtitle', 'Register a new household unit')

@section('content')
<div class="py-4 max-w-2xl">
    <form method="POST" action="{{ route('households.store') }}" novalidate>
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700 text-sm">Household Details</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Street / Full Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-400 @enderror" required>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Purok</label>
                        <select name="purok" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Purok</option>
                            @foreach(['Purok 1','Purok 2','Purok 3','Purok 4','Purok 5','Purok 6'] as $p)
                                <option value="{{ $p }}" @selected(old('purok')==$p)>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Street Name</label>
                        <input type="text" name="street" value="{{ old('street') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">House Type <span class="text-red-500">*</span></label>
                    <select name="house_type" id="house_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select type</option>
                        @foreach(['owned'=>'Owned','rented'=>'Rented','shared'=>'Shared','other'=>'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('house_type')==$val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('house_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Other type label — shown when "Other" is selected --}}
                <div id="other_type_field" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Specify House Type <span class="text-red-500">*</span></label>
                    <input type="text" name="house_type_other" value="{{ old('house_type_other') }}"
                           placeholder="e.g. Dormitory, Boarding House..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('house_type_other') border-red-400 @enderror">
                    @error('house_type_other')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Owner details — shown when "Rented" or "Shared" is selected --}}
                <div id="owner_fields" class="hidden space-y-4 border border-blue-100 bg-blue-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Owner / Landlord Details</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Owner Name <span class="text-red-500">*</span></label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('owner_name') border-red-400 @enderror">
                        @error('owner_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Owner Contact <span class="text-red-500">*</span></label>
                        <input type="text" name="owner_contact" value="{{ old('owner_contact') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('owner_contact') border-red-400 @enderror">
                        @error('owner_contact')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Owner Address</label>
                        <input type="text" name="owner_address" value="{{ old('owner_address') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900">Save Household</button>
            <a href="{{ route('households.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const sel = document.getElementById('house_type');
    const ownerFields = document.getElementById('owner_fields');
    const otherField  = document.getElementById('other_type_field');

    function toggle() {
        const v = sel.value;
        ownerFields.classList.toggle('hidden', !['rented', 'shared'].includes(v));
        otherField.classList.toggle('hidden', v !== 'other');
    }

    sel.addEventListener('change', toggle);
    toggle(); // run on page load to restore old() values
})();
</script>
@endpush
@endsection
