@extends('layouts.print')
@section('title', $clearance->document_type_label)

@section('content')
<div class="document-container">
    <div class="watermark">Official</div>

    <div class="header">
        <table style="width:100%; border:none;">
            <tr>
                <td style="width:80px; vertical-align:middle; text-align:center; border:none; padding:0;">
                    <div style="width:65px; height:65px; border-radius:50%; background:#1E3A5F; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                        <span style="color:white; font-size:28pt; font-weight:bold;">⌂</span>
                    </div>
                </td>
                <td style="vertical-align:middle; text-align:center; border:none; padding:0;">
                    <p style="font-size:9pt; color:#555; margin-bottom:2px;">Republic of the Philippines</p>
                    <p style="font-size:9pt; color:#555; margin-bottom:2px;">Province of Metro Manila · City/Municipality</p>
                    <h1 style="font-size:15pt; font-weight:bold; color:#1E3A5F; margin:4px 0;">BARANGAY SAN JOSE</h1>
                    <p style="font-size:9pt; color:#555;">Office of the Barangay Captain</p>
                </td>
                <td style="width:80px; vertical-align:middle; text-align:center; border:none; padding:0;">
                    <div style="width:65px; height:65px; border-radius:50%; background:#1E3A5F; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                        <span style="color:white; font-size:20pt;">🏛</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-title">
        <h3>{{ strtoupper($clearance->document_type_label) }}</h3>
        <p style="font-size:9pt; color:#666; margin-top:4px;">Control No: <strong>{{ $clearance->control_number }}</strong></p>
        @if($clearance->is_first_time_job_seeker_waiver)
            <p style="font-size:9pt; color:#22c55e; margin-top:2px; font-weight:bold;">✓ FREE — Republic Act 11261 (First-Time Job Seekers Act)</p>
        @endif
    </div>

    <div class="doc-body">
        @php
            $resident = $clearance->resident;
            $age      = $resident->birthdate->age;
            $address  = $resident->address;
            $captain  = 'Hon. Ricardo G. Magsaysay';
        @endphp

        <p>TO WHOM IT MAY CONCERN:</p>

        @if($clearance->document_type === 'barangay_clearance')
            <p>
                This is to certify that <strong>{{ strtoupper($resident->full_name) }}</strong>,
                {{ $age }} years old, {{ $resident->civil_status }}, {{ $resident->gender }},
                a bonafide resident of <strong>{{ $address }}, Barangay San Jose</strong>,
                is personally known to the undersigned.
            </p>
            <p>
                Further, this certifies that the above-named individual has no derogatory record
                on file in this office and is of good moral character and law-abiding citizen
                of this barangay.
            </p>
            <p>
                This certification is issued upon the request of the interested party for
                <strong>{{ strtoupper($clearance->purpose) }}</strong> purposes and for whatever
                legal purpose it may serve.
            </p>

        @elseif($clearance->document_type === 'residency_certificate')
            <p>
                This is to certify that <strong>{{ strtoupper($resident->full_name) }}</strong>,
                {{ $age }} years old, {{ $resident->civil_status }}, {{ $resident->gender }},
                is a <strong>bonafide resident</strong> of <strong>{{ $address }}, Barangay San Jose</strong>,
                for at least six (6) months up to the present.
            </p>
            <p>
                This certification is issued upon the request of the above-named individual
                for <strong>{{ strtoupper($clearance->purpose) }}</strong> purposes and for
                whatever legal purpose this may serve.
            </p>

        @elseif($clearance->document_type === 'indigency_certificate')
            <p>
                This is to certify that <strong>{{ strtoupper($resident->full_name) }}</strong>,
                {{ $age }} years old, {{ $resident->civil_status }}, {{ $resident->gender }},
                a resident of <strong>{{ $address }}, Barangay San Jose</strong>, belongs to an
                <strong>indigent family</strong> in this barangay and is one of the less fortunate
                members of our community.
            </p>
            <p>
                This certification is issued to attest to the financial incapacity of the
                above-named individual for <strong>{{ strtoupper($clearance->purpose) }}</strong>
                and for whatever legal purpose it may serve.
            </p>

        @elseif($clearance->document_type === 'certificate_of_employment')
            {{-- Certificate of Employment per RA 11261 — First-Time Jobseekers Assistance Act --}}
            <p>
                This is to certify that <strong>{{ strtoupper($resident->full_name) }}</strong>,
                {{ $age }} years old, {{ $resident->civil_status }}, {{ $resident->gender }},
                a bonafide resident of <strong>{{ $address }}, Barangay San Jose</strong>,
                is personally known to the undersigned.
            </p>

            @if($clearance->is_first_time_job_seeker_waiver)
            <p>
                Further, this certifies that the above-named individual is a
                <strong>FIRST-TIME JOB SEEKER</strong> as defined under
                <strong>Republic Act No. 11261</strong> (the "First-Time Jobseekers Assistance Act"),
                and as such is entitled to the exemption from payment of fees and charges
                for documents and transactions in pursuance of obtaining first employment.
            </p>
            <p>
                This certification is issued <strong>FREE OF CHARGE</strong> upon the request
                of the interested party for <strong>{{ strtoupper($clearance->purpose) }}</strong>
                and for whatever legal purpose it may serve in support of their first
                employment application.
            </p>
            <p style="font-size:9pt; color:#666; font-style:italic; margin-top:14px;">
                Note: This certificate is valid for one (1) year from the date of issuance and is
                non-transferable. The bearer is required to present this only to prospective
                employers as proof of first-time job seeker status.
            </p>
            @else
            <p>
                This certification is issued upon the request of the above-named individual
                for <strong>{{ strtoupper($clearance->purpose) }}</strong>
                and for whatever legal purpose it may serve.
            </p>
            @endif
        @endif

        <p>
            Issued this <strong>{{ $clearance->issued_date->format('jS') }}</strong> day of
            <strong>{{ $clearance->issued_date->format('F') }}, {{ $clearance->issued_date->format('Y') }}</strong>
            at Barangay San Jose.
        </p>
    </div>

    {{-- Signature + QR --}}
    <div style="margin-top:50px;">
        <table style="width:100%; border:none;">
            <tr>
                <td style="width:55%; border:none; vertical-align:bottom; padding-bottom:0;">
                    <p style="font-size:9pt; color:#555; margin-bottom:40px;">Requestor's Signature over Printed Name:</p>
                    <div style="border-top:1px solid #000; width:240px; padding-top:4px; font-size:9pt; color:#333;">
                        {{ strtoupper($resident->full_name) }}
                    </div>
                </td>
                <td style="width:45%; border:none; vertical-align:bottom; text-align:center; padding-bottom:0;">
                    <div id="qr-print" style="display:inline-block;"></div>
                    <p style="font-size:7pt; color:#888; margin-top:4px;">Scan to Verify Authenticity</p>
                    <p style="font-size:6pt; color:#aaa; margin-top:1px; font-family:monospace;">{{ substr($clearance->hash_code, 0, 16) }}…</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="signature-block" style="margin-top:40px;">
        <p style="font-size:9pt; color:#555; margin-bottom:40px;">Certified by:</p>
        <div style="display:inline-block; text-align:center; min-width:220px;">
            <div style="border-top: 2px solid #1E3A5F; padding-top:6px;">
                <p style="font-weight:bold; font-size:12pt; color:#1E3A5F;">{{ strtoupper($captain) }}</p>
                <p style="font-size:9pt; color:#555;">Barangay Captain</p>
                <p style="font-size:9pt; color:#555;">Barangay San Jose</p>
            </div>
        </div>
    </div>

    <div class="footer-info">
        <div>
            <p><strong>Doc. No.:</strong> {{ $clearance->control_number }}</p>
            <p><strong>Issued:</strong> {{ $clearance->issued_date->format('F d, Y') }}</p>
            @if($clearance->expiry_date)
            <p><strong>Valid Until:</strong> {{ $clearance->expiry_date->format('F d, Y') }}</p>
            @endif
            @if($clearance->is_first_time_job_seeker_waiver)
            <p style="color:#22c55e;"><strong>Fee:</strong> FREE (RA 11261)</p>
            @else
            <p><strong>Fee Paid:</strong> ₱{{ number_format($clearance->fee, 2) }}</p>
            @endif
        </div>
        <div class="qr-section text-right">
            <p class="hash-code"><strong>Hash:</strong> {{ substr($clearance->hash_code, 0, 32) }}...</p>
            <p style="font-size:7pt; color:#999; margin-top:2px;">Verify at: {{ url('/verify') }}</p>
        </div>
    </div>

    <div style="position:absolute; bottom:1.5in; right:1.2in; width:100px; height:100px; border:3px solid #1E3A5F44; border-radius:50%; display:flex; align-items:center; justify-content:center; transform:rotate(-15deg); opacity:0.3;">
        <div style="text-align:center; font-size:6pt; color:#1E3A5F; font-weight:bold; text-transform:uppercase; line-height:1.3;">
            <p>Official</p>
            <p>Barangay</p>
            <p>San Jose</p>
            <p>Seal</p>
        </div>
    </div>
</div>

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
    var svg = qr.createSvgTag(3, 4);
    document.getElementById('qr-print').innerHTML = svg;
})();
</script>
@endsection
