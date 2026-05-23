<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('resident_code')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birthdate');
            $table->enum('gender', ['male', 'female']);
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated', 'annulled'])->default('single');
            $table->string('address');
            $table->string('contact_number')->nullable();
            $table->string('occupation')->nullable();
            $table->foreignId('household_id')->nullable()->constrained('households')->nullOnDelete();
            $table->boolean('is_household_head')->default(false);
            $table->enum('resident_status', ['active', 'inactive', 'deceased', 'transferred'])->default('active');
            $table->string('profile_photo')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
