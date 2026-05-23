<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearances', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->unique();
            $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('users');
            $table->enum('document_type', ['barangay_clearance', 'residency_certificate', 'indigency_certificate']);
            $table->string('purpose');
            $table->string('hash_code')->unique();
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['active', 'revoked'])->default('active');
            $table->date('issued_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('fee', 8, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearances');
    }
};
