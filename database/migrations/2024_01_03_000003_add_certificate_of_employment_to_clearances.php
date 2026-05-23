<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend the document_type ENUM to include certificate_of_employment.
        // Using raw SQL because Laravel's schema builder needs doctrine/dbal to alter enums.
        DB::statement("
            ALTER TABLE clearances
            MODIFY COLUMN document_type ENUM(
                'barangay_clearance',
                'residency_certificate',
                'indigency_certificate',
                'certificate_of_employment'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        // Roll back to the original three types
        DB::statement("
            ALTER TABLE clearances
            MODIFY COLUMN document_type ENUM(
                'barangay_clearance',
                'residency_certificate',
                'indigency_certificate'
            ) NOT NULL
        ");
    }
};
