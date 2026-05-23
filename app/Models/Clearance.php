<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_number',
        'resident_id',
        'issued_by',
        'document_type',
        'purpose',
        'hash_code',
        'qr_code_path',
        'status',
        'issued_date',
        'expiry_date',
        'fee',
        'is_first_time_job_seeker_waiver',
        'notes',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
        'fee'         => 'decimal:2',
        'is_first_time_job_seeker_waiver' => 'boolean',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function verificationRecords()
    {
        return $this->hasMany(VerificationRecord::class);
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return match ($this->document_type) {
            'barangay_clearance'        => 'Barangay Clearance',
            'residency_certificate'     => 'Certificate of Residency',
            'indigency_certificate'     => 'Certificate of Indigency',
            'certificate_of_employment' => 'Certificate of Employment',
            default                     => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }

    public static function generateControlNumber(): string
    {
        $year  = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return 'BRG-' . $year . $month . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public static function generateHash(array $data): string
    {
        $payload = implode('|', [
            $data['control_number'],
            $data['resident_id'],
            $data['document_type'],
            $data['issued_date'],
            config('app.key'),
        ]);
        return hash('sha256', $payload);
    }
}
