<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersHasDiscountsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users_has_discounts')) {
            return;
        }

        Schema::create('users_has_discounts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discount_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_used')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_has_discounts');
    }
}
