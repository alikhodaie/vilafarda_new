<?php

namespace App\Services;

use App\Classes\SMS;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrderAdminSmsService
{
    public const MODE_ALWAYS = 'always';

    public const MODE_ROTATING = 'rotating';

    public const LAST_ROTATING_INDEX_KEY = 'order_sms:last_rotating_index';

    public function pickNextRotatingAdmin(): ?User
    {
        $admins = $this->getRotatingOnlyAdmins();

        if ($admins->isEmpty()) {
            return null;
        }

        $lastIndex = (int) setting(self::LAST_ROTATING_INDEX_KEY, -1);
        $nextIndex = ($lastIndex + 1) % $admins->count();

        Setting::query()->updateOrCreate(
            ['key' => self::LAST_ROTATING_INDEX_KEY],
            ['value' => (string) $nextIndex]
        );

        forgetSettingsCache();

        return $admins->get($nextIndex);
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
        return array_merge(
            $this->smsParam(['ID'], Str::limit($order->home->code, 25, '')),
            $this->smsParam(['COUNT'], $order->count_guest),
            $this->smsParam(['START-DATE', 'START_DATE'], $order->persianDate('start_at', '%A d F Y')),
            $this->smsParam(['END-DATE', 'END_DATE'], persianDate($order->end_at->copy()->addDay())->format('%A d F Y')),
            $this->smsParam(['AMOUNT'], number_format($order->price)),
            $this->smsParam(['GUEST-NAME', 'GUEST_NAME', 'guest_name'], Str::limit($order->renter->full_name, 25, '')),
            $this->smsParam(['GUEST-MOBILE', 'GUEST_MOBILE', 'guest_mobile'], $order->renter->mobile),
            $this->smsParam(['OWNER-NAME', 'OWNER_NAME', 'owner_name'], Str::limit($order->owner->full_name, 25, '')),
            $this->smsParam(['OWNER-MOBILE', 'OWNER_MOBILE', 'owner_mobile'], $order->owner->mobile),
            $this->smsParam(
                ['calendar_link', 'CALENDAR_LINK', 'CALENDAR-LINK'],
                Str::limit($this->calendarEditUrl($order), 200, '')
            ),
        );
    }

    public function buildGuestSmsParameters(Order $order, ?User $consultantAdmin): array
    {
        $parameters = $this->smsParam(
            ['HOME_NAME', 'HOME-NAME', 'home_name'],
            Str::limit($order->home->name, 25, '')
        );

        if ($consultantAdmin) {
            $parameters = array_merge(
                $parameters,
                $this->smsParam(
                    ['consultant_name', 'CONSULTANT_NAME', 'CONSULTANT-NAME'],
                    Str::limit($consultantAdmin->full_name, 25, '')
                ),
                $this->smsParam(
                    ['consultant_mobile', 'CONSULTANT_MOBILE', 'CONSULTANT-MOBILE'],
                    $consultantAdmin->mobile
                ),
            );
        }

        return $parameters;
    }

    public function calendarEditUrl(Order $order): string
    {
        return url(route('admin.homes.date.show', $order->home_id, false));
    }

    private function smsParam(array $names, mixed $value): array
    {
        $parameters = [];

        foreach (array_unique($names) as $name) {
            $parameters[] = [
                'name' => $name,
                'value' => $value,
            ];
        }

        return $parameters;
    }
}
