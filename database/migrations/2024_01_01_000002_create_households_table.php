<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('household_code')->unique();
            $table->string('address');
            $table->string('purok')->nullable();
            $table->string('street')->nullable();
            $table->enum('house_type', ['owned', 'rented', 'shared', 'other'])->default('owned');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
