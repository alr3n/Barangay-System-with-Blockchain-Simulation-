<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_code',
        'address',
        'purok',
        'street',
        'house_type',
        'house_type_other',
        'owner_name',
        'owner_contact',
        'owner_address',
    ];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function head()
    {
        return $this->hasOne(Resident::class)->where('is_household_head', true);
    }

    public function getMemberCountAttribute(): int
    {
        return $this->residents()->count();
    }

    /**
     * Resolve the human-readable house type label.
     * If type is "other", return the custom label entered by the user.
     */
    public function getHouseTypeLabelAttribute(): string
    {
        if ($this->house_type === 'other' && filled($this->house_type_other)) {
            return $this->house_type_other;
        }
        return ucfirst($this->house_type);
    }

    /**
     * Does this household require owner details?
     */
    public function requiresOwnerDetails(): bool
    {
        return in_array($this->house_type, ['rented', 'shared']);
    }
}
