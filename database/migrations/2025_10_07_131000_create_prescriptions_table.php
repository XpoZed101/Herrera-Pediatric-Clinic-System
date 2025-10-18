<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prescribed_by')->nullable()->constrained('users')->nullOnDelete();

            // Either medication or treatment; keep concerns separate via type + fields
            $table->enum('type', ['medication', 'treatment']);

            // Common fields
            $table->string('name');
            $table->text('instructions')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Medication-specific
            $table->string('dosage')->nullable(); // e.g., 5mg
            $table->string('frequency')->nullable(); // e.g., twice daily
            $table->string('route')->nullable(); // e.g., oral, topical

            // Treatment-specific
            $table->string('treatment_schedule')->nullable(); // e.g., PT 3x/week

            $table->string('status')->default('active'); // active, completed, discontinued
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};