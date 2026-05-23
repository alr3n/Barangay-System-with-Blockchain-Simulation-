@extends('layouts.print')
@section('title', 'Blotter Record — ' . $blotter->blotter_number)

@section('content')
<div class="document-container">

    <div class="header">
        <table style="width:100%; border:none;">
            <tr>
                <td style="width:80px; text-align:center; border:none; padding:0; vertical-align:middle;">
                    <div style="width:65px; height:65px; border-radius:50%; background:#1E3A5F; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                        <span style="color:white; font-size:28pt;">⌂</span>
                    </div>
                </td>
                <td style="text-align:center; border:none; padding:0; vertical-align:middle;">
                    <p style="font-size:9pt; color:#555; margin:2px 0;">Republic of the Philippines</p>
                    <h1 style="font-size:15pt; font-weight:bold; color:#1E3A5F; margin:4px 0;">BARANGAY SAN JOSE</h1>
                    <p style="font-size:9pt; color:#555;">Office of the Barangay Captain</p>
                </td>
                <td style="width:80px; border:none; padding:0;"></td>
            </tr>
        </table>
    </div>

    <div class="doc-title">
        <h3>BARANGAY BLOTTER RECORD</h3>
        <p style="font-size:9pt; color:#666; margin-top:4px;">Blotter No.: <strong>{{ $blotter->blotter_number }}</strong></p>
    </div>

    <table style="width:100%; border-collapse:collapse; margin:20px 0; font-size:10pt;">
        <tr style="background:#f0f4ff;">
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; width:30%; color:#1E3A5F;">Incident Date & Time</td>
            <td style="padding:8px 12px; border:1px solid #ddd;">
                {{ $blotter->incident_date->format('F d, Y') }}
                {{ $blotter->incident_time ? ' at ' . \Carbon\Carbon::parse($blotter->incident_time)->format('h:i A') : '' }}
            </td>
        </tr>
        <tr>
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F;">Incident Type</td>
            <td style="padding:8px 12px; border:1px solid #ddd;">{{ $blotter->incident_type }}</td>
        </tr>
        <tr style="background:#f0f4ff;">
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F;">Location</td>
            <td style="padding:8px 12px; border:1px solid #ddd;">{{ $blotter->incident_location }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F; vertical-align:top;">Complainant</td>
            <td style="padding:8px 12px; border:1px solid #ddd;">
                <strong>{{ $blotter->complainant_name }}</strong><br>
                <span style="font-size:9pt; color:#555;">{{ $blotter->complainant_address }}</span>
                @if($blotter->complainant_contact)
                <br><span style="font-size:9pt; color:#555;">Tel: {{ $blotter->complainant_contact }}</span>
                @endif
            </td>
        </tr>
        <tr style="background:#f0f4ff;">
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F; vertical-align:top;">Respondent</td>
            <td style="padding:8px 12px; border:1px solid #ddd;">
                <strong>{{ $blotter->respondent_name }}</strong><br>
                <span style="font-size:9pt; color:#555;">{{ $blotter->respondent_address }}</span>
                @if($blotter->respondent_contact)
                <br><span style="font-size:9pt; color:#555;">Tel: {{ $blotter->respondent_contact }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F; vertical-align:top;">Incident Narration</td>
            <td style="padding:8px 12px; border:1px solid #ddd; line-height:1.7;">{{ $blotter->incident_details }}</td>
        </tr>
        <tr style="background:#f0f4ff;">
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F;">Status</td>
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; text-transform:uppercase;">{{ $blotter->status }}</td>
        </tr>
        @if($blotter->resolution_notes)
        <tr>
            <td style="padding:8px 12px; border:1px solid #ddd; font-weight:bold; color:#1E3A5F; vertical-align:top;">Resolution Notes</td>
            <td style="padding:8px 12px; border:1px solid #ddd; line-height:1.7;">{{ $blotter->resolution_notes }}</td>
        </tr>
        @endif
    </table>

    <div style="margin-top:50px; display:flex; justify-content:space-between;">
        <div style="text-align:center; min-width:200px;">
            <div style="border-top:1px solid #000; padding-top:6px; font-size:9pt;">
                <p style="font-weight:bold; text-transform:uppercase;">{{ $blotter->complainant_name }}</p>
                <p style="color:#555;">Complainant's Signature</p>
            </div>
        </div>
        <div style="text-align:center; min-width:220px;">
            <div style="border-top:2px solid #1E3A5F; padding-top:6px; font-size:9pt;">
                <p style="font-weight:bold; color:#1E3A5F; font-size:11pt;">HON. RICARDO G. MAGSAYSAY</p>
                <p style="color:#555;">Barangay Captain</p>
            </div>
        </div>
    </div>

    <div class="footer-info" style="margin-top:30px;">
        <div>
            <p>Filed: {{ $blotter->created_at->format('F d, Y') }}</p>
            <p>Handled by: {{ $blotter->handler?->name ?? 'N/A' }}</p>
        </div>
        <div style="text-align:right; font-size:8pt; color:#888;">
            <p>This is an official barangay document.</p>
            <p>Barangay San Jose Information System</p>
        </div>
    </div>
</div>
@endsection
