@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('content')
<div class="py-4 space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Residents</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_residents']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Registered in the barangay</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Households</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_households']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Recorded household units</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Clearances Issued</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_clearances']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Total documents processed</p>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending Blotters</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending_blotters']) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Awaiting resolution</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Monthly Clearances Chart --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Monthly Clearance Issuance</h3>
                    <p class="text-xs text-gray-500">{{ date('Y') }} overview</p>
                </div>
            </div>
            <div style="position:relative; height:200px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        {{-- Document Type Doughnut --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm mb-0.5">Document Types</h3>
            <p class="text-xs text-gray-500 mb-3">Breakdown by type</p>
            <div style="position:relative; height:180px;">
                <canvas id="docTypeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Demographics Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Gender Breakdown --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm mb-0.5">Gender Breakdown</h3>
            <p class="text-xs text-gray-500 mb-3">Active residents by gender</p>
            <div class="flex items-center gap-5 mb-3">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                    <span class="text-xs text-gray-500">Male</span>
                    <span class="text-xs font-bold text-gray-800">{{ number_format($genderCount['male']) }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-pink-500 inline-block"></span>
                    <span class="text-xs text-gray-500">Female</span>
                    <span class="text-xs font-bold text-gray-800">{{ number_format($genderCount['female']) }}</span>
                </div>
            </div>
            <div style="position:relative; height:160px;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        {{-- Age Groups --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm mb-0.5">Age Distribution</h3>
            <p class="text-xs text-gray-500 mb-3">Active residents by age group</p>
            <div style="position:relative; height:160px;">
                <canvas id="ageChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Second Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Blotter Status --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 text-sm mb-0.5">Blotter Cases</h3>
            <p class="text-xs text-gray-500 mb-3">By status</p>
            <div style="position:relative; height:160px;">
                <canvas id="blotterChart"></canvas>
            </div>
            <div class="mt-3 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 inline-block"></span>Pending</span>
                    <span class="font-semibold">{{ $blotterStatus['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>Ongoing</span>
                    <span class="font-semibold">{{ $blotterStatus['ongoing'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>Resolved</span>
                    <span class="font-semibold">{{ $blotterStatus['resolved'] }}</span>
                </div>
            </div>
        </div>

        {{-- Recent Activities --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-800">Recent Activities</h3>
                    <p class="text-xs text-gray-500">Latest system actions</p>
                </div>
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($recentActivities as $log)
                <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                    <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-gray-800 truncate">{{ $log->description }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-gray-400">{{ $log->user?->name ?? 'System' }}</span>
                            <span class="text-gray-200">·</span>
                            <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full flex-shrink-0">{{ $log->module }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">No activities yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Clearances --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800">Recently Issued Documents</h3>
                <p class="text-xs text-gray-500">Latest clearances and certificates</p>
            </div>
            <a href="{{ route('clearances.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Control No.</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Resident</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Document</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Purpose</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentClearances as $c)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $c->control_number }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $c->resident->full_name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $c->document_type_label }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $c->purpose }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $c->issued_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $c->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No documents issued yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── Shared design tokens ─────────────────────────────────────── */
const FONT   = "'Inter', 'ui-sans-serif', system-ui, sans-serif";
const GRID   = 'rgba(0,0,0,0.04)';
const TICK   = { color: '#9CA3AF', font: { size: 11, family: FONT } };
const LEGEND = { labels: { font: { size: 11, family: FONT }, padding: 14, usePointStyle: true, pointStyleWidth: 8 } };

const TOOLTIP = {
    backgroundColor: '#1F2937',
    titleFont: { size: 12, family: FONT, weight: '600' },
    bodyFont:  { size: 11, family: FONT },
    padding: 10,
    cornerRadius: 8,
    displayColors: true,
    boxWidth: 8, boxHeight: 8,
};

function doughnutCenter(label, value) {
    return {
        id: 'centerText',
        beforeDraw(chart) {
            const { ctx, chartArea: { width, height, left, top } } = chart;
            ctx.save();
            const cx = left + width / 2, cy = top + height / 2;
            ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
            ctx.fillStyle = '#111827';
            ctx.font = `700 22px ${FONT}`;
            ctx.fillText(value, cx, cy - 9);
            ctx.fillStyle = '#6B7280';
            ctx.font = `400 10px ${FONT}`;
            ctx.fillText(label, cx, cy + 10);
            ctx.restore();
        }
    };
}

/* ── Monthly Clearances — gradient bar ────────────────────────── */
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyData = @json($monthlyData);

(function () {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 240);
    grad.addColorStop(0,   'rgba(59,130,246,0.35)');
    grad.addColorStop(1,   'rgba(59,130,246,0.02)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Clearances',
                data: monthlyData,
                backgroundColor: grad,
                borderColor: '#3B82F6',
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: { ...TOOLTIP, callbacks: {
                    title: ([i]) => months[i.dataIndex] + ' ' + new Date().getFullYear(),
                    label:  (i)  => '  ' + i.raw + ' document' + (i.raw !== 1 ? 's' : ''),
                }}
            },
            scales: {
                y: { beginAtZero: true, ticks: { ...TICK, precision: 0, stepSize: 1 }, grid: { color: GRID, drawBorder: false } },
                x: { ticks: TICK, grid: { display: false }, border: { display: false } }
            }
        }
    });
})();

/* ── Document Type — doughnut ─────────────────────────────────── */
const docTypes = @json($clearanceByType);
(function () {
    const total = (docTypes['barangay_clearance']||0)+(docTypes['residency_certificate']||0)
                +(docTypes['indigency_certificate']||0)+(docTypes['certificate_of_employment']||0);

    new Chart(document.getElementById('docTypeChart'), {
        type: 'doughnut',
        plugins: [doughnutCenter('docs', total)],
        data: {
            labels: ['Barangay Clearance','Residency Cert.','Indigency Cert.','Cert. of Employment'],
            datasets: [{
                data: [
                    docTypes['barangay_clearance']        || 0,
                    docTypes['residency_certificate']     || 0,
                    docTypes['indigency_certificate']     || 0,
                    docTypes['certificate_of_employment'] || 0,
                ],
                backgroundColor: ['#3B82F6','#10B981','#F59E0B','#8B5CF6'],
                hoverBackgroundColor: ['#2563EB','#059669','#D97706','#7C3AED'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            animation: { animateRotate: true, duration: 700 },
            plugins: { legend: { position: 'bottom', ...LEGEND }, tooltip: TOOLTIP }
        }
    });
})();

/* ── Gender — doughnut ────────────────────────────────────────── */
const gc = @json($genderCount);
(function () {
    const total = (gc.male||0) + (gc.female||0);
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        plugins: [doughnutCenter('total', total)],
        data: {
            labels: ['Male','Female'],
            datasets: [{
                data: [gc.male||0, gc.female||0],
                backgroundColor: ['#3B82F6','#EC4899'],
                hoverBackgroundColor: ['#2563EB','#DB2777'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            animation: { animateRotate: true, duration: 700 },
            plugins: { legend: { position: 'bottom', ...LEGEND }, tooltip: TOOLTIP }
        }
    });
})();

/* ── Age Groups — horizontal bar ─────────────────────────────── */
const ag = @json($ageGroups);
(function () {
    const palette = ['#6366F1','#3B82F6','#10B981','#F59E0B','#EF4444'];
    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(ag),
            datasets: [{
                label: 'Residents',
                data: Object.values(ag),
                backgroundColor: palette,
                hoverBackgroundColor: ['#4F46E5','#2563EB','#059669','#D97706','#DC2626'],
                borderRadius: 6,
                borderWidth: 0,
                barPercentage: 0.65,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: { ...TOOLTIP, callbacks: {
                    label: (i) => '  ' + i.raw + ' resident' + (i.raw !== 1 ? 's' : ''),
                }}
            },
            scales: {
                x: { beginAtZero: true, ticks: { ...TICK, precision: 0 }, grid: { color: GRID, drawBorder: false }, border: { display: false } },
                y: { ticks: TICK, grid: { display: false }, border: { display: false } }
            }
        }
    });
})();

/* ── Blotter Status — doughnut ────────────────────────────────── */
const bs = @json($blotterStatus);
(function () {
    const total = (bs.pending||0) + (bs.ongoing||0) + (bs.resolved||0);
    new Chart(document.getElementById('blotterChart'), {
        type: 'doughnut',
        plugins: [doughnutCenter('cases', total)],
        data: {
            labels: ['Pending','Ongoing','Resolved'],
            datasets: [{
                data: [bs.pending||0, bs.ongoing||0, bs.resolved||0],
                backgroundColor: ['#F59E0B','#3B82F6','#10B981'],
                hoverBackgroundColor: ['#D97706','#2563EB','#059669'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            animation: { animateRotate: true, duration: 700 },
            plugins: { legend: { display: false }, tooltip: TOOLTIP }
        }
    });
})();
</script>
@endpush
