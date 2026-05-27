<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (! Schema::hasColumn('homes', 'cover_alt')) {
                $table->string('cover_alt', 255)->nullable()->after('cover');
            }
        });

        Schema::table('home_images', function (Blueprint $table) {
            if (! Schema::hasColumn('home_images', 'alt_text')) {
                $table->string('alt_text', 255)->nullable()->after('original_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            if (Schema::hasColumn('homes', 'cover_alt')) {
                $table->dropColumn('cover_alt');
            }
        });

        Schema::table('home_images', function (Blueprint $table) {
            if (Schema::hasColumn('home_images', 'alt_text')) {
                $table->dropColumn('alt_text');
            }
        });
    }
};
