<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'discount_id')) {
                $table->foreignId('discount_id')->after('home_id')->nullable()->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('orders', 'discount')) {
                $table->integer('discount')->after('extra_guest')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'discount_id')) {
                $table->dropForeign(['discount_id']);
                $table->dropColumn('discount_id');
            }
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }
}
