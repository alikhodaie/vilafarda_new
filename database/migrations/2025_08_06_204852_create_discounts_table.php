<?php

use App\Models\Discount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('discounts')) {
            return;
        }

        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('type', collect(Discount::TYPES)->pluck('value')->toArray());
            $table->string('user_type');
            $table->string('amount');
            $table->integer('count_users')->default(0);
            $table->integer('count_usage')->default(0);
            $table->string('sms_data', 1000)->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
