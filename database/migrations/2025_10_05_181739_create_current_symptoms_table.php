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
        Schema::create('current_symptoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('symptom_type', [
                'fever',
                'cough',
                'rash',
                'ear_pain',
                'stomach_pain',
                'diarrhea',
                'vomiting',
                'headaches',
                'trouble_breathing',
                'other',
            ]);
            $table->string('other_name')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_symptoms');
    }
};
