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
        if (Schema::hasColumn('record_requests', 'status')) {
            DB::statement("ALTER TABLE record_requests MODIFY COLUMN status VARCHAR(32) NOT NULL DEFAULT 'waiting'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('record_requests', 'status')) {
            DB::statement("ALTER TABLE record_requests MODIFY COLUMN status ENUM('waiting','processing','completed','rejected') NOT NULL DEFAULT 'waiting'");
        }
    }
};