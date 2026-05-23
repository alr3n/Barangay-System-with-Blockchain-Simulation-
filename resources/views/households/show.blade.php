@extends('layouts.app')
@section('title', 'Household Details')
@section('page-title', 'Household Record')
@section('page-subtitle', $household->household_code)

@section('content')
<div class="py-4 space-y-5 max-w-4xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('households.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to list
        </a>
        <a href="{{ route('households.edit', $household) }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h2 class="font-bold text-gray-900 text-lg">{{ $household->household_code }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $household->address }}</p>
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Purok</span>
                    <span class="font-medium text-gray-800">{{ $household->purok ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Street</span>
                    <span class="font-medium text-gray-800">{{ $household->street ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">House Type</span>
                    <span class="font-medium text-gray-800 capitalize">{{ $household->house_type }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Members</span>
                    <span class="font-bold text-blue-600">{{ $household->residents->count() }}</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            {{-- Assign Member --}}
            @if($availableResidents->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 text-sm mb-3">Assign Resident to Household</h3>
                <form method="POST" action="{{ route('households.assignMember', $household) }}" class="flex gap-2">
                    @csrf
                    <select name="resident_id" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select resident to assign...</option>
                        @foreach($availableResidents as $r)
                            <option value="{{ $r->id }}">{{ $r->full_name }} ({{ $r->resident_code }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">Assign</button>
                </form>
            </div>
            @endif

            {{-- Members Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Household Members ({{ $household->residents->count() }})</h3>
                </div>
                @if($household->residents->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Name</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Age</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Role</th>
                                <th class="px-5 py-2.5 text-left text-xs font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($household->residents as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('residents.show', $r) }}" class="font-medium text-blue-600 hover:underline">
                                        {{ $r->full_name }}
                                    </a>
                                    <p class="text-xs text-gray-400">{{ $r->resident_code }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ $r->age }}</td>
                                <td class="px-5 py-3">
                                    @if($r->is_household_head)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Head</span>
                                    @else
                                        <form method="POST" action="{{ route('households.setHead', [$household, $r]) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs text-blue-600 hover:underline">Set as Head</button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <form method="POST" action="{{ route('households.removeMember', [$household, $r]) }}"
                                          onsubmit="return confirm('Remove this resident from household?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-400 text-center py-8">No members assigned yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
