<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\UpdateHomeCalendarSourcesRequest;
use App\Models\Home;
use App\Models\HomeCalendarSource;
use App\Services\ExternalCalendar\ExternalCalendarSyncService;
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
            ->search()
            ->when($request->filled('code'), function ($query) use ($request) {
                $query->where('code', 'like', '%'.$request->string('code').'%');
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
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->all());

        return view('admin.homes.calendar-sync.index', compact('homes'));
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
                    $home->calendarSource()?->delete();
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

        return $this->runSync($home, $syncService, manual: true);
    }

    public function syncAll(Request $request, ExternalCalendarSyncService $syncService)
    {
        $this->authorize('syncAllCalendar', Home::class);

        $homes = Home::query()
            ->with('calendarSource')
            ->where('is_draft', false)
            ->whereHas('calendarSource', function ($query) {
                $query->whereNotNull('external_url')->where('external_url', '!=', '');
            })
            ->orderByDesc('id')
            ->get();

        $successCount = 0;
        $failedCount = 0;
        $messages = [];

        foreach ($homes as $home) {
            $result = $this->runSync($home, $syncService, manual: true, redirect: false);

            if ($result['success']) {
                $successCount++;
                continue;
            }

            $failedCount++;
            $messages[] = ($home->code ?: '#'.$home->id).' — '.$result['message'];
        }

        if ($failedCount === 0) {
            return redirect()
                ->back()
                ->with('success', 'همگام‌سازی دستی '.$successCount.' اقامتگاه با موفقیت انجام شد.');
        }

        $summary = 'موفق: '.$successCount.' | ناموفق: '.$failedCount;

        return redirect()
            ->back()
            ->with('warning', $summary."\n".implode("\n", array_slice($messages, 0, 5)).($failedCount > 5 ? "\n..." : ''));
    }

    private function runSync(Home $home, ExternalCalendarSyncService $syncService, bool $manual = false, bool $redirect = true)
    {
        try {
            $source = $syncService->sync($home, $manual);

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
