@extends('layouts.app')
@section('title', $resident->full_name)
@section('page-title', 'Resident Profile')
@section('page-subtitle', $resident->resident_code)

@section('content')
<div class="py-4 space-y-5 max-w-5xl">

    {{-- Actions Bar --}}
    <div class="flex flex-wrap gap-2 items-center justify-between">
        <a href="{{ route('residents.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to list
        </a>
        <div class="flex gap-2">
            @if($resident->resident_status === 'active')
                <a href="{{ route('clearances.create', ['resident_id' => $resident->id]) }}"
                   class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Issue Document
                </a>
            @else
                <button type="button" disabled
                        class="inline-flex items-center gap-2 bg-gray-200 text-gray-400 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed"
                        title="Cannot issue documents — resident is {{ $resident->resident_status }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Issue Document
                </button>
            @endif
            <a href="{{ route('residents.edit', $resident) }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
        </div>
    </div>

    {{-- Deceased / Inactive banner --}}
    @if($resident->resident_status !== 'active')
        @php
            $banner = match($resident->resident_status) {
                'deceased'    => ['bg-red-50 border-red-200',     'text-red-800',     '⚠️ This resident is recorded as DECEASED. New documents cannot be issued.'],
                'inactive'    => ['bg-gray-50 border-gray-200',   'text-gray-700',    'This resident is INACTIVE. Document issuance is disabled.'],
                'transferred' => ['bg-yellow-50 border-yellow-200','text-yellow-800', 'This resident has TRANSFERRED out of the barangay. Document issuance is disabled.'],
                default       => ['bg-gray-50 border-gray-200','text-gray-700','Inactive resident.'],
            };
        @endphp
        <div class="{{ $banner[0] }} border rounded-xl px-5 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $banner[1] }}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium {{ $banner[1] }}">{{ $banner[2] }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4 {{ $resident->gender === 'male' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                {{ strtoupper(substr($resident->first_name, 0, 1)) }}
            </div>
            <h2 class="text-lg font-bold text-gray-900">{{ $resident->full_name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $resident->resident_code }}</p>

            <div class="mt-4">
                @php
                    $sc = match($resident->resident_status) {
                        'active'=>'bg-green-100 text-green-700','inactive'=>'bg-gray-100 text-gray-600',
                        'deceased'=>'bg-red-100 text-red-700','transferred'=>'bg-yellow-100 text-yellow-700',
                        default=>'bg-gray-100 text-gray-600'
                    };
                @endphp
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $sc }}">{{ ucfirst($resident->resident_status) }}</span>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100 text-left space-y-3">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Age</span><span class="font-medium text-gray-800">{{ $resident->age }} years old</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Gender</span><span class="font-medium text-gray-800 capitalize">{{ $resident->gender }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Civil Status</span><span class="font-medium text-gray-800 capitalize">{{ $resident->civil_status }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Birthdate</span><span class="font-medium text-gray-800">{{ $resident->birthdate->format('M d, Y') }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Contact</span><span class="font-medium text-gray-800">{{ $resident->contact_number ?? '—' }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Occupation</span><span class="font-medium text-gray-800">{{ $resident->occupation ?? '—' }}</span></div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4 text-sm">Address & Household</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Address</p>
                        <p class="text-sm font-medium text-gray-800">{{ $resident->address }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Household</p>
                        @if($resident->household)
                            <a href="{{ route('households.show', $resident->household) }}" class="text-sm text-blue-600 hover:underline font-medium">{{ $resident->household->household_code }}</a>
                            @if($resident->is_household_head)<span class="ml-1 text-xs text-green-600 bg-green-50 px-1.5 py-0.5 rounded">(Head)</span>@endif
                        @else
                            <p class="text-sm text-gray-500">Not assigned</p>
                        @endif
                    </div>
                    @if($resident->remarks)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Remarks</p>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $resident->remarks }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 text-sm">Issued Documents ({{ $resident->clearances->count() }})</h3>
                    @if($resident->resident_status === 'active')
                        <a href="{{ route('clearances.create', ['resident_id' => $resident->id]) }}" class="text-xs text-blue-600 hover:text-blue-800">+ Issue new</a>
                    @endif
                </div>
                @if($resident->clearances->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Control No.</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Type</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Purpose</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Date</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($resident->clearances as $c)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-2.5 font-mono text-xs text-gray-500">{{ $c->control_number }}</td>
                                <td class="px-5 py-2.5 text-gray-700 text-xs">{{ $c->document_type_label }}</td>
                                <td class="px-5 py-2.5 text-gray-600">{{ $c->purpose }}</td>
                                <td class="px-5 py-2.5 text-gray-500">{{ $c->issued_date->format('M d, Y') }}</td>
                                <td class="px-5 py-2.5">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $c->status==='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($c->status) }}</span>
                                </td>
                                <td class="px-5 py-2.5"><a href="{{ route('clearances.show', $c) }}" class="text-xs text-blue-600 hover:underline">View</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-400 text-center py-8">No documents issued for this resident.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
