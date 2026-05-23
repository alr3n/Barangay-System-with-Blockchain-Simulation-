<?php

namespace Tests\Feature;

use App\Models\Household;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HouseholdTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create();
    }

    public function test_household_index_is_accessible(): void
    {
        $this->actingAs($this->staff)
             ->get(route('households.index'))
             ->assertStatus(200);
    }

    public function test_household_can_be_created(): void
    {
        $this->actingAs($this->staff)
             ->post(route('households.store'), [
                 'address'    => '123 Rizal Street, Barangay San Jose',
                 'purok'      => 'Purok 1',
                 'street'     => 'Rizal Street',
                 'house_type' => 'owned',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('households', [
            'address'    => '123 Rizal Street, Barangay San Jose',
            'house_type' => 'owned',
        ]);
    }

    public function test_household_code_is_auto_generated(): void
    {
        $this->actingAs($this->staff)
             ->post(route('households.store'), [
                 'address'    => '99 Test Road',
                 'house_type' => 'rented',
             ]);

        $hh = Household::first();
        $this->assertNotNull($hh->household_code);
        $this->assertStringStartsWith('HH-', $hh->household_code);
    }

    public function test_household_requires_address_and_type(): void
    {
        $this->actingAs($this->staff)
             ->post(route('households.store'), [])
             ->assertSessionHasErrors(['address', 'house_type']);
    }
}
