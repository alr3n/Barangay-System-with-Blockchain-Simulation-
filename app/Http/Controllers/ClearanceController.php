<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Clearance;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClearanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Clearance::with(['resident', 'issuedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('control_number', 'like', "%$search%")
                  ->orWhere('purpose', 'like', "%$search%")
                  ->orWhereHas('resident', function ($r) use ($search) {
                      $r->where('first_name', 'like', "%$search%")
                        ->orWhere('middle_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('resident_code', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('type'))   $query->where('document_type', $request->type);
        if ($request->filled('status')) $query->where('status', $request->status);

        $clearances = $query->latest()->paginate(15)->withQueryString();

        return view('clearances.index', compact('clearances'));
    }

    public function create(Request $request)
    {
        // Only ACTIVE residents are eligible
        $residents = Resident::where('resident_status', 'active')
            ->orderBy('last_name')
            ->get();

        $selectedResident = null;
        if ($request->filled('resident_id')) {
            $selectedResident = Resident::find($request->resident_id);

            if ($selectedResident && $selectedResident->resident_status !== 'active') {
                return redirect()->route('residents.show', $selectedResident)
                    ->with('error', "Cannot issue documents — this resident is {$selectedResident->resident_status}.");
            }
        }

        return view('clearances.create', compact('residents', 'selectedResident'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id'   => 'required|exists:residents,id',
            'document_type' => 'required|in:barangay_clearance,residency_certificate,indigency_certificate,certificate_of_employment',
            'purpose'       => 'required|string|max:255',
            'fee'           => 'nullable|numeric|min:0',
            'expiry_date'   => 'nullable|date|after:today',
            'notes'         => 'nullable|string|max:500',
            'claim_ftjs_waiver' => 'nullable|boolean',
        ]);

        // Block document issuance to non-active residents (defense-in-depth)
        $resident = Resident::findOrFail($validated['resident_id']);
        if ($resident->resident_status !== 'active') {
            throw ValidationException::withMessages([
                'resident_id' => "Cannot issue documents — this resident is {$resident->resident_status}.",
            ]);
        }

        $claimsWaiver = $request->boolean('claim_ftjs_waiver');

        // If the resident is a First-Time Job Seeker, all processing fees are waived
        if ($resident->is_first_time_job_seeker) {
            $validated['fee'] = 0;
        }

        // RA 11261 COE waiver — validate eligibility when explicitly claimed
        if ($claimsWaiver) {
            if (!$resident->isEligibleForFreeCertificate()) {
                throw ValidationException::withMessages([
                    'claim_ftjs_waiver' => 'This resident is not eligible for the First-Time Job Seeker free certificate. '
                        . 'They must be marked as a first-time job seeker on their profile, be 18–30 years old, and be active.',
                ]);
            }
            $validated['fee'] = 0;
        }

        $controlNumber = Clearance::generateControlNumber();
        $issuedDate    = now()->toDateString();

        $hashData = [
            'control_number' => $controlNumber,
            'resident_id'    => $validated['resident_id'],
            'document_type'  => $validated['document_type'],
            'issued_date'    => $issuedDate,
        ];

        $clearance = Clearance::create([
            'control_number' => $controlNumber,
            'resident_id'    => $validated['resident_id'],
            'issued_by'      => auth()->id(),
            'document_type'  => $validated['document_type'],
            'purpose'        => $validated['purpose'],
            'hash_code'      => Clearance::generateHash($hashData),
            'issued_date'    => $issuedDate,
            'expiry_date'    => $validated['expiry_date'] ?? null,
            'fee'            => $validated['fee'] ?? 0,
            'notes'          => $validated['notes'] ?? null,
            'status'         => 'active',
            'is_first_time_job_seeker_waiver' => $claimsWaiver,
        ]);

        $logSuffix = $resident->is_first_time_job_seeker ? ' (FREE — First-Time Job Seeker)' : ($claimsWaiver ? ' (FREE — RA 11261 waiver)' : '');
        ActivityLog::log('create', 'Clearances',
            "Issued {$clearance->document_type_label} for resident ID {$clearance->resident_id}. Control: {$controlNumber}{$logSuffix}");

        $successMsg = $resident->is_first_time_job_seeker
            ? 'Document issued FREE — First-Time Job Seeker fee waiver applied.'
            : ($claimsWaiver ? 'Certificate issued FREE under RA 11261 (First-Time Job Seekers Act).' : 'Document issued successfully.');

        return redirect()->route('clearances.show', $clearance)
            ->with('success', $successMsg);
    }

    public function show(Clearance $clearance)
    {
        $clearance->load(['resident.household', 'issuedBy', 'verificationRecords']);
        return view('clearances.show', compact('clearance'));
    }

    public function print(Clearance $clearance)
    {
        // CRITICAL: Block printing of revoked clearances
        if ($clearance->status === 'revoked') {
            return redirect()->route('clearances.show', $clearance)
                ->with('error', 'This document has been revoked and cannot be printed.');
        }

        $clearance->load(['resident', 'issuedBy']);
        return view('clearances.print', compact('clearance'));
    }

    public function revoke(Clearance $clearance)
    {
        $clearance->update(['status' => 'revoked']);
        ActivityLog::log('update', 'Clearances', "Revoked clearance: {$clearance->control_number}");
        return back()->with('success', "Clearance {$clearance->control_number} has been revoked.");
    }

    public function destroy(Clearance $clearance)
    {
        $cn = $clearance->control_number;
        $clearance->delete();
        ActivityLog::log('delete', 'Clearances', "Deleted clearance: {$cn}");
        return redirect()->route('clearances.index')->with('success', 'Clearance deleted.');
    }
}
