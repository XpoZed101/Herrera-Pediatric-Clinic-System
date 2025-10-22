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
            if (!Schema::hasColumn('record_requests', 'patient_id')) {
                $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete()->after('id');
            }
            if (!Schema::hasColumn('record_requests', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('patient_id');
            }
            if (!Schema::hasColumn('record_requests', 'record_type')) {
                $table->string('record_type', 64)->after('user_id');
            }
            if (!Schema::hasColumn('record_requests', 'date_start')) {
                $table->date('date_start')->nullable()->after('record_type');
            }
            if (!Schema::hasColumn('record_requests', 'date_end')) {
                $table->date('date_end')->nullable()->after('date_start');
            }
            if (!Schema::hasColumn('record_requests', 'delivery_method')) {
                $table->string('delivery_method', 32)->after('date_end');
            }
            if (!Schema::hasColumn('record_requests', 'delivery_email')) {
                $table->string('delivery_email')->nullable()->after('delivery_method');
            }
            if (!Schema::hasColumn('record_requests', 'purpose')) {
                $table->string('purpose')->nullable()->after('delivery_email');
            }
            if (!Schema::hasColumn('record_requests', 'notes')) {
                $table->text('notes')->nullable()->after('purpose');
            }
            if (!Schema::hasColumn('record_requests', 'status')) {
                $table->string('status', 32)->default('waiting')->after('notes');
            }

            // Indexes
            if (!Schema::hasColumn('record_requests', 'patient_id')) {
                $table->index(['patient_id']);
            }
            if (!Schema::hasColumn('record_requests', 'status')) {
                $table->index(['status']);
            }
            if (!Schema::hasColumn('record_requests', 'record_type')) {
                $table->index(['record_type']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('record_requests', function (Blueprint $table) {
            // drop in reverse order if they exist
            if (Schema::hasColumn('record_requests', 'record_type')) {
                $table->dropIndex(['record_type']);
            }
            if (Schema::hasColumn('record_requests', 'status')) {
                $table->dropIndex(['status']);
            }
            if (Schema::hasColumn('record_requests', 'patient_id')) {
                $table->dropIndex(['patient_id']);
            }

            if (Schema::hasColumn('record_requests', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('record_requests', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('record_requests', 'purpose')) {
                $table->dropColumn('purpose');
            }
            if (Schema::hasColumn('record_requests', 'delivery_email')) {
                $table->dropColumn('delivery_email');
            }
            if (Schema::hasColumn('record_requests', 'delivery_method')) {
                $table->dropColumn('delivery_method');
            }
            if (Schema::hasColumn('record_requests', 'date_end')) {
                $table->dropColumn('date_end');
            }
            if (Schema::hasColumn('record_requests', 'date_start')) {
                $table->dropColumn('date_start');
            }
            if (Schema::hasColumn('record_requests', 'record_type')) {
                $table->dropColumn('record_type');
            }
            if (Schema::hasColumn('record_requests', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('record_requests', 'patient_id')) {
                $table->dropConstrainedForeignId('patient_id');
            }
        });
    }
};
