<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Blotter;
use App\Rules\PhilippineMobileNumber;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlotterController extends Controller
{
    /** Predefined incident types — used by the dropdown. */
    private const STANDARD_INCIDENT_TYPES = [
        'Noise Complaint', 'Physical Altercation', 'Property Dispute',
        'Land Boundary Dispute', 'Theft', 'Vandalism', 'Threat',
        'Domestic Violence', 'Animal Nuisance', 'Trespassing',
    ];

    public function index(Request $request)
    {
        $query = Blotter::with('handler');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('blotter_number', 'like', "%$search%")
                  ->orWhere('complainant_name', 'like', "%$search%")
                  ->orWhere('respondent_name', 'like', "%$search%")
                  ->orWhere('incident_type', 'like', "%$search%")
                  ->orWhere('incident_location', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);

        $blotters = $query->latest()->paginate(15)->withQueryString();

        return view('blotter.index', compact('blotters'));
    }

    public function create()
    {
        $incidentTypes = self::STANDARD_INCIDENT_TYPES;
        return view('blotter.create', compact('incidentTypes'));
    }

    private function resolveIncidentType(Request $request): string
    {
        $picked = trim((string) $request->input('incident_type'));
        if ($picked === 'Others') {
            return trim((string) $request->input('incident_type_other'));
        }
        return $picked;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'complainant_name'    => 'required|string|max:150',
            'complainant_address' => 'required|string|max:255',
            'complainant_contact' => ['nullable', new PhilippineMobileNumber],
            'respondent_name'     => 'required|string|max:150',
            'respondent_address'  => 'required|string|max:255',
            'respondent_contact'  => ['nullable', new PhilippineMobileNumber],
            'incident_date'       => 'required|date|before_or_equal:today',
            'incident_time'       => 'nullable|date_format:H:i',
            'incident_location'   => 'required|string|max:255',
            'incident_type'       => 'required|string|max:100',
            'incident_type_other' => [Rule::requiredIf(fn () => $request->incident_type === 'Others'), 'nullable', 'string', 'max:100'],
            // Minimum-character validation prevents incomplete submissions
            'incident_details'    => 'required|string|min:50|max:5000',
        ], [
            'incident_type_other.required'  => 'Please specify the incident type.',
            'incident_date.before_or_equal' => 'Incident date cannot be in the future.',
            'incident_details.min'          => 'Incident details must be at least 50 characters to ensure a complete description.',
        ]);

        $validated['incident_type']  = $this->resolveIncidentType($request);
        $validated['blotter_number'] = Blotter::generateBlotterNumber();
        $validated['status']         = 'pending';
        $validated['handled_by']     = auth()->id();

        unset($validated['incident_type_other']);

        $blotter = Blotter::create($validated);

        ActivityLog::log('create', 'Blotter',
            "Recorded blotter: {$blotter->blotter_number} - {$blotter->incident_type}");

        return redirect()->route('blotter.show', $blotter)
            ->with('success', "Blotter {$blotter->blotter_number} has been filed and is now PENDING review. Details are now locked — only status can be updated.");
    }

    public function show(Blotter $blotter)
    {
        $blotter->load('handler');
        return view('blotter.show', compact('blotter'));
    }

    /**
     * Edit page — only status field is editable.
     * Resolved blotters are READ-ONLY.
     */
    public function edit(Blotter $blotter)
    {
        if ($blotter->status === 'resolved') {
            return redirect()->route('blotter.show', $blotter)
                ->with('error', "Blotter {$blotter->blotter_number} has been RESOLVED and is now read-only. No further changes are permitted.");
        }

        $incidentTypes = self::STANDARD_INCIDENT_TYPES;
        return view('blotter.edit', compact('blotter', 'incidentTypes'));
    }

    /**
     * Update — only status, resolution_notes, and resolved_date are persisted.
     * All other incident details cannot be modified after creation.
     */
    public function update(Request $request, Blotter $blotter)
    {
        // Defense-in-depth: block updates on resolved cases
        if ($blotter->status === 'resolved') {
            return redirect()->route('blotter.show', $blotter)
                ->with('error', 'Resolved blotters are read-only and cannot be modified.');
        }

        // Only validate fields that staff are allowed to touch
        $validated = $request->validate([
            'status'           => 'required|in:pending,ongoing,resolved',
            'resolution_notes' => 'nullable|string|max:2000',
            'resolved_date'    => 'nullable|date|required_if:status,resolved|before_or_equal:today',
        ], [
            'resolved_date.required_if'    => 'Resolved date is required when marking the case as resolved.',
            'resolved_date.before_or_equal'=> 'Resolved date cannot be in the future.',
        ]);

        $oldStatus = $blotter->status;
        $blotter->update($validated);

        ActivityLog::log('update', 'Blotter',
            "Status updated for blotter: {$blotter->blotter_number} — {$oldStatus} → {$blotter->status}");

        $message = $this->statusNotificationMessage($blotter, $oldStatus);

        return redirect()->route('blotter.show', $blotter)
            ->with($message['type'], $message['text']);
    }

    /**
     * Status-aware notification messages.
     */
    private function statusNotificationMessage(Blotter $blotter, string $oldStatus): array
    {
        $no = $blotter->blotter_number;

        if ($oldStatus === $blotter->status) {
            return ['type' => 'success', 'text' => "Blotter {$no} resolution notes saved."];
        }

        return match ($blotter->status) {
            'pending'  => ['type' => 'success', 'text' => "Blotter {$no} has been reset to PENDING status."],
            'ongoing'  => ['type' => 'success', 'text' => "Blotter {$no} is now ONGOING. Investigation has been opened."],
            'resolved' => ['type' => 'success', 'text' => "Blotter {$no} has been marked as RESOLVED. Case is now closed and read-only."],
            default    => ['type' => 'success', 'text' => "Blotter {$no} status updated."],
        };
    }

    public function destroy(Blotter $blotter)
    {
        // Resolved cases are read-only — including delete
        if ($blotter->status === 'resolved') {
            return back()->with('error', 'Resolved blotters cannot be deleted. The case is permanent for audit purposes.');
        }

        $num = $blotter->blotter_number;
        $blotter->delete();
        ActivityLog::log('delete', 'Blotter', "Deleted blotter: {$num}");
        return redirect()->route('blotter.index')->with('success', "Blotter {$num} deleted.");
    }

    public function print(Blotter $blotter)
    {
        $blotter->load('handler');
        return view('blotter.print', compact('blotter'));
    }
}
