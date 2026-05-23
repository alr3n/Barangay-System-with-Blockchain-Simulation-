<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlotterFactory extends Factory
{
    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'blotter_number'       => 'BLT-' . date('Y') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'complainant_name'     => fake()->name(),
            'complainant_address'  => fake()->streetAddress(),
            'complainant_contact'  => '09' . fake()->numerify('#########'),
            'respondent_name'      => fake()->name(),
            'respondent_address'   => fake()->streetAddress(),
            'respondent_contact'   => null,
            'incident_date'        => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'incident_time'        => fake()->time('H:i'),
            'incident_location'    => fake()->streetAddress(),
            'incident_type'        => fake()->randomElement(['Noise Complaint','Physical Altercation','Theft','Vandalism']),
            'incident_details'     => fake()->paragraph(3),
            'status'               => 'pending',
            'resolution_notes'     => null,
            'resolved_date'        => null,
            'handled_by'           => null,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attr) => [
            'status'           => 'resolved',
            'resolution_notes' => fake()->sentence(),
            'resolved_date'    => now()->toDateString(),
        ]);
    }
}
