<?php

namespace App\Services;

use App\Classes\SMS;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderAdminSmsService
{
    private const DEFAULT_ADMIN_PARAM_NAMES = [
        'id' => 'ID',
        'count' => 'COUNT',
        'start_date' => 'START_DATE',
        'end_date' => 'END_DATE',
        'amount' => 'AMOUNT',
        'guest_name' => 'GUEST_NAME',
        'guest_mobile' => 'GUEST_MOBILE',
        'owner_name' => 'OWNER_NAME',
        'owner_mobile' => 'OWNER_MOBILE',
        'calendar_link' => 'CALENDAR_LINK',
    ];

    private const DEFAULT_GUEST_PARAM_NAMES = [
        'home_name' => 'HOME_NAME',
        'consultant_name' => 'CONSULTANT_NAME',
        'consultant_mobile' => 'CONSULTANT_MOBILE',
    ];

    public const MODE_ALWAYS = 'always';

    public const MODE_ROTATING = 'rotating';

    public const LAST_ROTATING_INDEX_KEY = 'order_sms:last_rotating_index';

    public const LAST_ROTATING_COUNT_KEY = 'order_sms:last_rotating_count';

    public function pickNextRotatingAdmin(): ?User
    {
        $admins = $this->getRotatingOnlyAdmins();

        if ($admins->isEmpty()) {
            return null;
        }

        if ($admins->count() === 1) {
            return $admins->first();
        }

        return DB::transaction(function () use ($admins) {
            $setting = Setting::query()
                ->where('key', self::LAST_ROTATING_INDEX_KEY)
                ->lockForUpdate()
                ->first();

            $countSetting = Setting::query()
                ->where('key', self::LAST_ROTATING_COUNT_KEY)
                ->lockForUpdate()
                ->first();

            $lastIndex = $setting ? (int) $setting->value : -1;
            $storedCount = $countSetting ? (int) $countSetting->value : 0;

            if ($storedCount !== $admins->count() || $lastIndex >= $admins->count()) {
                $lastIndex = -1;
            }

            $nextIndex = ($lastIndex + 1) % $admins->count();

            Setting::query()->updateOrCreate(
                ['key' => self::LAST_ROTATING_INDEX_KEY],
                ['value' => (string) $nextIndex]
            );

            Setting::query()->updateOrCreate(
                ['key' => self::LAST_ROTATING_COUNT_KEY],
                ['value' => (string) $admins->count()]
            );

            forgetSettingsCache();

            return $admins->get($nextIndex);
        });
    }

    public function getRotatingOnlyAdmins(): Collection
    {
        $alwaysIds = $this->getAlwaysAdmins()->pluck('id');

        return User::getAdminsWithRotatingOrderSms()
            ->reject(fn (User $admin) => $alwaysIds->contains($admin->id))
            ->unique('id')
            ->sortBy('id')
            ->values();
    }

    public function getAlwaysAdmins(): Collection
    {
        return User::getAdminsWithAlwaysOrderSms()->unique('id')->values();
    }

    public function sendAdminOrderSms(Order $order, ?User $rotatingAdmin): void
    {
        $parameters = $this->buildAdminSmsParameters($order);
        $pattern = config('sms.patterns.order_created_admin');
        $sentMobiles = [];

        foreach ($this->getAlwaysAdmins() as $admin) {
            if (in_array($admin->mobile, $sentMobiles, true)) {
                continue;
            }

            SMS::sendPattern($admin->mobile, $pattern, $parameters, [
                'user_id' => $admin->id,
                'related' => $order,
                'source' => 'OrderObserver::created',
            ]);
            $sentMobiles[] = $admin->mobile;
        }

        if ($rotatingAdmin && ! in_array($rotatingAdmin->mobile, $sentMobiles, true)) {
            SMS::sendPattern($rotatingAdmin->mobile, $pattern, $parameters, [
                'user_id' => $rotatingAdmin->id,
                'related' => $order,
                'source' => 'OrderObserver::created',
            ]);
        }
    }

    public function buildAdminSmsParameters(Order $order): array
    {
        $limit = $this->parameterMaxLength();

        return [
            [
                'name' => $this->adminSmsParamName('id'),
                'value' => $this->adminParameterValue('id', Str::limit($order->home->code, $limit, '')),
            ],
            [
                'name' => $this->adminSmsParamName('count'),
                'value' => $this->adminParameterValue('count', (string) $order->count_guest),
            ],
            [
                'name' => $this->adminSmsParamName('start_date'),
                'value' => $this->adminParameterValue('start_date', $order->persianDate('start_at', 'Y/m/d')),
            ],
            [
                'name' => $this->adminSmsParamName('end_date'),
                'value' => $this->adminParameterValue('end_date', persianDate($order->end_at->copy()->addDay())->format('Y/m/d')),
            ],
            [
                'name' => $this->adminSmsParamName('amount'),
                'value' => $this->adminParameterValue('amount', number_format($order->price)),
            ],
            [
                'name' => $this->adminSmsParamName('guest_name'),
                'value' => $this->adminParameterValue('guest_name', Str::limit($order->renter->full_name, $limit, '')),
            ],
            [
                'name' => $this->adminSmsParamName('guest_mobile'),
                'value' => $this->adminParameterValue('guest_mobile', $order->renter->mobile),
            ],
            [
                'name' => $this->adminSmsParamName('owner_name'),
                'value' => $this->adminParameterValue('owner_name', Str::limit($order->owner->full_name, $limit, '')),
            ],
            [
                'name' => $this->adminSmsParamName('owner_mobile'),
                'value' => $this->adminParameterValue('owner_mobile', $order->owner->mobile),
            ],
            [
                'name' => $this->adminSmsParamName('calendar_link'),
                'value' => $this->adminParameterValue('calendar_link', $this->calendarLink($order)),
            ],
        ];
    }

    public function buildGuestSmsParameters(Order $order, ?User $consultantAdmin): array
    {
        $limit = $this->parameterMaxLength();
        $parameters = [
            [
                'name' => $this->guestSmsParamName('home_name'),
                'value' => Str::limit($order->home->name, $limit, ''),
            ],
        ];

        if ($consultantAdmin) {
            $parameters[] = [
                'name' => $this->guestSmsParamName('consultant_name'),
                'value' => Str::limit($consultantAdmin->full_name, $limit, ''),
            ];
            $parameters[] = [
                'name' => $this->guestSmsParamName('consultant_mobile'),
                'value' => $consultantAdmin->mobile,
            ];
        }

        return $parameters;
    }

    /**
     * مسیر تقویم بدون دامنه (حداکثر ۲۵ کاراکتر در پترن).
     * در متن قالب IPPanel حتماً قبل از متغیر بنویسید: https://vilafarda.ir/
     * نتیجه: https://vilafarda.ir/admin/homes/117/date
     */
    public function calendarLink(Order $order): string
    {
        return ltrim(route('admin.homes.date.show', $order->home_id, false), '/');
    }

    private function adminSmsParamName(string $key): string
    {
        return $this->resolveSmsParamName('order_created_admin', self::DEFAULT_ADMIN_PARAM_NAMES, $key);
    }

    private function guestSmsParamName(string $key): string
    {
        return $this->resolveSmsParamName('order_created_renter', self::DEFAULT_GUEST_PARAM_NAMES, $key);
    }

    private function resolveSmsParamName(string $templateKey, array $defaults, string $key): string
    {
        $configured = config("sms.parameter_names.{$templateKey}");

        if (is_array($configured) && $configured !== [] && isset($configured[$key])) {
            return (string) $configured[$key];
        }

        return $defaults[$key] ?? $key;
    }

    private function adminParameterValue(string $key, string $value): string
    {
        $value = trim($value);
        $max = $this->adminParameterMaxLength($key);

        if ($max <= 0) {
            return $value;
        }

        return Str::limit($value, $max, '');
    }

    private function adminParameterMaxLength(string $key): int
    {
        return $this->parameterMaxLength();
    }

    private function parameterMaxLength(): int
    {
        $max = (int) config('sms.parameter_max_length', 25);

        return $max > 0 ? $max : 25;
    }
}
