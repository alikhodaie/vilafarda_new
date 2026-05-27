<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('homes', 'draft_step')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->unsignedTinyInteger('draft_step')->nullable()->after('is_draft');
            });
        }

        DB::table('homes')->where('is_draft', false)->whereNull('draft_step')->update(['draft_step' => null]);
        DB::table('homes')->where('is_draft', true)->whereNull('draft_step')->update(['draft_step' => 1]);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('homes', 'draft_step')) {
            return;
        }

        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn('draft_step');
        });
    }
};
