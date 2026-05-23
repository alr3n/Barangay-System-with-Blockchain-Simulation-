<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Blotter;
use App\Models\Clearance;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_residents'  => Resident::where('resident_status', 'active')->count(),
            'total_households' => Household::count(),
            'total_clearances' => Clearance::count(),
            'pending_blotters' => Blotter::where('status', 'pending')->count(),
        ];

        // Monthly clearance issuance for current year
        $monthlyClearances = Clearance::select(
            DB::raw('MONTH(issued_date) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('issued_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyClearances[$i] ?? 0;
        }

        // Clearance type breakdown
        $clearanceByType = Clearance::select('document_type', DB::raw('COUNT(*) as count'))
            ->groupBy('document_type')
            ->pluck('count', 'document_type')
            ->toArray();

        // Blotter status breakdown
        $blotterStatus = [
            'pending'  => Blotter::where('status', 'pending')->count(),
            'ongoing'  => Blotter::where('status', 'ongoing')->count(),
            'resolved' => Blotter::where('status', 'resolved')->count(),
        ];

        // Resident demographics — gender
        $genderCount = [
            'male'   => Resident::where('gender', 'male')->where('resident_status', 'active')->count(),
            'female' => Resident::where('gender', 'female')->where('resident_status', 'active')->count(),
        ];

        // Resident demographics — age groups
        $ageGroups = [
            'Under 18' => Resident::where('resident_status', 'active')->whereDate('birthdate', '>', now()->subYears(18)->toDateString())->count(),
            '18–30'    => Resident::where('resident_status', 'active')->whereBetween('birthdate', [now()->subYears(30)->toDateString(), now()->subYears(18)->toDateString()])->count(),
            '31–45'    => Resident::where('resident_status', 'active')->whereBetween('birthdate', [now()->subYears(45)->toDateString(), now()->subYears(31)->toDateString()])->count(),
            '46–60'    => Resident::where('resident_status', 'active')->whereBetween('birthdate', [now()->subYears(60)->toDateString(), now()->subYears(46)->toDateString()])->count(),
            'Over 60'  => Resident::where('resident_status', 'active')->whereDate('birthdate', '<', now()->subYears(60)->toDateString())->count(),
        ];

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Recent clearances
        $recentClearances = Clearance::with(['resident', 'issuedBy'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'monthlyData',
            'clearanceByType',
            'blotterStatus',
            'genderCount',
            'ageGroups',
            'recentActivities',
            'recentClearances'
        ));
    }
}
