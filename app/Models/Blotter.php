<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $fillable = [
        'blotter_number',
        'complainant_name',
        'complainant_address',
        'complainant_contact',
        'respondent_name',
        'respondent_address',
        'respondent_contact',
        'incident_date',
        'incident_time',
        'incident_location',
        'incident_type',
        'incident_details',
        'status',
        'resolution_notes',
        'resolved_date',
        'handled_by',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'warning',
            'ongoing'  => 'primary',
            'resolved' => 'success',
            default    => 'secondary',
        };
    }

    public static function generateBlotterNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'BLT-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
