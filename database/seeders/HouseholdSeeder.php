<?php

namespace Database\Seeders;

use App\Models\Household;
use Illuminate\Database\Seeder;

class HouseholdSeeder extends Seeder
{
    public function run(): void
    {
        $households = [
            ['household_code' => 'HH-2024-0001', 'address' => '123 Rizal Street, Barangay San Jose', 'purok' => 'Purok 1', 'street' => 'Rizal Street', 'house_type' => 'owned'],
            ['household_code' => 'HH-2024-0002', 'address' => '45 Mabini Avenue, Barangay San Jose', 'purok' => 'Purok 1', 'street' => 'Mabini Avenue', 'house_type' => 'rented'],
            ['household_code' => 'HH-2024-0003', 'address' => '78 Luna Street, Barangay San Jose', 'purok' => 'Purok 2', 'street' => 'Luna Street', 'house_type' => 'owned'],
            ['household_code' => 'HH-2024-0004', 'address' => '12 Bonifacio Road, Barangay San Jose', 'purok' => 'Purok 2', 'street' => 'Bonifacio Road', 'house_type' => 'owned'],
            ['household_code' => 'HH-2024-0005', 'address' => '90 Aguinaldo Street, Barangay San Jose', 'purok' => 'Purok 3', 'street' => 'Aguinaldo Street', 'house_type' => 'shared'],
            ['household_code' => 'HH-2024-0006', 'address' => '34 Quezon Boulevard, Barangay San Jose', 'purok' => 'Purok 3', 'street' => 'Quezon Boulevard', 'house_type' => 'owned'],
            ['household_code' => 'HH-2024-0007', 'address' => '56 Magsaysay Lane, Barangay San Jose', 'purok' => 'Purok 4', 'street' => 'Magsaysay Lane', 'house_type' => 'rented'],
            ['household_code' => 'HH-2024-0008', 'address' => '22 Marcos Street, Barangay San Jose', 'purok' => 'Purok 4', 'street' => 'Marcos Street', 'house_type' => 'owned'],
        ];

        foreach ($households as $hh) {
            Household::create($hh);
        }
    }
}
