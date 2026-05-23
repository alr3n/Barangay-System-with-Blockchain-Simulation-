<?php

namespace App\Http\Controllers;

use App\Models\Clearance;
use App\Models\VerificationRecord;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        return view('verification.index');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'hash_code' => 'required|string',
        ]);

        $hash      = trim($request->hash_code);
        $clearance = Clearance::where('hash_code', $hash)->with('resident')->first();

        $result = 'invalid';

        if ($clearance) {
            // Re-compute hash to check for tampering
            $recomputed = Clearance::generateHash([
                'control_number' => $clearance->control_number,
                'resident_id'    => $clearance->resident_id,
                'document_type'  => $clearance->document_type,
                'issued_date'    => $clearance->issued_date->toDateString(),
            ]);

            if ($recomputed === $hash) {
                $result = $clearance->status === 'active' ? 'verified' : 'revoked';
            } else {
                $result = 'tampered';
            }
        }

        // Log this verification attempt
        VerificationRecord::create([
            'hash_queried' => $hash,
            'result'       => in_array($result, ['verified', 'revoked']) ? ($result === 'verified' ? 'verified' : 'invalid') : $result,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'clearance_id' => $clearance?->id,
        ]);

        return view('verification.result', compact('clearance', 'result', 'hash'));
    }
}
