<?php

namespace Database\Seeders;

use App\Models\Clearance;
use Illuminate\Database\Seeder;

class ClearanceSeeder extends Seeder
{
    public function run(): void
    {
        $clearances = [
            ['resident_id'=>1,'document_type'=>'barangay_clearance','purpose'=>'Employment','fee'=>100,'issued_date'=>'2024-01-10','expiry_date'=>'2025-01-10'],
            ['resident_id'=>2,'document_type'=>'residency_certificate','purpose'=>'School Enrollment','fee'=>50,'issued_date'=>'2024-01-15','expiry_date'=>null],
            ['resident_id'=>4,'document_type'=>'barangay_clearance','purpose'=>'Business Permit','fee'=>100,'issued_date'=>'2024-02-01','expiry_date'=>'2025-02-01'],
            ['resident_id'=>6,'document_type'=>'indigency_certificate','purpose'=>'Medical Assistance','fee'=>0,'issued_date'=>'2024-02-14','expiry_date'=>null],
            ['resident_id'=>7,'document_type'=>'barangay_clearance','purpose'=>'Loan Application','fee'=>100,'issued_date'=>'2024-03-05','expiry_date'=>'2025-03-05'],
            ['resident_id'=>9,'document_type'=>'residency_certificate','purpose'=>'Passport Application','fee'=>50,'issued_date'=>'2024-03-20','expiry_date'=>null],
            ['resident_id'=>11,'document_type'=>'barangay_clearance','purpose'=>'Employment','fee'=>100,'issued_date'=>'2024-04-02','expiry_date'=>'2025-04-02'],
            ['resident_id'=>13,'document_type'=>'indigency_certificate','purpose'=>'Scholarship','fee'=>0,'issued_date'=>'2024-04-18','expiry_date'=>null],
            ['resident_id'=>5,'document_type'=>'barangay_clearance','purpose'=>'Employment','fee'=>100,'issued_date'=>'2024-05-10','expiry_date'=>'2025-05-10'],
            ['resident_id'=>8,'document_type'=>'residency_certificate','purpose'=>'Voter Registration','fee'=>50,'issued_date'=>'2024-05-25','expiry_date'=>null],
            ['resident_id'=>10,'document_type'=>'barangay_clearance','purpose'=>'Travel Abroad','fee'=>100,'issued_date'=>'2024-06-08','expiry_date'=>'2025-06-08'],
            ['resident_id'=>15,'document_type'=>'indigency_certificate','purpose'=>'Burial Assistance','fee'=>0,'issued_date'=>'2024-06-22','expiry_date'=>null],
        ];

        foreach ($clearances as $index => $c) {
            $cn = 'BRG-' . date('Ym', strtotime($c['issued_date'])) . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $hash = Clearance::generateHash([
                'control_number' => $cn,
                'resident_id'    => $c['resident_id'],
                'document_type'  => $c['document_type'],
                'issued_date'    => $c['issued_date'],
            ]);

            Clearance::create([
                ...$c,
                'control_number' => $cn,
                'issued_by'      => 1,
                'hash_code'      => $hash,
                'status'         => 'active',
                'notes'          => null,
            ]);
        }
    }
}
