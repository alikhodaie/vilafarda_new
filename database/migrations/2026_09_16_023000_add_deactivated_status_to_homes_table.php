<?php

use App\Models\Home;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->modifyStatusEnum(array_keys(Home::STATUSES));
    }

    public function down(): void
    {
        if (Schema::hasTable('homes') && Schema::hasColumn('homes', 'status')) {
            Home::query()
                ->where('status', Home::DEACTIVATED)
                ->update(['status' => Home::REJECTED]);
        }

        $this->modifyStatusEnum([
            Home::PENDING,
            Home::ACCEPTED,
            Home::REJECTED,
        ]);
    }

    /**
     * @param  array<int, string>  $statuses
     */
    private function modifyStatusEnum(array $statuses): void
    {
        if (! Schema::hasTable('homes') || ! Schema::hasColumn('homes', 'status')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if (! in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        $quoted = implode("','", array_map(
            static fn (string $status): string => str_replace("'", "''", $status),
            $statuses
        ));

        DB::statement("ALTER TABLE homes MODIFY COLUMN status ENUM('{$quoted}') NOT NULL DEFAULT '".Home::PENDING."'");
    }
};
