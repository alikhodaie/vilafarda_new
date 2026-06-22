<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->unsignedInteger('orders_accepted_count')->default(0)->after('score');
            $table->unsignedInteger('orders_rejected_count')->default(0)->after('orders_accepted_count');
            $table->string('order_response_tier', 20)->nullable()->after('orders_rejected_count');
            $table->string('guest_review_tier', 20)->nullable()->after('order_response_tier');
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn([
                'orders_accepted_count',
                'orders_rejected_count',
                'order_response_tier',
                'guest_review_tier',
            ]);
        });
    }
};
