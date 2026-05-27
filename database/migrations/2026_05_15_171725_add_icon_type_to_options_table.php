<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconTypeToOptionsTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('options', 'icon_type')) {
            return;
        }

        Schema::table('options', function (Blueprint $table) {
            $table->string('icon_type', 10)->default('image')->after('icon');
        });
    }

    public function down()
    {
        if (! Schema::hasColumn('options', 'icon_type')) {
            return;
        }

        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('icon_type');
        });
    }
}
