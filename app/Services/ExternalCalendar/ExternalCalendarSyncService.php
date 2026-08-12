<?php

namespace App\Services\ExternalCalendar;

use App\Models\Home;
use App\Models\HomeCalendarSource;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExternalCalendarSyncService
{
    public function __construct(
        private JajigaCalendarFetcher $jajigaFetcher,
        private JabamaCalendarFetcher $jabamaFetcher
    ) {}

    public function sync(Home $home, bool $manual = false): HomeCalendarSource
    {
        $source = $home->calendarSource;

        if (! $source || blank($source->external_url)) {
            throw new \RuntimeException('لینک خارجی برای این اقامتگاه ثبت نشده است.');
        }

        if (! $manual && ! $source->sync_enabled) {
            throw new \RuntimeException('همگام‌سازی خودکار برای این اقامتگاه غیرفعال است.');
        }

        if (! $source->external_room_id) {
            throw new \RuntimeException('شناسه اقامتگاه از روی لینک خارجی قابل تشخیص نیست.');
        }

        $blockedDates = $this->fetchUnavailableDates($source->platform, $source->external_room_id);
        $previousBlocked = collect($source->last_blocked_dates ?? [])->filter()->values()->all();

        DB::transaction(function () use ($home, $source, $blockedDates, $previousBlocked) {
            $home->unsetRelation('custom_dates');

            $toUnblock = array_diff($previousBlocked, $blockedDates);

            foreach ($toUnblock as $date) {
                if ($this->hasActiveOrderOnDate($home, $date)) {
                    continue;
                }

                $home->custom_dates()
                    ->where('price', 0)
                    ->whereDate('date', Carbon::parse($date)->toDateString())
                    ->delete();
            }

            foreach ($blockedDates as $date) {
                if ($this->hasActiveOrderOnDate($home, $date)) {
                    continue;
                }

                $home->upsertCustomDate($date, 0);
            }

            $source->update([
                'last_synced_at' => now(),
                'last_sync_status' => HomeCalendarSource::STATUS_SUCCESS,
                'last_sync_message' => 'تعداد '.count($blockedDates).' روز بسته همگام‌سازی شد.',
                'last_blocked_dates' => $blockedDates,
            ]);
        });

        return $source->fresh();
    }

    private function fetchUnavailableDates(?string $platform, string $roomId): array
    {
        if (JajigaCalendarFetcher::supports($platform)) {
            return $this->jajigaFetcher->fetchUnavailableDates($roomId);
        }

        if (JabamaCalendarFetcher::supports($platform)) {
            return $this->jabamaFetcher->fetchUnavailableDates($roomId);
        }

        $label = $platform
            ? \App\Support\ExternalCalendarPlatform::label($platform)
            : 'نامشخص';

        throw new \RuntimeException('همگام‌سازی برای پلتفرم «'.$label.'» هنوز پیاده‌سازی نشده است.');
    }

    private function hasActiveOrderOnDate(Home $home, string $date): bool
    {
        $day = Carbon::parse($date)->startOfDay();

        return $home->orders()
            ->whereIn('status', [Order::AWAITING_PAYMENT, Order::WAITING_FOR_RENTER, Order::IN_RENT])
            ->where('start_at', '<=', $day)
            ->where('end_at', '>=', $day)
            ->exists();
    }
}
