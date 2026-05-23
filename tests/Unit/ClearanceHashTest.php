<?php

namespace Tests\Unit;

use App\Models\Clearance;
use PHPUnit\Framework\TestCase;

class ClearanceHashTest extends TestCase
{
    public function test_sha256_hash_is_generated_correctly(): void
    {
        $data = [
            'control_number' => 'BRG-202401-0001',
            'resident_id'    => 1,
            'document_type'  => 'barangay_clearance',
            'issued_date'    => '2024-01-15',
        ];

        $hash = Clearance::generateHash($data);

        // Hash should be a valid SHA-256 string (64 hex chars)
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash);
    }

    public function test_same_input_produces_same_hash(): void
    {
        $data = [
            'control_number' => 'BRG-202401-0001',
            'resident_id'    => 1,
            'document_type'  => 'barangay_clearance',
            'issued_date'    => '2024-01-15',
        ];

        $hash1 = Clearance::generateHash($data);
        $hash2 = Clearance::generateHash($data);

        $this->assertSame($hash1, $hash2);
    }

    public function test_different_input_produces_different_hash(): void
    {
        $data1 = [
            'control_number' => 'BRG-202401-0001',
            'resident_id'    => 1,
            'document_type'  => 'barangay_clearance',
            'issued_date'    => '2024-01-15',
        ];

        $data2 = [
            'control_number' => 'BRG-202401-0002', // different control number
            'resident_id'    => 1,
            'document_type'  => 'barangay_clearance',
            'issued_date'    => '2024-01-15',
        ];

        $this->assertNotSame(
            Clearance::generateHash($data1),
            Clearance::generateHash($data2)
        );
    }
}
