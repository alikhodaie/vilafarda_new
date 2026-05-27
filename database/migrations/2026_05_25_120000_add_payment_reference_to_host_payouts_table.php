<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentReferenceToHostPayoutsTable extends Migration
{
    public function up()
    {
        Schema::table('host_payouts', function (Blueprint $table) {
            $table->string('payment_reference', 100)->nullable()->after('paid_at');
        });
    }

    public function down()
    {
        Schema::table('host_payouts', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
        });
    }
}
