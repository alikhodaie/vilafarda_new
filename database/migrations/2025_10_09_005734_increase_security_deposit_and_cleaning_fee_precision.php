<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IncreaseSecurityDepositAndCleaningFeePrecision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('homes', 'security_deposit') || ! Schema::hasColumn('homes', 'cleaning_fee')) {
            return;
        }

        if ($this->columnPrecisionIs('homes', 'security_deposit', 15)
            && $this->columnPrecisionIs('homes', 'cleaning_fee', 15)) {
            return;
        }

        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn(['security_deposit', 'cleaning_fee']);
        });

        Schema::table('homes', function (Blueprint $table) {
            $table->decimal('security_deposit', 15, 2)->nullable()->after('week_price');
            $table->decimal('cleaning_fee', 15, 2)->nullable()->after('security_deposit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('homes', 'security_deposit') || ! Schema::hasColumn('homes', 'cleaning_fee')) {
            return;
        }

        if ($this->columnPrecisionIs('homes', 'security_deposit', 10)
            && $this->columnPrecisionIs('homes', 'cleaning_fee', 10)) {
            return;
        }

        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn(['security_deposit', 'cleaning_fee']);
        });

        Schema::table('homes', function (Blueprint $table) {
            $table->decimal('security_deposit', 10, 2)->nullable()->after('week_price');
            $table->decimal('cleaning_fee', 10, 2)->nullable()->after('security_deposit');
        });
    }

    private function columnPrecisionIs(string $table, string $column, int $precision): bool
    {
        $row = DB::selectOne(
            'SELECT NUMERIC_PRECISION as p FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );

        return $row && (int) $row->p === $precision;
    }
}
