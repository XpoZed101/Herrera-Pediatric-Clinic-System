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
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('checked_in_at')->nullable()->after('scheduled_at');
            $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
            $table->foreignId('checked_in_by')->nullable()->after('checked_out_at')->constrained('users')->nullOnDelete();
            $table->foreignId('checked_out_by')->nullable()->after('checked_in_by')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('checked_in_by');
            $table->dropConstrainedForeignId('checked_out_by');
            $table->dropColumn(['checked_in_at', 'checked_out_at']);
        });
    }
};