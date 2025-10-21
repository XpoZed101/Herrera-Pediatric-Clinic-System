<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phone_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('caller_name', 120);
            $table->string('caller_phone', 50)->nullable();
            $table->text('reason');
            $table->string('triage_level', 20); // emergency|urgent|routine
            $table->string('action', 20); // advice|callback|schedule|escalate
            $table->date('callback_date')->nullable();
            $table->string('status', 30)->default('open'); // open|awaiting_callback|scheduled|closed|escalated
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['triage_level']);
            $table->index(['status']);
            $table->index(['callback_date']);
            $table->index(['patient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_inquiries');
    }
};
