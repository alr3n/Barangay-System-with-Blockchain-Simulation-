<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Clearance;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Summary counts for report cards
        $data = [
            'total_residents'   => Resident::where('resident_status', 'active')->count(),
            'total_households'  => Household::count(),
            'total_clearances'  => Clearance::count(),
            'total_blotters'    => Blotter::count(),
            'clearance_by_type' => Clearance::selectRaw('document_type, COUNT(*) as count')
                ->groupBy('document_type')->get(),
            'blotter_by_status' => Blotter::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')->get(),
            'residents_by_gender' => Resident::selectRaw('gender, COUNT(*) as count')
                ->where('resident_status', 'active')->groupBy('gender')->get(),
        ];

        return view('reports.index', compact('data'));
    }

    public function exportResidents(Request $request): StreamedResponse
    {
        $residents = Resident::with('household')
            ->when($request->filled('status'), fn($q) => $q->where('resident_status', $request->status))
            ->when($request->filled('gender'), fn($q) => $q->where('gender', $request->gender))
            ->orderBy('last_name')
            ->get();

        return response()->streamDownload(function () use ($residents) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Resident Code', 'Last Name', 'First Name', 'Middle Name',
                'Birthdate', 'Age', 'Gender', 'Civil Status', 'Address',
                'Contact Number', 'Occupation', 'Household', 'Status',
            ]);
            foreach ($residents as $r) {
                fputcsv($handle, [
                    $r->resident_code,
                    $r->last_name,
                    $r->first_name,
                    $r->middle_name ?? '',
                    $r->birthdate->format('Y-m-d'),
                    $r->age,
                    ucfirst($r->gender),
                    ucfirst($r->civil_status),
                    $r->address,
                    $r->contact_number ?? '',
                    $r->occupation ?? '',
                    $r->household?->household_code ?? 'N/A',
                    ucfirst($r->resident_status),
                ]);
            }
            fclose($handle);
        }, 'residents_' . date('Ymd') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportClearances(Request $request): StreamedResponse
    {
        $clearances = Clearance::with(['resident', 'issuedBy'])
            ->when($request->filled('type'), fn($q) => $q->where('document_type', $request->type))
            ->when($request->filled('from'), fn($q) => $q->where('issued_date', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->where('issued_date', '<=', $request->to))
            ->orderBy('issued_date', 'desc')
            ->get();

        return response()->streamDownload(function () use ($clearances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Control Number', 'Resident Name', 'Document Type',
                'Purpose', 'Issued Date', 'Expiry Date', 'Fee', 'Issued By', 'Status',
            ]);
            foreach ($clearances as $c) {
                fputcsv($handle, [
                    $c->control_number,
                    $c->resident->full_name,
                    $c->document_type_label,
                    $c->purpose,
                    $c->issued_date->format('Y-m-d'),
                    $c->expiry_date ? $c->expiry_date->format('Y-m-d') : '',
                    $c->fee,
                    $c->issuedBy->name,
                    ucfirst($c->status),
                ]);
            }
            fclose($handle);
        }, 'clearances_' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportBlotter(Request $request): StreamedResponse
    {
        $blotters = Blotter::with('handler')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('from'), fn($q) => $q->where('incident_date', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->where('incident_date', '<=', $request->to))
            ->orderBy('incident_date', 'desc')
            ->get();

        return response()->streamDownload(function () use ($blotters) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Blotter No.', 'Complainant', 'Respondent',
                'Incident Type', 'Incident Date', 'Location',
                'Status', 'Handled By',
            ]);
            foreach ($blotters as $b) {
                fputcsv($handle, [
                    $b->blotter_number,
                    $b->complainant_name,
                    $b->respondent_name,
                    $b->incident_type,
                    $b->incident_date->format('Y-m-d'),
                    $b->incident_location,
                    ucfirst($b->status),
                    $b->handler?->name ?? 'N/A',
                ]);
            }
            fclose($handle);
        }, 'blotter_' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportHouseholds(Request $request): StreamedResponse
    {
        $households = Household::withCount('residents')->orderBy('household_code')->get();

        return response()->streamDownload(function () use ($households) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Household Code', 'Address', 'Purok', 'Street', 'House Type', 'Member Count',
            ]);
            foreach ($households as $h) {
                fputcsv($handle, [
                    $h->household_code,
                    $h->address,
                    $h->purok ?? '',
                    $h->street ?? '',
                    ucfirst($h->house_type),
                    $h->residents_count,
                ]);
            }
            fclose($handle);
        }, 'households_' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
