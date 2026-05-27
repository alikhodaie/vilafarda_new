<?php

use App\Models\Home;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('cover')->nullable();
            $table->string('video')->nullable();
            $table->enum('status', array_keys(Home::STATUSES))->default(Home::PENDING);
            $table->string('reject_policy')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->string('address', 500)->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->string('description', 1500)->nullable();
            $table->string('sleep_area_description', 500)->nullable();
            $table->string('more_health')->nullable();
            $table->string('more_safety')->nullable();
            $table->string('rules', 500)->nullable();
            $table->integer('yard_meter')->default(0);
            $table->integer('infrastructure_meter')->default(0);
            $table->integer('main_guest')->default(0);
            $table->integer('extra_guest')->default(0);
            $table->integer('week_price')->default(0);
            $table->integer('wed_price')->default(0);
            $table->integer('thu_price')->default(0);
            $table->integer('fri_price')->default(0);
            $table->tinyInteger('off')->default(0);
            $table->tinyInteger('daily_off')->default(0);
            $table->tinyInteger('daily_off_amount')->default(0);
            $table->integer('price_per_surplus')->default(0);
            $table->string('atmosphere')->nullable();
            $table->string('type')->nullable();
            $table->string('area')->nullable();
            $table->integer('count_comments')->default(0);
            $table->tinyInteger('score')->default(0)->index();
            $table->tinyInteger('fake_score')->default(3)->index();
            $table->boolean('is_draft')->default(true);
            $table->timestamp('fast_reserve_start_at')->nullable();
            $table->timestamp('fast_reserve_end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homes');
    }
}
