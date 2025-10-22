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
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('temperature', 4, 1)->nullable()->after('notes');
            $table->string('blood_pressure', 20)->nullable()->after('temperature');
            $table->unsignedSmallInteger('heart_rate')->nullable()->after('blood_pressure');
            $table->unsignedSmallInteger('respiratory_rate')->nullable()->after('heart_rate');
            $table->unsignedTinyInteger('oxygen_saturation')->nullable()->after('respiratory_rate');
            $table->timestamp('vitals_recorded_at')->nullable()->after('oxygen_saturation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'temperature',
                'blood_pressure',
                'heart_rate',
                'respiratory_rate',
                'oxygen_saturation',
                'vitals_recorded_at',
            ]);
        });
    }
};