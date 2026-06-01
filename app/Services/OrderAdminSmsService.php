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
        $names = config('sms.parameter_names.order_created_admin', []);
        $values = [
            'id' => Str::limit($order->home->code, $this->parameterMaxLength(), ''),
            'count' => (string) $order->count_guest,
            'start_date' => $this->parameterValue($order->persianDate('start_at', '%A d F Y')),
            'end_date' => $this->parameterValue(persianDate($order->end_at->copy()->addDay())->format('%A d F Y')),
            'amount' => $this->parameterValue(number_format($order->price)),
            'guest_name' => $this->parameterValue($order->renter->full_name),
            'guest_mobile' => $this->parameterValue($order->renter->mobile),
            'owner_name' => $this->parameterValue($order->owner->full_name),
            'owner_mobile' => $this->parameterValue($order->owner->mobile),
            'calendar_link' => $this->parameterValue($this->shortCalendarLink($order)),
        ];

        return $this->buildParametersFromMap($names, $values);
    }

    public function buildGuestSmsParameters(Order $order, ?User $consultantAdmin): array
    {
        $names = config('sms.parameter_names.order_created_renter', []);
        $values = [
            'home_name' => $this->parameterValue($order->home->name),
        ];

        if ($consultantAdmin) {
            $values['consultant_name'] = $this->parameterValue($consultantAdmin->full_name);
            $values['consultant_mobile'] = $this->parameterValue($consultantAdmin->mobile);
        }

        return $this->buildParametersFromMap($names, $values);
    }

    public function shortCalendarLink(Order $order): string
    {
        return route('admin.homes.date.show', $order->home_id, false);
    }

    private function buildParametersFromMap(array $names, array $values): array
    {
        $parameters = [];

        foreach ($names as $valueKey => $smsName) {
            if (! array_key_exists($valueKey, $values) || $values[$valueKey] === null || $values[$valueKey] === '') {
                continue;
            }

            $parameters[] = [
                'name' => $smsName,
                'value' => $values[$valueKey],
            ];
        }

        return $parameters;
    }

    private function parameterValue(?string $value): string
    {
        return Str::limit(trim((string) $value), $this->parameterMaxLength(), '');
    }

    private function parameterMaxLength(): int
    {
        $max = (int) config('sms.parameter_max_length', 25);

        return $max > 0 ? $max : 25;
    }
}
