<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResidentFactory extends Factory
{
    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'resident_code'   => 'RES-' . date('Y') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'first_name'      => fake()->firstName(),
            'middle_name'     => fake()->lastName(),
            'last_name'       => fake()->lastName(),
            'birthdate'       => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'gender'          => fake()->randomElement(['male', 'female']),
            'civil_status'    => fake()->randomElement(['single', 'married', 'widowed', 'separated']),
            'address'         => fake()->streetAddress() . ', Barangay San Jose',
            'contact_number'  => '09' . fake()->numerify('#########'),
            'occupation'      => fake()->jobTitle(),
            'household_id'    => null,
            'is_household_head' => false,
            'resident_status' => 'active',
            'remarks'         => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attr) => ['resident_status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attr) => ['resident_status' => 'inactive']);
    }
}
