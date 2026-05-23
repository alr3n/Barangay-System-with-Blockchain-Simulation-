<?php

namespace App\Http\Controllers;

use App\Models\ArchivedResident;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArchivedResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = ArchivedResident::with('archivedBy');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('middle_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('resident_code', 'like', "%$s%");
            });
        }

        if ($request->filled('reason')) {
            $query->where('archive_reason', $request->reason);
        }

        if ($request->filled('from')) $query->whereDate('archived_at', '>=', $request->from);
        if ($request->filled('to'))   $query->whereDate('archived_at', '<=', $request->to);

        $archived = $query->orderBy('archived_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total'       => ArchivedResident::count(),
            'deceased'    => ArchivedResident::where('archive_reason', 'deceased')->count(),
            'inactive'    => ArchivedResident::where('archive_reason', 'inactive')->count(),
            'transferred' => ArchivedResident::where('archive_reason', 'transferred')->count(),
            'deleted'     => ArchivedResident::where('archive_reason', 'deleted')->count(),
        ];

        return view('archived_residents.index', compact('archived', 'stats'));
    }

    public function show(ArchivedResident $archived_resident)
    {
        $archived_resident->load('archivedBy', 'originalResident');
        return view('archived_residents.show', ['archive' => $archived_resident]);
    }

    /**
     * Export archive records as CSV — for reporting/audit.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = ArchivedResident::with('archivedBy');
        if ($request->filled('reason')) $query->where('archive_reason', $request->reason);
        if ($request->filled('from'))   $query->whereDate('archived_at', '>=', $request->from);
        if ($request->filled('to'))     $query->whereDate('archived_at', '<=', $request->to);

        $records = $query->orderBy('archived_at', 'desc')->get();

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Archive ID', 'Original Resident ID', 'Resident Code',
                'Last Name', 'First Name', 'Middle Name', 'Birthdate', 'Age',
                'Gender', 'Civil Status', 'Address', 'Contact', 'Occupation',
                'Previous Household', 'First-Time Job Seeker',
                'Archive Reason', 'Archive Notes', 'Archived By',
            ]);
            foreach ($records as $r) {
                fputcsv($handle, [
                    $r->id, $r->original_resident_id, $r->resident_code,
                    $r->last_name, $r->first_name, $r->middle_name ?? '',
                    $r->birthdate->format('Y-m-d'), $r->age,
                    ucfirst($r->gender), ucfirst($r->civil_status),
                    $r->address, $r->contact_number ?? '', $r->occupation ?? '',
                    $r->previous_household_code ?? '',
                    $r->is_first_time_job_seeker ? 'Yes' : 'No',
                    ucfirst($r->archive_reason),
                    $r->archive_notes ?? '',
                    $r->archivedBy?->name ?? 'System',
                ]);
            }
            fclose($handle);
        }, 'archived_residents_' . date('Ymd_His') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
