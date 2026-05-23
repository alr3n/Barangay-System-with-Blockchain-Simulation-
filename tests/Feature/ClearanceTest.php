<?php

namespace Tests\Feature;

use App\Models\Clearance;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearanceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_clearance_index_is_accessible(): void
    {
        $this->actingAs($this->admin)
             ->get(route('clearances.index'))
             ->assertStatus(200);
    }

    public function test_clearance_can_be_issued(): void
    {
        $resident = Resident::factory()->create([
            'resident_code' => 'RES-2024-0001',
            'resident_status' => 'active',
        ]);

        $this->actingAs($this->admin)
             ->post(route('clearances.store'), [
                 'resident_id'   => $resident->id,
                 'document_type' => 'barangay_clearance',
                 'purpose'       => 'Employment',
                 'fee'           => 100,
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('clearances', [
            'resident_id'   => $resident->id,
            'document_type' => 'barangay_clearance',
            'purpose'       => 'Employment',
        ]);
    }

    public function test_clearance_hash_is_generated_on_issuance(): void
    {
        $resident = Resident::factory()->create([
            'resident_code' => 'RES-2024-0001',
            'resident_status' => 'active',
        ]);

        $this->actingAs($this->admin)
             ->post(route('clearances.store'), [
                 'resident_id'   => $resident->id,
                 'document_type' => 'residency_certificate',
                 'purpose'       => 'Passport',
                 'fee'           => 50,
             ]);

        $clearance = Clearance::first();
        $this->assertNotNull($clearance->hash_code);
        $this->assertEquals(64, strlen($clearance->hash_code));
    }

    public function test_verification_page_returns_verified_for_valid_hash(): void
    {
        $resident = Resident::factory()->create([
            'resident_code' => 'RES-2024-0001',
            'resident_status' => 'active',
        ]);

        $this->actingAs($this->admin)
             ->post(route('clearances.store'), [
                 'resident_id'   => $resident->id,
                 'document_type' => 'barangay_clearance',
                 'purpose'       => 'Employment',
                 'fee'           => 100,
             ]);

        $clearance = Clearance::first();

        $response = $this->post(route('verify.check'), [
            'hash_code' => $clearance->hash_code,
        ]);

        $response->assertStatus(200);
        $response->assertSee('verified', false);
    }

    public function test_verification_returns_invalid_for_unknown_hash(): void
    {
        $response = $this->post(route('verify.check'), [
            'hash_code' => str_repeat('a', 64),
        ]);

        $response->assertStatus(200);
        $response->assertSee('invalid', false);
    }

    public function test_clearance_can_be_revoked(): void
    {
        $resident = Resident::factory()->create([
            'resident_code' => 'RES-2024-0001',
            'resident_status' => 'active',
        ]);

        $this->actingAs($this->admin)
             ->post(route('clearances.store'), [
                 'resident_id'   => $resident->id,
                 'document_type' => 'barangay_clearance',
                 'purpose'       => 'Test',
                 'fee'           => 0,
             ]);

        $clearance = Clearance::first();

        $this->actingAs($this->admin)
             ->patch(route('clearances.revoke', $clearance));

        $this->assertDatabaseHas('clearances', [
            'id'     => $clearance->id,
            'status' => 'revoked',
        ]);
    }
}
