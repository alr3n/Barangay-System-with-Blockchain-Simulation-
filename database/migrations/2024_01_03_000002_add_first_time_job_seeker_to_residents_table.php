<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // RA 11261 — First-Time Jobseekers Assistance Act
            $table->boolean('is_first_time_job_seeker')->default(false)->after('occupation');
            $table->date('first_time_job_seeker_certified_at')->nullable()->after('is_first_time_job_seeker');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['is_first_time_job_seeker', 'first_time_job_seeker_certified_at']);
        });
    }
};
