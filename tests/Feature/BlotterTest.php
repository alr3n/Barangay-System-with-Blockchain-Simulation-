<?php

namespace Tests\Feature;

use App\Models\Blotter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlotterTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create();
    }

    public function test_blotter_index_is_accessible(): void
    {
        $this->actingAs($this->staff)
             ->get(route('blotter.index'))
             ->assertStatus(200);
    }

    public function test_blotter_can_be_filed(): void
    {
        $this->actingAs($this->staff)
             ->post(route('blotter.store'), [
                 'complainant_name'    => 'Jose Santos',
                 'complainant_address' => '123 Test Street',
                 'complainant_contact' => '09171234567',
                 'respondent_name'     => 'Pedro Cruz',
                 'respondent_address'  => '456 Test Avenue',
                 'incident_date'       => '2024-05-01',
                 'incident_time'       => '14:00',
                 'incident_location'   => '123 Test Street',
                 'incident_type'       => 'Noise Complaint',
                 'incident_details'    => 'Respondent was playing loud music.',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('blotters', [
            'complainant_name' => 'Jose Santos',
            'incident_type'    => 'Noise Complaint',
            'status'           => 'pending',
        ]);
    }

    public function test_blotter_status_can_be_updated_to_resolved(): void
    {
        $blotter = Blotter::factory()->create([
            'blotter_number'      => 'BLT-2024-0001',
            'complainant_name'    => 'Jose Santos',
            'complainant_address' => '123 Test',
            'respondent_name'     => 'Pedro Cruz',
            'respondent_address'  => '456 Test',
            'incident_date'       => '2024-05-01',
            'incident_location'   => 'Test Location',
            'incident_type'       => 'Noise Complaint',
            'incident_details'    => 'Test details.',
            'status'              => 'pending',
        ]);

        $this->actingAs($this->staff)
             ->put(route('blotter.update', $blotter), [
                 'complainant_name'    => 'Jose Santos',
                 'complainant_address' => '123 Test',
                 'respondent_name'     => 'Pedro Cruz',
                 'respondent_address'  => '456 Test',
                 'incident_date'       => '2024-05-01',
                 'incident_location'   => 'Test Location',
                 'incident_type'       => 'Noise Complaint',
                 'incident_details'    => 'Test details.',
                 'status'              => 'resolved',
                 'resolution_notes'    => 'Both parties settled amicably.',
                 'resolved_date'       => '2024-05-03',
             ]);

        $this->assertDatabaseHas('blotters', [
            'id'     => $blotter->id,
            'status' => 'resolved',
        ]);
    }

    public function test_blotter_requires_mandatory_fields(): void
    {
        $this->actingAs($this->staff)
             ->post(route('blotter.store'), [])
             ->assertSessionHasErrors([
                 'complainant_name',
                 'respondent_name',
                 'incident_date',
                 'incident_type',
             ]);
    }
}
