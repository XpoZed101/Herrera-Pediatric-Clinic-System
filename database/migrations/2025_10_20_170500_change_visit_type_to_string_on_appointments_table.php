<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Switch appointments.visit_type from ENUM to VARCHAR to support dynamic VisitType slugs
        DB::statement("ALTER TABLE appointments MODIFY COLUMN visit_type VARCHAR(64) NOT NULL");
    }

    public function down(): void
    {
        // Revert back to the original ENUM values used previously
        DB::statement("ALTER TABLE appointments MODIFY COLUMN visit_type ENUM('well_visit','sick_visit','follow_up','immunization','consultation') NOT NULL");
    }
};