<?php

namespace App\Console\Commands;

use App\Models\Home;
use App\Services\HomeProfileStatsService;
use Illuminate\Console\Command;

class RecalculateHomePerformanceStats extends Command
{
    protected $signature = 'homes:recalculate-performance-stats {--home= : شناسه یک اقامتگاه خاص}';

    protected $description = 'محاسبه و ذخیره آمار عملکرد هر اقامتگاه در دیتابیس';

    public function handle(HomeProfileStatsService $homeProfileStatsService): int
    {
        $homeId = $this->option('home');

        if ($homeId) {
            $home = Home::query()->findOrFail($homeId);
            $homeProfileStatsService->recalculateForHome($home);
            $this->info("آمار اقامتگاه {$homeId} به‌روزرسانی شد.");

            return 0;
        }

        $updated = 0;

        Home::query()
            ->where('is_draft', false)
            ->orderBy('id')
            ->chunkById(100, function ($homes) use ($homeProfileStatsService, &$updated) {
                foreach ($homes as $home) {
                    $homeProfileStatsService->recalculateForHome($home);
                    $updated++;
                }
            });

        $this->info("{$updated} اقامتگاه به‌روزرسانی شد.");

        return 0;
    }
}
