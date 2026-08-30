<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE home_custom_dates MODIFY `date` DATE NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE home_custom_dates MODIFY `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }
};
