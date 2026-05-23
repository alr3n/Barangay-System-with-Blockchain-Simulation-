<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedResident extends Model
{
    protected $fillable = [
        'original_resident_id',
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
        'previous_household_code',
        'is_first_time_job_seeker',
        'archive_reason',
        'archive_notes',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'birthdate'                => 'date',
        'archived_at'              => 'datetime',
        'is_first_time_job_seeker' => 'boolean',
    ];

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function originalResident()
    {
        return $this->belongsTo(Resident::class, 'original_resident_id')->withTrashed();
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

    public function getReasonBadgeClassAttribute(): string
    {
        return match ($this->archive_reason) {
            'deceased'    => 'bg-red-100 text-red-700',
            'inactive'    => 'bg-gray-100 text-gray-600',
            'transferred' => 'bg-yellow-100 text-yellow-700',
            'deleted'     => 'bg-purple-100 text-purple-700',
            default       => 'bg-gray-100 text-gray-600',
        };
    }
}
