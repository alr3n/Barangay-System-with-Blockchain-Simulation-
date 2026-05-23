<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archived_residents', function (Blueprint $table) {
            $table->id();

            // Snapshot of the resident at time of archiving
            $table->unsignedBigInteger('original_resident_id')->nullable()->index();
            $table->string('resident_code');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('civil_status');
            $table->string('address');
            $table->string('contact_number')->nullable();
            $table->string('occupation')->nullable();
            $table->string('previous_household_code')->nullable();
            $table->boolean('is_first_time_job_seeker')->default(false);

            // Audit trail
            $table->enum('archive_reason', ['deceased', 'inactive', 'transferred', 'deleted'])->index();
            $table->text('archive_notes')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('archived_at');

            $table->timestamps();

            $table->index(['archive_reason', 'archived_at']);
            $table->index(['last_name', 'first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_residents');
    }
};
