<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            $table->string('blotter_number')->unique();
            $table->string('complainant_name');
            $table->string('complainant_address');
            $table->string('complainant_contact')->nullable();
            $table->string('respondent_name');
            $table->string('respondent_address');
            $table->string('respondent_contact')->nullable();
            $table->date('incident_date');
            $table->time('incident_time')->nullable();
            $table->string('incident_location');
            $table->string('incident_type');
            $table->text('incident_details');
            $table->enum('status', ['pending', 'ongoing', 'resolved'])->default('pending');
            $table->text('resolution_notes')->nullable();
            $table->date('resolved_date')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blotters');
    }
};
