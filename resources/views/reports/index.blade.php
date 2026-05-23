@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Exports')
@section('page-subtitle', 'Generate and export system reports')

@section('content')
<div class="py-4 space-y-6">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-primary">{{ number_format($data['total_residents']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Active Residents</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-green-600">{{ number_format($data['total_households']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Households</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-purple-600">{{ number_format($data['total_clearances']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Documents Issued</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
            <p class="text-3xl font-bold text-orange-500">{{ number_format($data['total_blotters']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Blotter Cases</p>
        </div>
    </div>

    {{-- Export Sections --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Residents Export --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-semibold text-gray-800">Resident Report</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('reports.residents.export') }}" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="deceased">Deceased</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Gender</label>
                            <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Residents CSV
                    </button>
                </form>
            </div>
        </div>

        {{-- Clearances Export --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="font-semibold text-gray-800">Clearance Report</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('reports.clearances.export') }}" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                            <input type="date" name="from" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                            <input type="date" name="to" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Document Type</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            <option value="barangay_clearance">Barangay Clearance</option>
                            <option value="residency_certificate">Residency Certificate</option>
                            <option value="indigency_certificate">Indigency Certificate</option>
                            <option value="certificate_of_employment">Certificate of Employment</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-purple-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Clearances CSV
                    </button>
                </form>
            </div>
        </div>

        {{-- Blotter Export --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-semibold text-gray-800">Blotter Report</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('reports.blotter.export') }}" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                            <input type="date" name="from" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                            <input type="date" name="to" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-orange-500 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export Blotter CSV
                    </button>
                </form>
            </div>
        </div>

        {{-- Households Export --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3 class="font-semibold text-gray-800">Household Report</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4">Export all household records including member counts.</p>
                <a href="{{ route('reports.households.export') }}"
                   class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Households CSV
                </a>
            </div>
        </div>

        {{-- Archived Residents Export --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden md:col-span-2">
            <div class="px-6 py-4 border-b border-gray-100 bg-purple-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8H3a2 2 0 00-2 2v9a2 2 0 002 2h18a2 2 0 002-2v-9a2 2 0 00-2-2h-2M5 8V6a2 2 0 012-2h10a2 2 0 012 2v2M5 8h14"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Archived Residents Report</h3>
                        <p class="text-xs text-purple-600">Deceased, inactive, transferred, and deleted resident records</p>
                    </div>
                </div>
                <a href="{{ route('archived_residents.index') }}"
                   class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center gap-1">
                    View Archive →
                </a>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('archived_residents.export') }}" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Archive Reason</label>
                            <select name="reason" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">All Reasons</option>
                                <option value="deceased">Deceased</option>
                                <option value="inactive">Inactive</option>
                                <option value="transferred">Transferred</option>
                                <option value="deleted">Deleted</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                            <input type="date" name="from" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                            <input type="date" name="to" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="flex items-center justify-center gap-2 bg-purple-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export Archived Residents CSV
                        </button>
                        <a href="{{ route('archived_residents.index') }}"
                           class="flex items-center justify-center gap-2 border border-purple-300 text-purple-700 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-purple-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View Full Archive
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
