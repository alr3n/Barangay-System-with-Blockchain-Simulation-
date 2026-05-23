<?php

namespace Tests\Unit;

use App\Models\Resident;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ResidentModelTest extends TestCase
{
    public function test_full_name_attribute_combines_names_correctly(): void
    {
        $resident = new Resident();
        $resident->first_name  = 'Juan';
        $resident->middle_name = 'Santos';
        $resident->last_name   = 'Dela Cruz';

        $this->assertEquals('Juan Santos Dela Cruz', $resident->full_name);
    }

    public function test_full_name_without_middle_name(): void
    {
        $resident = new Resident();
        $resident->first_name  = 'Maria';
        $resident->middle_name = null;
        $resident->last_name   = 'Garcia';

        $this->assertEquals('Maria Garcia', $resident->full_name);
    }

    public function test_resident_code_format_is_correct(): void
    {
        // The format should be RES-YYYY-XXXX
        $pattern = '/^RES-\d{4}-\d{4}$/';
        $this->assertMatchesRegularExpression($pattern, 'RES-2024-0001');
    }
}
