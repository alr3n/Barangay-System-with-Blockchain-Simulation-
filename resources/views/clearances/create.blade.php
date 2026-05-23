@extends('layouts.app')
@section('title', 'Issue Document')
@section('page-title', 'Issue Document')
@section('page-subtitle', 'Generate a new barangay document for a resident')

@section('content')
<div class="py-4 max-w-2xl">
    <form method="POST" action="{{ route('clearances.store') }}" novalidate id="clearance-form">
        @csrf

        {{-- Embed resident FTJS eligibility data for JS --}}
        <script type="application/json" id="residents-data">
            {!! json_encode($residents->mapWithKeys(fn($r) => [$r->id => [
                'name' => $r->full_name,
                'is_ftjs' => $r->is_first_time_job_seeker,
                'eligible' => $r->isEligibleForFreeCertificate(),
                'age' => $r->age,
            ]])) !!}
        </script>

        <div class="space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Document Details</h3>
                </div>
                <div class="p-6 space-y-4">

                    {{-- Searchable resident combobox --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Resident <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-400 mb-2">Only active residents can be issued documents. Type any part of the name.</p>

                        <div class="combobox-wrapper" data-combobox>
                            <input type="text" data-combobox-input placeholder="Type to search by first, middle, or last name..." autocomplete="off"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('resident_id') border-red-400 @enderror">
                            <input type="hidden" name="resident_id" data-combobox-value id="resident_id" value="{{ old('resident_id', $selectedResident?->id) }}" required>
                            <div data-combobox-dropdown class="combobox-dropdown hidden">
                                @foreach($residents as $r)
                                    <div data-combobox-item data-value="{{ $r->id }}"
                                         data-label="{{ $r->full_name }} ({{ $r->resident_code }})"
                                         data-search-keywords="{{ strtolower($r->first_name . ' ' . $r->middle_name . ' ' . $r->last_name . ' ' . $r->resident_code) }}"
                                         class="combobox-item">
                                        <div class="flex items-center justify-between gap-2">
                                            <div>
                                                <div class="font-medium text-gray-800">{{ $r->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $r->resident_code }} · Age {{ $r->age }}</div>
                                            </div>
                                            @if($r->is_first_time_job_seeker)
                                                <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full">FTJS</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('resident_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                        {{-- FTJS banner --}}
                        <div id="ftjs-info" class="hidden mt-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2.5 flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <p class="text-xs text-green-700">
                                <strong>First-Time Job Seeker</strong> — Processing fee is automatically waived (₱0.00) for all document types.
                            </p>
                        </div>
                    </div>

                    {{-- Document Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Document Type <span class="text-red-500">*</span></label>
                        <select name="document_type" id="document_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('document_type') border-red-400 @enderror" required>
                            <option value="">Select document type...</option>
                            <option value="barangay_clearance"        @selected(old('document_type')=='barangay_clearance')>Barangay Clearance</option>
                            <option value="residency_certificate"     @selected(old('document_type')=='residency_certificate')>Certificate of Residency</option>
                            <option value="indigency_certificate"     @selected(old('document_type')=='indigency_certificate')>Certificate of Indigency</option>
                            <option value="certificate_of_employment" @selected(old('document_type')=='certificate_of_employment')>Certificate of Employment (RA 11261)</option>
                        </select>
                        @error('document_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- FTJS waiver checkbox — only shown when COE + eligible resident --}}
                    <div id="ftjs-waiver-group" class="hidden bg-green-50 border-2 border-green-200 rounded-xl p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="claim_ftjs_waiver" id="claim_ftjs_waiver" value="1" {{ old('claim_ftjs_waiver') ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <div>
                                <p class="text-sm font-bold text-green-800">Apply RA 11261 — First-Time Job Seeker Free Certificate</p>
                                <p class="text-xs text-green-700 mt-1">This resident is a certified first-time job seeker. The Certificate of Employment will be issued FREE OF CHARGE under Republic Act 11261.</p>
                                <p class="text-xs text-green-600 mt-1 italic">Processing fee will be automatically set to ₱0.00</p>
                            </div>
                        </label>
                        @error('claim_ftjs_waiver')<p class="text-red-600 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Purpose <span class="text-red-500">*</span></label>
                        <input type="text" name="purpose" value="{{ old('purpose') }}" placeholder="e.g. Employment, Loan Application, School Enrollment..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('purpose') border-red-400 @enderror" required>
                        @error('purpose')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach(['Employment','Business Permit','Loan Application','School Enrollment','Passport Application','Voter Registration','Travel Abroad','Medical Assistance','Scholarship','Burial Assistance','First Job Application'] as $p)
                                <button type="button" class="purpose-btn text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full hover:bg-blue-50 hover:text-blue-700">{{ $p }}</button>
                            @endforeach
                            <button type="button" data-purpose-other class="text-xs bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full hover:bg-blue-100 font-medium">+ Others (type your own)</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Processing Fee --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Processing Fee (₱)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm select-none">₱</span>
                                <input type="number" name="fee" id="fee_input"
                                       value="{{ old('fee', 0) }}" min="0" step="0.01"
                                       class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            </div>
                            <p id="fee_hint" class="text-xs text-gray-400 mt-1">Select a document type to auto-fill</p>
                        </div>
                        {{-- Expiry Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date"
                                   value="{{ old('expiry_date') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            <p id="expiry_hint" class="text-xs text-gray-400 mt-1">Auto-populated by document type</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                        <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Optional internal notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Blockchain-Verified Document</p>
                        <p class="text-xs text-blue-600 mt-1">A unique SHA-256 hash and QR code will be generated. Anyone can scan to verify authenticity.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900">Issue Document</button>
                <a href="{{ route('clearances.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50">Cancel</a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    /* ── Config ──────────────────────────────────────────────────── */
    const FEE = {
        barangay_clearance:        50,
        residency_certificate:     50,
        indigency_certificate:      0,
        certificate_of_employment: 100,
    };
    const VALIDITY_MONTHS = {
        barangay_clearance:         6,
        residency_certificate:      12,
        indigency_certificate:       6,
        certificate_of_employment:  12,
    };
    const DOC_LABEL = {
        barangay_clearance:        'Barangay Clearance',
        residency_certificate:     'Residency Certificate',
        indigency_certificate:     'Indigency Certificate',
        certificate_of_employment: 'Cert. of Employment',
    };

    /* ── Elements ────────────────────────────────────────────────── */
    const residentsData = JSON.parse(document.getElementById('residents-data').textContent);
    const residentInput = document.getElementById('resident_id');
    const docTypeSelect = document.getElementById('document_type');
    const ftjsGroup     = document.getElementById('ftjs-waiver-group');
    const ftjsCheckbox  = document.getElementById('claim_ftjs_waiver');
    const ftjsInfo      = document.getElementById('ftjs-info');
    const feeInput      = document.getElementById('fee_input');
    const feeHint       = document.getElementById('fee_hint');
    const expiryInput   = document.getElementById('expiry_date');
    const expiryHint    = document.getElementById('expiry_hint');

    /* ── Helpers ─────────────────────────────────────────────────── */
    function getResident() {
        const id = residentInput.value;
        return id ? residentsData[id] : null;
    }

    function addMonths(months) {
        const d = new Date();
        d.setMonth(d.getMonth() + months);
        return d.toISOString().split('T')[0];
    }

    function lockFee(value, reason) {
        feeInput.value    = value;
        feeInput.readOnly = true;
        feeInput.classList.add('bg-gray-50', 'text-gray-500', 'cursor-not-allowed');
        feeInput.classList.remove('bg-white');
        feeHint.innerHTML = `<span class="text-green-600 font-medium">${reason}</span>`;
    }

    function unlockFee(value, docType) {
        feeInput.value    = value;
        feeInput.readOnly = false;
        feeInput.classList.remove('bg-gray-50', 'text-gray-500', 'cursor-not-allowed');
        feeInput.classList.add('bg-white');
        if (docType && DOC_LABEL[docType]) {
            feeHint.innerHTML = `<span class="text-blue-600">Auto-set for ${DOC_LABEL[docType]}</span>`;
        } else {
            feeHint.textContent = 'Select a document type to auto-fill';
        }
    }

    function setExpiry(months, docType) {
        if (!docType) {
            expiryInput.value = '';
            expiryHint.textContent = 'Auto-populated by document type';
            return;
        }
        const date = addMonths(months);
        expiryInput.value = date;
        const label = months >= 12 ? `${months / 12} year` : `${months} months`;
        expiryHint.innerHTML = `<span class="text-blue-600">Valid for ${label} · expires ${new Date(date).toLocaleDateString('en-PH', { month:'short', day:'numeric', year:'numeric' })}</span>`;
    }

    /* ── Main update function ────────────────────────────────────── */
    function update() {
        const r       = getResident();
        const docType = docTypeSelect.value;
        const isCoe   = docType === 'certificate_of_employment';
        const isFtjs  = r && r.is_ftjs;
        const eligible = r && r.eligible;

        // ── FTJS info banner
        ftjsInfo.classList.toggle('hidden', !(isFtjs));

        // ── RA 11261 waiver checkbox (COE + eligible only)
        const showWaiver = isCoe && eligible;
        ftjsGroup.classList.toggle('hidden', !showWaiver);
        if (!showWaiver) ftjsCheckbox.checked = false;

        // ── Fee logic
        if (isFtjs) {
            // Any document → FREE for First-Time Job Seekers
            lockFee(0, '₱0.00 — Free (First-Time Job Seeker)');
        } else if (ftjsCheckbox.checked) {
            // RA 11261 COE waiver explicitly claimed
            lockFee(0, '₱0.00 — Free under RA 11261');
        } else if (docType && FEE[docType] !== undefined) {
            // Normal: auto-set predefined fee
            unlockFee(FEE[docType], docType);
        } else {
            // No doc type selected yet
            unlockFee('', null);
        }

        // ── Expiry date logic
        if (docType && VALIDITY_MONTHS[docType]) {
            setExpiry(VALIDITY_MONTHS[docType], docType);
        } else {
            setExpiry(0, null);
        }
    }

    /* ── Event listeners ─────────────────────────────────────────── */
    residentInput.addEventListener('change', update);
    docTypeSelect.addEventListener('change', update);
    ftjsCheckbox.addEventListener('change', update);

    // Initial run (restores old() state on validation failure)
    update();

    /* ── Purpose quick-pick buttons ─────────────────────────────── */
    const purposeInput = document.querySelector('[name="purpose"]');
    document.querySelectorAll('.purpose-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            purposeInput.value = btn.textContent.trim();
            purposeInput.focus();
        });
    });
    const otherBtn = document.querySelector('[data-purpose-other]');
    if (otherBtn) {
        otherBtn.addEventListener('click', () => purposeInput.focus());
    }
})();
</script>
@endpush
@endsection
