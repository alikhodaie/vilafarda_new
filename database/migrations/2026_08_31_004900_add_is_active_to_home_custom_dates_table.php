<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_custom_dates', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('min_nights');
        });

        DB::table('home_custom_dates')->where('price', 0)->update(['is_active' => false]);
    }

    public function down(): void
    {
        Schema::table('home_custom_dates', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
