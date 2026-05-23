<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'System Administrator',
            'email'     => 'admin@barangay.gov.ph',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Maria Santos',
            'email'     => 'staff@barangay.gov.ph',
            'password'  => Hash::make('staff123'),
            'role'      => 'staff',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Juan Dela Cruz',
            'email'     => 'juan@barangay.gov.ph',
            'password'  => Hash::make('staff123'),
            'role'      => 'staff',
            'is_active' => true,
        ]);
    }
}
