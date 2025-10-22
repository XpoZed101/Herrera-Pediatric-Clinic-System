<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('waitlist_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->string('triage_level', 20); // emergency|urgent|routine
            $table->unsignedTinyInteger('priority')->default(1);
            $table->date('desired_date_start')->nullable();
            $table->date('desired_date_end')->nullable();
            $table->string('status', 20)->default('waiting'); // waiting|invited|scheduled|expired|cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id']);
            $table->index(['status']);
            $table->index(['priority']);
            $table->index(['desired_date_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlist_entries');
    }
};