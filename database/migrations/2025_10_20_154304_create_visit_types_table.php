<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visit_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g., 'well_visit', 'follow_up'
            $table->string('name'); // e.g., 'Well Visit'
            $table->unsignedInteger('amount_cents'); // price in centavos
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed common defaults for immediate usability
        DB::table('visit_types')->insert([
            [
                'slug' => 'well_visit',
                'name' => 'Well Visit',
                'amount_cents' => 1000 * 100,
                'is_active' => true,
                'description' => 'Routine wellness examination and general check-up.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sick_visit',
                'name' => 'Sick Visit',
                'amount_cents' => 1000 * 100,
                'is_active' => true,
                'description' => 'Evaluation for illness, symptoms, or acute concerns.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'follow_up',
                'name' => 'Follow Up',
                'amount_cents' => 500 * 100,
                'is_active' => true,
                'description' => 'Follow-up appointment related to a previous visit.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'immunization',
                'name' => 'Immunization',
                'amount_cents' => 1000 * 100,
                'is_active' => true,
                'description' => 'Vaccination appointment (vaccine cost may be separate).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'consultation',
                'name' => 'Consultation',
                'amount_cents' => 1000 * 100,
                'is_active' => true,
                'description' => 'General consultation and medical advice.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_types');
    }
};
