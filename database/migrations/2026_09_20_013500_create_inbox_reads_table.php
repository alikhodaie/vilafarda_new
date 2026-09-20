<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbox_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('readable');
            $table->timestamps();

            $table->unique(['user_id', 'readable_type', 'readable_id'], 'inbox_reads_user_readable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbox_reads');
    }
};
