<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            // Owner details — required when house_type is 'rented' or 'shared'
            $table->string('owner_name')->nullable()->after('house_type');
            $table->string('owner_contact')->nullable()->after('owner_name');
            $table->string('owner_address')->nullable()->after('owner_contact');

            // Custom label when house_type === 'other'
            $table->string('house_type_other')->nullable()->after('owner_address');
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'owner_contact', 'owner_address', 'house_type_other']);
        });
    }
};
