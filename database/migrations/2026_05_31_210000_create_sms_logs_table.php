<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('mobile', 20);
            $table->string('recipient_name')->nullable();
            $table->string('pattern_id', 64);
            $table->string('pattern_title')->nullable();
            $table->json('parameters')->nullable();
            $table->string('status', 20);
            $table->text('response_body')->nullable();
            $table->string('error_message')->nullable();
            $table->string('source')->nullable();
            $table->nullableMorphs('related');
            $table->timestamps();

            $table->index('mobile');
            $table->index('status');
            $table->index('pattern_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
