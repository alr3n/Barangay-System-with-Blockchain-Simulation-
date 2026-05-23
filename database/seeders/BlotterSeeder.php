<?php

namespace Database\Seeders;

use App\Models\Blotter;
use Illuminate\Database\Seeder;

class BlotterSeeder extends Seeder
{
    public function run(): void
    {
        $blotters = [
            [
                'blotter_number'     => 'BLT-2024-0001',
                'complainant_name'   => 'Jose Santos',
                'complainant_address'=> '123 Rizal Street',
                'complainant_contact'=> '09171234567',
                'respondent_name'    => 'Unknown Neighbor',
                'respondent_address' => '125 Rizal Street',
                'respondent_contact' => null,
                'incident_date'      => '2024-02-10',
                'incident_time'      => '21:30',
                'incident_location'  => '123 Rizal Street',
                'incident_type'      => 'Noise Complaint',
                'incident_details'   => 'Complainant reported that respondent was playing loud music past 10 PM disturbing the neighborhood.',
                'status'             => 'resolved',
                'resolution_notes'   => 'Both parties were called and settled amicably. Respondent agreed to avoid loud music after 9 PM.',
                'resolved_date'      => '2024-02-12',
                'handled_by'         => 1,
            ],
            [
                'blotter_number'     => 'BLT-2024-0002',
                'complainant_name'   => 'Ana Dela Cruz',
                'complainant_address'=> '45 Mabini Avenue',
                'complainant_contact'=> '09191234567',
                'respondent_name'    => 'Mark Torres',
                'respondent_address' => '50 Mabini Avenue',
                'respondent_contact' => '09301122334',
                'incident_date'      => '2024-03-05',
                'incident_time'      => '14:00',
                'incident_location'  => 'Mabini Avenue Corner',
                'incident_type'      => 'Physical Altercation',
                'incident_details'   => 'Complainant reports being verbally and physically harassed by respondent over a land boundary dispute.',
                'status'             => 'ongoing',
                'resolution_notes'   => 'Case referred to barangay mediation. Scheduled hearing on March 15.',
                'resolved_date'      => null,
                'handled_by'         => 2,
            ],
            [
                'blotter_number'     => 'BLT-2024-0003',
                'complainant_name'   => 'Pedro Garcia',
                'complainant_address'=> '12 Bonifacio Road',
                'complainant_contact'=> '09221234567',
                'respondent_name'    => 'Unknown Persons',
                'respondent_address' => 'Unknown',
                'respondent_contact' => null,
                'incident_date'      => '2024-04-15',
                'incident_time'      => '02:00',
                'incident_location'  => '12 Bonifacio Road',
                'incident_type'      => 'Theft',
                'incident_details'   => 'Complainant reported that his motorcycle was stolen from outside his house in the early morning hours.',
                'status'             => 'pending',
                'resolution_notes'   => null,
                'resolved_date'      => null,
                'handled_by'         => 1,
            ],
            [
                'blotter_number'     => 'BLT-2024-0004',
                'complainant_name'   => 'Rosario Flores',
                'complainant_address'=> '90 Aguinaldo Street',
                'complainant_contact'=> null,
                'respondent_name'    => 'Stray Animals Owner',
                'respondent_address' => 'Purok 3 Area',
                'respondent_contact' => null,
                'incident_date'      => '2024-05-20',
                'incident_time'      => '08:00',
                'incident_location'  => 'Aguinaldo Street',
                'incident_type'      => 'Animal Nuisance',
                'incident_details'   => 'Complainant reports multiple stray dogs terrorizing residents along Aguinaldo Street. One child was bitten.',
                'status'             => 'resolved',
                'resolution_notes'   => 'Barangay coordinated with city pound. Stray animals were collected and proper warnings issued.',
                'resolved_date'      => '2024-05-25',
                'handled_by'         => 2,
            ],
        ];

        foreach ($blotters as $b) {
            Blotter::create($b);
        }
    }
}
