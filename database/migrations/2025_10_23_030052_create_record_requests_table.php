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
        if (!Schema::hasTable('record_requests')) {
            Schema::create('record_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

                $table->string('record_type', 64); // history|vaccinations|prescriptions|diagnoses|visit_summaries|lab_results
                $table->date('date_start')->nullable();
                $table->date('date_end')->nullable();

                $table->string('delivery_method', 32); // download|email|pickup
                $table->string('delivery_email')->nullable();

                $table->string('purpose')->nullable();
                $table->text('notes')->nullable();

                $table->string('status', 32)->default('waiting'); // waiting|processing|completed|rejected

                $table->timestamps();

                $table->index(['patient_id']);
                $table->index(['status']);
                $table->index(['record_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_requests');
    }
};
