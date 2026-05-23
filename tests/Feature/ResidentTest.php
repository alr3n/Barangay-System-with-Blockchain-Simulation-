<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_resident_list_is_accessible(): void
    {
        $this->actingAs($this->admin)
             ->get(route('residents.index'))
             ->assertStatus(200);
    }

    public function test_resident_can_be_created(): void
    {
        $this->actingAs($this->admin)
             ->post(route('residents.store'), [
                 'first_name'      => 'Juan',
                 'middle_name'     => 'Santos',
                 'last_name'       => 'Dela Cruz',
                 'birthdate'       => '1990-01-15',
                 'gender'          => 'male',
                 'civil_status'    => 'single',
                 'address'         => '123 Test Street',
                 'resident_status' => 'active',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('residents', [
            'first_name' => 'Juan',
            'last_name'  => 'Dela Cruz',
        ]);
    }

    public function test_resident_creation_requires_mandatory_fields(): void
    {
        $this->actingAs($this->admin)
             ->post(route('residents.store'), [])
             ->assertSessionHasErrors(['first_name', 'last_name', 'birthdate', 'gender']);
    }

    public function test_resident_can_be_updated(): void
    {
        $resident = Resident::factory()->create([
            'first_name' => 'Old Name',
            'last_name'  => 'Surname',
            'birthdate'  => '1985-06-10',
            'gender'     => 'male',
            'civil_status' => 'single',
            'address'    => 'Old Address',
            'resident_status' => 'active',
            'resident_code' => 'RES-2024-0001',
        ]);

        $this->actingAs($this->admin)
             ->put(route('residents.update', $resident), [
                 'first_name'      => 'New Name',
                 'last_name'       => 'Surname',
                 'birthdate'       => '1985-06-10',
                 'gender'          => 'male',
                 'civil_status'    => 'single',
                 'address'         => 'New Address',
                 'resident_status' => 'active',
             ]);

        $this->assertDatabaseHas('residents', ['first_name' => 'New Name']);
    }

    public function test_resident_can_be_soft_deleted(): void
    {
        $resident = Resident::factory()->create([
            'resident_code' => 'RES-2024-0001',
            'birthdate'     => '1990-01-01',
            'gender'        => 'male',
            'civil_status'  => 'single',
            'address'       => 'Test',
            'resident_status' => 'active',
        ]);

        $this->actingAs($this->admin)
             ->delete(route('residents.destroy', $resident));

        $this->assertSoftDeleted('residents', ['id' => $resident->id]);
    }
}
