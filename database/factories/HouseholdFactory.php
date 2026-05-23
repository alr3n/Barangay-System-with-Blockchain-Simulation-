<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HouseholdFactory extends Factory
{
    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'household_code' => 'HH-' . date('Y') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'address'        => fake()->streetAddress() . ', Barangay San Jose',
            'purok'          => 'Purok ' . fake()->numberBetween(1, 6),
            'street'         => fake()->streetName(),
            'house_type'     => fake()->randomElement(['owned', 'rented', 'shared', 'other']),
        ];
    }
}
