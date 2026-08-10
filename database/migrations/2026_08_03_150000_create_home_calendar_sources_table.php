<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_calendar_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('platform', 30)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->string('external_room_id', 50)->nullable();
            $table->boolean('sync_enabled')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->string('last_sync_status', 20)->nullable();
            $table->text('last_sync_message')->nullable();
            $table->json('last_blocked_dates')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_calendar_sources');
    }
};
