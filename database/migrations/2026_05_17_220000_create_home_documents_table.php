<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('home_documents')) {
            Schema::create('home_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('home_id')->constrained('homes')->cascadeOnDelete();
                $table->string('name', 80);
                $table->string('original_name', 255)->nullable();
                $table->unsignedInteger('size')->default(0);
                $table->string('mime', 100)->nullable();
                $table->timestamps();

                $table->unique(['home_id', 'name']);
            });
        }

        if (! Schema::hasColumn('homes', 'document')) {
            return;
        }

        $homes = DB::table('homes')
            ->whereNotNull('document')
            ->where('document', '!=', '')
            ->get(['id', 'document']);

        foreach ($homes as $home) {
            $exists = DB::table('home_documents')
                ->where('home_id', $home->id)
                ->where('name', $home->document)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('home_documents')->insert([
                'home_id' => $home->id,
                'name' => $home->document,
                'original_name' => $home->document,
                'size' => 0,
                'mime' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('home_documents');
    }
};
