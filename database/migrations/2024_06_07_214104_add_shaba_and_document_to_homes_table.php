<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShabaAndDocumentToHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('homes', 'shaba')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->string('shaba', 100)->nullable()->after('is_draft');
            });
        }

        if (! Schema::hasColumn('homes', 'document')) {
            Schema::table('homes', function (Blueprint $table) {
                $table->string('document', 50)->nullable()->after('is_draft');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columns = array_filter([
            Schema::hasColumn('homes', 'shaba') ? 'shaba' : null,
            Schema::hasColumn('homes', 'document') ? 'document' : null,
        ]);

        if ($columns !== []) {
            Schema::table('homes', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
