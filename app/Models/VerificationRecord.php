<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationRecord extends Model
{
    protected $fillable = [
        'hash_queried',
        'result',
        'ip_address',
        'user_agent',
        'clearance_id',
    ];

    public function clearance()
    {
        return $this->belongsTo(Clearance::class);
    }
}
