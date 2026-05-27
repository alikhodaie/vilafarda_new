<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('homes', 'is_host_active')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->boolean('is_host_active')->default(true)->after('is_draft');
            });
        }

        if (! Schema::hasColumn('homes', 'host_deactivation_reason')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->string('host_deactivation_reason', 50)->nullable()->after('is_host_active');
            });
        }
    }

    public function down(): void
    {
        $columns = array_filter([
            Schema::hasColumn('homes', 'is_host_active') ? 'is_host_active' : null,
            Schema::hasColumn('homes', 'host_deactivation_reason') ? 'host_deactivation_reason' : null,
        ]);

        if ($columns !== []) {
            Schema::table('homes', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
