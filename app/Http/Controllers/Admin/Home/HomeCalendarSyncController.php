<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\UpdateHomeCalendarSourcesRequest;
use App\Models\Home;
use App\Models\HomeCalendarSource;
use App\Services\ExternalCalendar\ExternalCalendarSyncService;
use App\Support\ExternalCalendarSyncCooldown;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeCalendarSyncController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('indexCalendarSync', Home::class);

        $homes = Home::query()
            ->with(['user', 'calendarSource'])
            ->where('is_draft', false)
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('homes.id', $request->get('id'));
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $name = '%'.(string) $request->input('name').'%';

                $query->where(function ($builder) use ($name) {
                    $builder->where('homes.name', 'like', $name)
                        ->orWhere('homes.code', 'like', $name);
                });
            })
            ->when($request->filled('code'), function ($query) use ($request) {
                $query->where('homes.code', 'like', '%'.(string) $request->input('code').'%');
            })
            ->when($request->filled('user'), function ($query) use ($request) {
                $query->where('homes.user_id', $request->get('user'));
            })
            ->when($request->filled('has_external_link'), function ($query) use ($request) {
                if ($request->get('has_external_link') === 'yes') {
                    $query->whereHas('calendarSource', function ($builder) {
                        $builder->whereNotNull('external_url')->where('external_url', '!=', '');
                    });
                }

                if ($request->get('has_external_link') === 'no') {
                    $query->where(function ($builder) {
                        $builder->doesntHave('calendarSource')
                            ->orWhereHas('calendarSource', function ($sourceQuery) {
                                $sourceQuery->whereNull('external_url')->orWhere('external_url', '');
                            });
                    });
                }
            })
            ->when($request->filled('sync_enabled'), function ($query) use ($request) {
                $enabled = $request->get('sync_enabled') === 'yes';

                $query->whereHas('calendarSource', function ($builder) use ($enabled) {
                    $builder->where('sync_enabled', $enabled);
                });
            })
            ->orderByRaw("
                CASE WHEN EXISTS (
                    SELECT 1
                    FROM home_calendar_sources hcs
                    WHERE hcs.home_id = homes.id
                      AND hcs.external_url IS NOT NULL
                      AND hcs.external_url <> ''
                ) THEN 0 ELSE 1 END ASC
            ")
            ->orderByRaw("
                COALESCE((
                    SELECT hcs.last_synced_at
                    FROM home_calendar_sources hcs
                    WHERE hcs.home_id = homes.id
                      AND hcs.external_url IS NOT NULL
                      AND hcs.external_url <> ''
                    LIMIT 1
                ), CASE WHEN EXISTS (
                    SELECT 1
                    FROM home_calendar_sources hcs2
                    WHERE hcs2.home_id = homes.id
                      AND hcs2.external_url IS NOT NULL
                      AND hcs2.external_url <> ''
                ) THEN '1970-01-01 00:00:00' ELSE '9999-12-31 23:59:59' END) ASC
            ")
            ->orderByDesc('homes.id')
            ->paginate(20)
            ->appends($request->all());

        $syncCooldownSeconds = ExternalCalendarSyncCooldown::remainingSeconds();
        $syncCooldownTotal = ExternalCalendarSyncCooldown::SECONDS;

        return view('admin.homes.calendar-sync.index', compact(
            'homes',
            'syncCooldownSeconds',
            'syncCooldownTotal'
        ));
    }

    public function update(UpdateHomeCalendarSourcesRequest $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->input('sources', []) as $homeId => $data) {
                $home = Home::query()
                    ->where('is_draft', false)
                    ->whereKey($homeId)
                    ->first();

                if (! $home) {
                    continue;
                }

                $externalUrl = trim((string) ($data['external_url'] ?? ''));
                $syncEnabled = ! empty($data['sync_enabled']);

                if ($externalUrl === '') {
                    if ($home->calendarSource) {
                        $home->calendarSource->delete();
                    }
                    continue;
                }

                $source = $home->calendarSource ?: new HomeCalendarSource(['home_id' => $home->id]);
                $source->applyExternalUrl($externalUrl);

                if (! empty($data['platform'])) {
                    $source->platform = $data['platform'];
                    $source->external_room_id = \App\Support\ExternalCalendarPlatform::extractRoomId(
                        $source->platform,
                        $externalUrl
                    );
                }

                $source->sync_enabled = $syncEnabled;
                $source->save();
            }

            DB::commit();

            return redirect()
                ->to(route('admin.homes.calendar-sync.index', $request->query()))
                ->with('success', 'تنظیمات به‌روزرسانی تقویم ذخیره شد.');
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->withInput()->with('danger', __('text.whoops'));
        }
    }

    public function sync(Home $home, ExternalCalendarSyncService $syncService)
    {
        $this->authorize('syncCalendar', $home);

        if ($redirect = $this->cooldownRedirectIfNeeded()) {
            return $redirect;
        }

        return $this->runSync($home, $syncService, true);
    }

    public function syncAll(Request $request, ExternalCalendarSyncService $syncService)
    {
        $this->authorize('syncAllCalendar', Home::class);

        if ($redirect = $this->cooldownRedirectIfNeeded()) {
            return $redirect;
        }

        $homes = Home::query()
            ->with('calendarSource')
            ->where('is_draft', false)
            ->whereHas('calendarSource', function ($query) {
                $query->whereNotNull('external_url')->where('external_url', '!=', '');
            })
            ->orderByRaw("
                COALESCE((
                    SELECT hcs.last_synced_at
                    FROM home_calendar_sources hcs
                    WHERE hcs.home_id = homes.id
                    LIMIT 1
                ), '1970-01-01 00:00:00') ASC
            ")
            ->orderByDesc('id')
            ->limit(1)
            ->get();

        $home = $homes->first();

        if (! $home) {
            return redirect()
                ->back()
                ->with('danger', 'اقامتگاهی با لینک خارجی برای همگام‌سازی پیدا نشد.');
        }

        $result = $this->runSync($home, $syncService, true, false, true);

        if ($result['success']) {
            return redirect()
                ->back()
                ->with('success', 'همگام‌سازی «'.($home->code ?: '#'.$home->id).'» انجام شد. برای مورد بعدی '.$this->cooldownLabel().' صبر کنید.');
        }

        return redirect()
            ->back()
            ->with('danger', ($home->code ?: '#'.$home->id).' — '.$result['message']);
    }

    private function cooldownLabel(): string
    {
        return ExternalCalendarSyncCooldown::SECONDS.' ثانیه';
    }

    private function cooldownRedirectIfNeeded()
    {
        $remaining = ExternalCalendarSyncCooldown::remainingSeconds();

        if ($remaining <= 0) {
            return null;
        }

        return redirect()
            ->back()
            ->with('danger', 'برای جلوگیری از مسدود شدن IP، '.$remaining.' ثانیه دیگر صبر کنید و دوباره همگام‌سازی کنید.');
    }

    private function runSync(
        Home $home,
        ExternalCalendarSyncService $syncService,
        bool $manual = false,
        bool $redirect = true,
        bool $markCooldown = true
    ) {
        try {
            $source = $syncService->sync($home, $manual);

            if ($markCooldown) {
                ExternalCalendarSyncCooldown::mark();
            }

            if (! $redirect) {
                return [
                    'success' => true,
                    'message' => $source->last_sync_message ?: 'همگام‌سازی با موفقیت انجام شد.',
                ];
            }

            return redirect()
                ->back()
                ->with('success', $source->last_sync_message ?: 'همگام‌سازی با موفقیت انجام شد.');
        } catch (Exception $e) {
            if ($markCooldown) {
                ExternalCalendarSyncCooldown::mark();
            }

            if ($source = $home->calendarSource) {
                $source->update([
                    'last_synced_at' => now(),
                    'last_sync_status' => HomeCalendarSource::STATUS_FAILED,
                    'last_sync_message' => $e->getMessage(),
                ]);
            }

            if (! $redirect) {
                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }

            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
