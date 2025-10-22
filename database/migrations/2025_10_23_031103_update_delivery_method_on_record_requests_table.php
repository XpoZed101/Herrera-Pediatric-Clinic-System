<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('record_requests', 'delivery_method')) {
            DB::statement("ALTER TABLE record_requests MODIFY COLUMN delivery_method VARCHAR(32) NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to an ENUM without 'download' if needed
        if (Schema::hasColumn('record_requests', 'delivery_method')) {
            DB::statement("ALTER TABLE record_requests MODIFY COLUMN delivery_method ENUM('email','pickup') NOT NULL");
        }
    }
};
