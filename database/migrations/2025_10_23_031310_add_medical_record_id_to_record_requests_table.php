<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('record_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('record_requests', 'medical_record_id')) {
                $table->foreignId('medical_record_id')->nullable()->constrained('medical_records')->nullOnDelete()->after('user_id');
                $table->index(['medical_record_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('record_requests', function (Blueprint $table) {
            if (Schema::hasColumn('record_requests', 'medical_record_id')) {
                // Drop index first for safety, then drop constrained foreign key
                $table->dropIndex(['medical_record_id']);
                $table->dropConstrainedForeignId('medical_record_id');
            }
        });
    }
};