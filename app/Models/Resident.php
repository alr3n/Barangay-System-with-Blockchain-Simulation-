<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resident_code',
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'gender',
        'civil_status',
        'address',
        'contact_number',
        'occupation',
        'is_first_time_job_seeker',
        'first_time_job_seeker_certified_at',
        'household_id',
        'is_household_head',
        'resident_status',
        'profile_photo',
        'remarks',
    ];

    protected $casts = [
        'birthdate'                          => 'date',
        'is_household_head'                  => 'boolean',
        'is_first_time_job_seeker'           => 'boolean',
        'first_time_job_seeker_certified_at' => 'date',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function clearances()
    {
        return $this->hasMany(Clearance::class);
    }

    public function archiveRecords()
    {
        return $this->hasMany(ArchivedResident::class, 'original_resident_id');
    }

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? ' ' . $this->middle_name . ' ' : ' ';
        return $this->first_name . $middle . $this->last_name;
    }

    public function getAgeAttribute(): int
    {
        return $this->birthdate->age;
    }

    /**
     * RA 11261: First-time job seekers are exempt from barangay fees
     * for documents required when applying for their first job.
     */
    public function isEligibleForFreeCertificate(): bool
    {
        return $this->is_first_time_job_seeker
            && $this->resident_status === 'active'
            && $this->age >= 18
            && $this->age <= 30; // RA 11261 covers 18-30 year-olds
    }

    /**
     * Snapshot this resident into the archive table with the given reason.
     */
    public function archive(string $reason, ?string $notes = null): ArchivedResident
    {
        return ArchivedResident::create([
            'original_resident_id'      => $this->id,
            'resident_code'             => $this->resident_code,
            'first_name'                => $this->first_name,
            'middle_name'               => $this->middle_name,
            'last_name'                 => $this->last_name,
            'birthdate'                 => $this->birthdate,
            'gender'                    => $this->gender,
            'civil_status'              => $this->civil_status,
            'address'                   => $this->address,
            'contact_number'            => $this->contact_number,
            'occupation'                => $this->occupation,
            'previous_household_code'   => $this->household?->household_code,
            'is_first_time_job_seeker'  => $this->is_first_time_job_seeker,
            'archive_reason'            => $reason,
            'archive_notes'             => $notes,
            'archived_by'               => auth()->id(),
            'archived_at'               => now(),
        ]);
    }

    public static function generateCode(): string
    {
        $year  = date('Y');
        $count = self::withTrashed()->whereYear('created_at', $year)->count() + 1;
        return 'RES-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
