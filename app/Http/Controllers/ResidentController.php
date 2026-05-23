<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Household;
use App\Models\Resident;
use App\Rules\PhilippineMobileNumber;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::with('household');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('middle_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('resident_code', 'like', "%$search%")
                  ->orWhere('contact_number', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('resident_status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $residents = $query->latest()->paginate(15)->withQueryString();

        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        $households = Household::orderBy('household_code')->get();
        return view('residents.create', compact('households'));
    }

    private function validationRules(): array
    {
        return [
            'first_name'      => 'required|string|max:100',
            'middle_name'     => 'nullable|string|max:100',
            'last_name'       => 'required|string|max:100',
            'birthdate'       => 'required|date|before:today',
            'gender'          => 'required|in:male,female',
            'civil_status'    => 'required|in:single,married,widowed,separated,annulled',
            'address'         => 'required|string|max:255',
            'contact_number'  => ['nullable', new PhilippineMobileNumber],
            'occupation'      => 'nullable|string|max:100',
            'is_first_time_job_seeker' => 'nullable|boolean',
            'household_id'    => 'nullable|exists:households,id',
            'is_household_head' => 'nullable|boolean',
            'resident_status' => 'required|in:active,inactive,deceased,transferred',
            'remarks'         => 'nullable|string|max:500',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules(), [
            'contact_number.regex' => 'Contact number must be exactly 11 digits and start with 09.',
        ]);

        $validated['resident_code'] = Resident::generateCode();
        $validated['is_first_time_job_seeker'] = $request->boolean('is_first_time_job_seeker');
        $validated['is_household_head']        = $request->boolean('is_household_head');

        // If marked as FTJS, stamp the certification date
        if ($validated['is_first_time_job_seeker']) {
            $validated['first_time_job_seeker_certified_at'] = now()->toDateString();
        }

        $resident = Resident::create($validated);

        // If immediately created with a non-active status, archive a snapshot too
        if ($resident->resident_status !== 'active') {
            $resident->archive($resident->resident_status, 'Created with non-active status.');
        }

        ActivityLog::log('create', 'Residents',
            "Added new resident: {$resident->full_name} ({$resident->resident_code})");

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident record added successfully.');
    }

    public function show(Resident $resident)
    {
        $resident->load(['household', 'clearances.issuedBy', 'archiveRecords.archivedBy']);
        return view('residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        $households = Household::orderBy('household_code')->get();
        return view('residents.edit', compact('resident', 'households'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate($this->validationRules());

        $validated['is_first_time_job_seeker'] = $request->boolean('is_first_time_job_seeker');
        $validated['is_household_head']        = $request->boolean('is_household_head');

        // Track FTJS certification date — set only on first activation
        if ($validated['is_first_time_job_seeker'] && !$resident->is_first_time_job_seeker) {
            $validated['first_time_job_seeker_certified_at'] = now()->toDateString();
        } elseif (!$validated['is_first_time_job_seeker']) {
            $validated['first_time_job_seeker_certified_at'] = null;
        }

        $oldStatus = $resident->resident_status;
        $resident->update($validated);

        // ARCHIVE LOGIC: when transitioning from active → non-active, create archive snapshot
        if ($oldStatus === 'active' && in_array($validated['resident_status'], ['deceased', 'inactive', 'transferred'])) {
            $resident->archive(
                $validated['resident_status'],
                "Status changed from {$oldStatus} to {$validated['resident_status']} via resident update."
            );
            ActivityLog::log('archive', 'Residents',
                "Archived resident: {$resident->full_name} - reason: {$validated['resident_status']}");
        }

        ActivityLog::log('update', 'Residents',
            "Updated resident record: {$resident->full_name} ({$resident->resident_code})");

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident record updated successfully.');
    }

    /**
     * "Delete" — archive snapshot, then soft delete.
     * The resident record is preserved via soft delete; data is never physically removed.
     */
    public function destroy(Request $request, Resident $resident)
    {
        $reason = $request->input('reason'); // optional textual reason

        // Always create an archive snapshot before soft-deleting
        $resident->archive('deleted', $reason ?: 'Removed from active records by ' . auth()->user()->name);

        $name = $resident->full_name;
        $code = $resident->resident_code;
        $resident->delete(); // soft delete (deleted_at)

        ActivityLog::log('delete', 'Residents',
            "Archived & soft-deleted resident: {$name} ({$code}). Record preserved in archive.");

        return redirect()->route('residents.index')
            ->with('success', "{$name} has been archived. The record is preserved for audit and reporting.");
    }
}
