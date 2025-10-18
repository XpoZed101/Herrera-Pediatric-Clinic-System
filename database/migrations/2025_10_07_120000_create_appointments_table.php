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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->enum('visit_type', ['well_visit', 'sick_visit', 'follow_up', 'immunization', 'consultation']);
            $table->string('reason')->nullable();
            $table->enum('status', ['requested', 'scheduled', 'cancelled', 'completed'])->default('requested');
            $table->text('notes')->nullable();

            // Current symptoms (checkboxes)
            $table->boolean('fever')->default(false);
            $table->boolean('cough')->default(false);
            $table->boolean('rash')->default(false);
            $table->boolean('ear_pain')->default(false);
            $table->boolean('stomach_pain')->default(false);
            $table->boolean('diarrhea')->default(false);
            $table->boolean('vomiting')->default(false);
            $table->boolean('headaches')->default(false);
            $table->boolean('trouble_breathing')->default(false);
            $table->string('symptom_other')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};