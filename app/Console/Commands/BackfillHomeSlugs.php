<?php

namespace App\Console\Commands;

use App\Models\Home;
use App\Support\HomeSlug;
use Illuminate\Console\Command;

class BackfillHomeSlugs extends Command
{
    protected $signature = 'homes:backfill-slugs {--force : بازنویسی اسلاگ‌های موجود}';

    protected $description = 'ساخت یا به‌روزرسانی اسلاگ سئو برای اقامتگاه‌های بدون اسلاگ';

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $updated = 0;

        Home::query()
            ->with(['city', 'province'])
            ->orderBy('id')
            ->chunkById(100, function ($homes) use ($force, &$updated) {
                foreach ($homes as $home) {
                    if (! $force && filled($home->slug)) {
                        continue;
                    }

                    $home->update(['slug' => HomeSlug::suggestFor($home)]);
                    $updated++;
                }
            });

        $this->info("{$updated} اقامتگاه به‌روزرسانی شد.");

        return 0;
    }
}
