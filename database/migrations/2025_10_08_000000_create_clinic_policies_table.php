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
        Schema::create('clinic_policies', function (Blueprint $table) {
            $table->id();
            $table->text('cancellation_policy')->nullable();
            $table->text('privacy_rules')->nullable();
            $table->text('staff_workflows')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_policies');
    }
};