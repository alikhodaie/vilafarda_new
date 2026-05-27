<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('homes', 'code')) {
            return;
        }

        Schema::table('homes', function (Blueprint $table) {
            $table->string('code', 50)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('homes', 'code')) {
            return;
        }

        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
