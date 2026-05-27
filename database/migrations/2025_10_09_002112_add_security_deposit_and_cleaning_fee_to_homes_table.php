<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecurityDepositAndCleaningFeeToHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('homes', 'security_deposit')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->decimal('security_deposit', 10, 2)->nullable()->after('week_price');
            });
        }

        if (! Schema::hasColumn('homes', 'cleaning_fee')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->decimal('cleaning_fee', 10, 2)->nullable()->after('security_deposit');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columns = array_filter([
            Schema::hasColumn('homes', 'security_deposit') ? 'security_deposit' : null,
            Schema::hasColumn('homes', 'cleaning_fee') ? 'cleaning_fee' : null,
        ]);

        if ($columns !== []) {
            Schema::table('homes', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
