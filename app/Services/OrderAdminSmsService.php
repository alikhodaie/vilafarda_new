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
        $admins = User::getAdminsWithRotatingOrderSms()->values();

        if ($admins->isEmpty()) {
            return null;
        }

        if ($admins->count() === 1) {
            $next = $admins->first();
        } else {
            $lastId = (int) setting(self::LAST_ADMIN_SETTING_KEY, 0);
            $currentIndex = $admins->search(fn (User $admin) => $admin->id === $lastId);

            if ($currentIndex === false) {
                $next = $admins->first();
            } else {
                $next = $admins->get(($currentIndex + 1) % $admins->count());
            }
        }

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
                'name' => 'START-DATE',
                'value' => $order->persianDate('start_at', '%A d F Y'),
            ],
            [
                'name' => 'END-DATE',
                'value' => persianDate($order->end_at->copy()->addDay())->format('%A d F Y'),
            ],
            [
                'name' => 'AMOUNT',
                'value' => number_format($order->price),
            ],
            [
                'name' => 'GUEST-NAME',
                'value' => Str::limit($order->renter->full_name, 25, ''),
            ],
            [
                'name' => 'GUEST-MOBILE',
                'value' => $order->renter->mobile,
            ],
            [
                'name' => 'OWNER-NAME',
                'value' => Str::limit($order->owner->full_name, 25, ''),
            ],
            [
                'name' => 'OWNER-MOBILE',
                'value' => $order->owner->mobile,
            ],
        ];
    }

    public function buildGuestSmsParameters(Order $order, ?User $consultantAdmin): array
    {
        $parameters = [
            [
                'name' => 'HOME_NAME',
                'value' => Str::limit($order->home->name, 25, ''),
            ],
        ];

        if ($consultantAdmin) {
            $parameters[] = [
                'name' => 'consultant_name',
                'value' => Str::limit($consultantAdmin->full_name, 25, ''),
            ];
            $parameters[] = [
                'name' => 'consultant_mobile',
                'value' => $consultantAdmin->mobile,
            ];
        }

        return $parameters;
    }

    public function calendarEditUrl(Order $order): string
    {
        return url(route('admin.homes.date.show', $order->home_id, false));
    }
}
