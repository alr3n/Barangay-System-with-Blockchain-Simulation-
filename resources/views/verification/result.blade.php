<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Result — Barangay San Jose</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    <div class="bg-primary py-4 px-6">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">Barangay San Jose</p>
                    <p class="text-blue-300 text-xs">Document Verification Portal</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="text-blue-200 hover:text-white text-xs">Staff Login →</a>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-12">

        @if($result === 'verified')
        {{-- VERIFIED --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 animate-pulse-once">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-green-700">Document Verified</h2>
            <p class="text-gray-500 mt-2 text-sm">This document is authentic and has not been tampered with.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-green-200 overflow-hidden mb-5">
            <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                <p class="text-sm font-semibold text-green-800">✓ Verified Barangay Document</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Control Number</p>
                        <p class="text-sm font-mono font-semibold text-gray-800">{{ $clearance->control_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Document Type</p>
                        <p class="text-sm font-medium text-gray-800">{{ $clearance->document_type_label }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Issued To</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $clearance->resident->full_name }}</p>
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
                        <p class="text-xs text-gray-500 mb-1">Valid Until</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $clearance->expiry_date ? $clearance->expiry_date->format('F d, Y') : 'No expiry' }}
                        </p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">SHA-256 Hash</p>
                    <p class="font-mono text-xs text-gray-600 bg-gray-50 rounded px-3 py-2 break-all">{{ $hash }}</p>
                </div>
            </div>
        </div>

        @elseif($result === 'tampered')
        {{-- TAMPERED --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-red-700">Document Tampered</h2>
            <p class="text-gray-500 mt-2 text-sm">The document data does not match the original hash. This document may have been modified.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Hash Mismatch Detected</p>
                    <p class="text-sm text-red-700 mt-1">
                        The hash code exists in our system but the document content has been modified.
                        Do not accept this document as valid. Please report this to the barangay office.
                    </p>
                </div>
            </div>
        </div>

        @elseif($result === 'revoked')
        {{-- REVOKED --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-orange-700">Document Revoked</h2>
            <p class="text-gray-500 mt-2 text-sm">This document has been revoked by the barangay and is no longer valid.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-orange-200 p-6">
            <p class="text-sm text-orange-700">
                Control Number: <strong>{{ $clearance->control_number }}</strong><br>
                This document was revoked by the issuing authority and should not be accepted.
            </p>
        </div>

        @else
        {{-- INVALID / NOT FOUND --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-700">Document Not Found</h2>
            <p class="text-gray-500 mt-2 text-sm">No document matches the provided hash code. This document may be invalid or was not issued by Barangay San Jose.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <p class="text-xs text-gray-500 mb-2">Hash queried:</p>
            <p class="font-mono text-xs text-gray-700 bg-gray-50 rounded px-3 py-2 break-all">{{ $hash }}</p>
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            <a href="{{ route('verify.index') }}"
               class="flex-1 text-center bg-primary text-white py-3 rounded-xl text-sm font-semibold hover:bg-blue-900 transition-colors">
                Verify Another Document
            </a>
            <a href="{{ route('login') }}"
               class="flex-1 text-center border border-gray-300 text-gray-600 py-3 rounded-xl text-sm hover:bg-gray-50 transition-colors">
                Staff Login
            </a>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Verification performed on {{ now()->format('F d, Y \a\t h:i A') }}
        </p>
    </div>
</body>
</html>
