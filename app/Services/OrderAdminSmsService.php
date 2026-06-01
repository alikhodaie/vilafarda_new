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
