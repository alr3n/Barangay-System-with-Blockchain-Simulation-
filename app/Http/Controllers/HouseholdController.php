<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HouseholdController extends Controller
{
    public function index(Request $request)
    {
        $query = Household::withCount('residents');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('household_code', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('purok', 'like', "%$search%")
                  ->orWhere('owner_name', 'like', "%$search%");
            });
        }

        if ($request->filled('type')) {
            $query->where('house_type', $request->type);
        }

        $households = $query->latest()->paginate(15)->withQueryString();

        return view('households.index', compact('households'));
    }

    public function create()
    {
        return view('households.create');
    }

    /**
     * Build validation rules that require owner details for rented/shared
     * and a custom label for "other".
     */
    private function validationRules(): array
    {
        return [
            'address'    => 'required|string|max:255',
            'purok'      => 'nullable|string|max:100',
            'street'     => 'nullable|string|max:100',
            'house_type' => 'required|in:owned,rented,shared,other',

            // Required only when type is rented or shared
            'owner_name'    => [Rule::requiredIf(fn () => in_array(request('house_type'), ['rented', 'shared'])), 'nullable', 'string', 'max:150'],
            'owner_contact' => [Rule::requiredIf(fn () => in_array(request('house_type'), ['rented', 'shared'])), 'nullable', 'string', 'max:20'],
            'owner_address' => ['nullable', 'string', 'max:255'],

            // Required only when type is "other"
            'house_type_other' => [Rule::requiredIf(fn () => request('house_type') === 'other'), 'nullable', 'string', 'max:100'],
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules(), [
            'owner_name.required'        => 'Owner name is required for rented or shared households.',
            'owner_contact.required'     => 'Owner contact is required for rented or shared households.',
            'house_type_other.required'  => 'Please specify the house type.',
        ]);

        // Clear irrelevant fields based on type
        if (!in_array($validated['house_type'], ['rented', 'shared'])) {
            $validated['owner_name']    = null;
            $validated['owner_contact'] = null;
            $validated['owner_address'] = null;
        }
        if ($validated['house_type'] !== 'other') {
            $validated['house_type_other'] = null;
        }

        $year  = date('Y');
        $count = Household::whereYear('created_at', $year)->count() + 1;
        $validated['household_code'] = 'HH-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $household = Household::create($validated);

        ActivityLog::log('create', 'Households', "Created household: {$household->household_code}");

        return redirect()->route('households.show', $household)
            ->with('success', 'Household record created successfully.');
    }

    public function show(Household $household)
    {
        $household->load('residents');
        $availableResidents = Resident::whereNull('household_id')
            ->where('resident_status', 'active')
            ->orderBy('last_name')
            ->get();
        return view('households.show', compact('household', 'availableResidents'));
    }

    public function edit(Household $household)
    {
        return view('households.edit', compact('household'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate($this->validationRules(), [
            'owner_name.required'        => 'Owner name is required for rented or shared households.',
            'owner_contact.required'     => 'Owner contact is required for rented or shared households.',
            'house_type_other.required'  => 'Please specify the house type.',
        ]);

        // Clear irrelevant fields
        if (!in_array($validated['house_type'], ['rented', 'shared'])) {
            $validated['owner_name']    = null;
            $validated['owner_contact'] = null;
            $validated['owner_address'] = null;
        }
        if ($validated['house_type'] !== 'other') {
            $validated['house_type_other'] = null;
        }

        $household->update($validated);

        ActivityLog::log('update', 'Households', "Updated household: {$household->household_code}");

        return redirect()->route('households.show', $household)
            ->with('success', 'Household record updated successfully.');
    }

    public function destroy(Household $household)
    {
        $code = $household->household_code;
        Resident::where('household_id', $household->id)->update(['household_id' => null, 'is_household_head' => false]);
        $household->delete();

        ActivityLog::log('delete', 'Households', "Deleted household: {$code}");

        return redirect()->route('households.index')
            ->with('success', 'Household record deleted.');
    }

    public function assignMember(Request $request, Household $household)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
        ]);

        $resident = Resident::findOrFail($request->resident_id);
        $resident->update(['household_id' => $household->id]);

        return back()->with('success', 'Resident assigned to household.');
    }

    public function removeMember(Household $household, Resident $resident)
    {
        $resident->update(['household_id' => null, 'is_household_head' => false]);
        return back()->with('success', 'Resident removed from household.');
    }

    public function setHead(Household $household, Resident $resident)
    {
        Resident::where('household_id', $household->id)->update(['is_household_head' => false]);
        $resident->update(['is_household_head' => true]);
        return back()->with('success', 'Household head updated.');
    }
}
