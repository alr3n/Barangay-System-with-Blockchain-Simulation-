<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_records', function (Blueprint $table) {
            $table->id();
            $table->string('hash_queried');
            $table->enum('result', ['verified', 'invalid', 'tampered']);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('clearance_id')->nullable()->constrained('clearances')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_records');
    }
};
