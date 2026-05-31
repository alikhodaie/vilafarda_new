<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrderAdminSmsService
{
    public const MODE_ALWAYS = 'always';

    public const MODE_ROTATING = 'rotating';

    public const LAST_ADMIN_SETTING_KEY = 'order_sms:last_admin_id';

    public function pickNextRotatingAdmin(): ?User
    {
        $admins = User::getAdminsWithRotatingOrderSms();

        if ($admins->isEmpty()) {
            return null;
        }

        $lastId = (int) setting(self::LAST_ADMIN_SETTING_KEY, 0);
        $next = $admins->first(fn (User $admin) => $admin->id > $lastId) ?? $admins->first();

        Setting::query()->updateOrCreate(
            ['key' => self::LAST_ADMIN_SETTING_KEY],
            ['value' => (string) $next->id]
        );

        forgetSettingsCache();

        return $next;
    }

    public function getAlwaysAdmins(): Collection
    {
        return User::getAdminsWithAlwaysOrderSms();
    }

    public function buildAdminSmsParameters(Order $order): array
    {
        return [
            [
                'name' => 'ID',
                'value' => Str::limit($order->home->code, 25, ''),
            ],
            [
                'name' => 'COUNT',
                'value' => $order->count_guest,
            ],
            [
                'name' => 'START_DATE',
                'value' => $order->persianDate('start_at', '%A d F Y'),
            ],
            [
                'name' => 'END_DATE',
                'value' => persianDate($order->end_at->copy()->addDay())->format('%A d F Y'),
            ],
            [
                'name' => 'AMOUNT',
                'value' => number_format($order->price),
            ],
            [
                'name' => 'GUEST_NAME',
                'value' => Str::limit($order->renter->full_name, 25, ''),
            ],
            [
                'name' => 'GUEST_MOBILE',
                'value' => $order->renter->mobile,
            ],
            [
                'name' => 'OWNER_NAME',
                'value' => Str::limit($order->owner->full_name, 25, ''),
            ],
            [
                'name' => 'OWNER_MOBILE',
                'value' => $order->owner->mobile,
            ],
            [
                'name' => 'CALENDAR_LINK',
                'value' => $this->calendarEditUrl($order),
            ],
        ];
    }

    public function buildGuestSmsParameters(Order $order, ?User $rotatingAdmin): array
    {
        $parameters = [
            [
                'name' => 'HOME_NAME',
                'value' => Str::limit($order->home->name, 25, ''),
            ],
        ];

        if ($rotatingAdmin) {
            $parameters[] = [
                'name' => 'CONSULTANT_NAME',
                'value' => Str::limit($rotatingAdmin->full_name, 25, ''),
            ];
            $parameters[] = [
                'name' => 'CONSULTANT_MOBILE',
                'value' => $rotatingAdmin->mobile,
            ];
        }

        return $parameters;
    }

    public function calendarEditUrl(Order $order): string
    {
        return url(route('admin.homes.date.show', $order->home_id, false));
    }
}
