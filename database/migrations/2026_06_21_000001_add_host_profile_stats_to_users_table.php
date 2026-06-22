<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('homes_accepted_count')->default(0)->after('count_canceled_orders');
            $table->unsignedInteger('homes_rejected_count')->default(0)->after('homes_accepted_count');
            $table->unsignedInteger('homes_pending_count')->default(0)->after('homes_rejected_count');
            $table->unsignedInteger('guest_reviews_count')->default(0)->after('homes_pending_count');
            $table->decimal('guest_reviews_score', 4, 1)->default(0)->after('guest_reviews_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'homes_accepted_count',
                'homes_rejected_count',
                'homes_pending_count',
                'guest_reviews_count',
                'guest_reviews_score',
            ]);
        });
    }
};
