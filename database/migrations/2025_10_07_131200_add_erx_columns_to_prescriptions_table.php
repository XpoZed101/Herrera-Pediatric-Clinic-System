<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->boolean('erx_enabled')->default(false)->after('notes');
            $table->string('erx_status')->nullable()->after('erx_enabled'); // e.g., submitted, failed
            $table->string('erx_external_id')->nullable()->after('erx_status');
            $table->timestamp('erx_submitted_at')->nullable()->after('erx_external_id');
            $table->string('erx_pharmacy')->nullable()->after('erx_submitted_at');
            $table->text('erx_error')->nullable()->after('erx_pharmacy');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['erx_enabled', 'erx_status', 'erx_external_id', 'erx_submitted_at', 'erx_pharmacy', 'erx_error']);
        });
    }
};
