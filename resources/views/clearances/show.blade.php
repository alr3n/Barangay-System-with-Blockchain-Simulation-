@extends('layouts.app')
@section('title', 'Clearance Details')
@section('page-title', 'Document Details')
@section('page-subtitle', $clearance->control_number)

@section('content')
<div class="py-4 space-y-5 max-w-4xl">

    <div class="flex items-center justify-between">
        <a href="{{ route('clearances.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to list
        </a>
        <div class="flex gap-2">
            {{-- Only allow printing if status is ACTIVE --}}
            @if($clearance->status === 'active')
                <a href="{{ route('clearances.print', $clearance) }}" target="_blank"
                   class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Document
                </a>
                <form method="POST" action="{{ route('clearances.revoke', $clearance) }}"
                      onsubmit="return confirm('Are you sure you want to revoke this clearance? This action cannot be undone.')">
                    @csrf @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Revoke
                    </button>
                </form>
            @else
                <button type="button" disabled
                        class="inline-flex items-center gap-2 bg-gray-100 text-gray-400 px-4 py-2 rounded-lg text-sm cursor-not-allowed"
                        title="Revoked documents cannot be printed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Printing Disabled
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 space-y-5">

            @if($clearance->status === 'revoked')
            <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-700 font-medium">This document has been revoked and is no longer valid. Printing is disabled.</p>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700 text-sm">Document Information</h3>
                    @php
                        $tc = match($clearance->document_type) {
                            'barangay_clearance'    => 'bg-blue-100 text-blue-700',
                            'residency_certificate' => 'bg-green-100 text-green-700',
                            'indigency_certificate' => 'bg-orange-100 text-orange-700',
                            default                 => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tc }}">{{ $clearance->document_type_label }}</span>
                </div>
                <div class="p-6 grid grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Control Number</p>
                        <p class="text-sm font-mono font-semibold text-gray-800">{{ $clearance->control_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $clearance->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($clearance->status) }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Resident</p>
                        <a href="{{ route('residents.show', $clearance->resident) }}" class="text-sm font-medium text-blue-600 hover:underline">{{ $clearance->resident->full_name }}</a>
                        <p class="text-xs text-gray-400">{{ $clearance->resident->resident_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Purpose</p>
                        <p class="text-sm font-medium text-gray-800">{{ $clearance->purpose }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Date Issued</p>
                        <p class="text-sm font-medium text-gray-800">{{ $clearance->issued_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Expiry Date</p>
                        <p class="text-sm font-medium text-gray-800">{{ $clearance->expiry_date ? $clearance->expiry_date->format('F d, Y') : 'No expiry' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Processing Fee</p>
                        <p class="text-sm font-medium text-gray-800">₱{{ number_format($clearance->fee, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Issued By</p>
                        <p class="text-sm font-medium text-gray-800">{{ $clearance->issuedBy->name }}</p>
                    </div>
                    @if($clearance->notes)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Notes</p>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $clearance->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">SHA-256 Hash (Blockchain Verification)</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-900 rounded-lg px-4 py-3 mb-3">
                        <p class="font-mono text-xs text-green-400 break-all">{{ $clearance->hash_code }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="copyToClipboard('{{ $clearance->hash_code }}')" class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition-colors">
                            Copy Hash
                        </button>
                        <a href="{{ route('verify.index') }}?hash={{ $clearance->hash_code }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Verify on public portal
                        </a>
                    </div>
                </div>
            </div>

            @if($clearance->verificationRecords->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-700 text-sm">Verification History ({{ $clearance->verificationRecords->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">IP Address</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Result</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($clearance->verificationRecords->take(10) as $vr)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-2.5 text-gray-600 text-xs">{{ $vr->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-5 py-2.5 font-mono text-xs text-gray-500">{{ $vr->ip_address }}</td>
                                <td class="px-5 py-2.5">
                                    @php
                                        $rc = match($vr->result) {
                                            'verified' => 'bg-green-100 text-green-700',
                                            'tampered' => 'bg-red-100 text-red-700',
                                            default    => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $rc }}">{{ ucfirst($vr->result) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                <h3 class="font-semibold text-gray-700 text-sm mb-4">QR Code</h3>
                <div class="flex justify-center mb-4">
                    <div id="qrcode" class="inline-block {{ $clearance->status === 'revoked' ? 'opacity-30 grayscale' : '' }}"></div>
                </div>
                <p class="text-xs text-gray-400">Scan to verify this document on the public portal</p>
                @if($clearance->status === 'revoked')
                <p class="text-xs text-red-500 mt-2 font-medium">⚠️ Document is revoked</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.js"></script>
<script>
(function () {
    var payload = [
        "BARANGAY SAN JOSE - OFFICIAL DOCUMENT",
        "Control No: {{ $clearance->control_number }}",
        "Document  : {{ $clearance->document_type_label }}",
        "Issued To : {{ $clearance->resident->full_name }}",
        "Purpose   : {{ $clearance->purpose }}",
        "Date      : {{ $clearance->issued_date->format('M d, Y') }}",
        "Status    : {{ strtoupper($clearance->status) }}",
        "Verify    : {{ url('/verify') }}?hash={{ $clearance->hash_code }}"
    ].join("\n");

    var qr = qrcode(0, 'M');
    qr.addData(payload);
    qr.make();
    document.getElementById('qrcode').innerHTML = qr.createSvgTag(4, 8);
})();
</script>
@endpush
