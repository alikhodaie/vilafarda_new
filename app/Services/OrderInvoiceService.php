<?php

namespace App\Services;

use App\Models\Home;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class OrderInvoiceService
{
    public function breakdown(Order $order): array
    {
        $order->loadMissing(['home.custom_dates', 'discountModel']);
        $home = $order->home;

        $period = CarbonPeriod::between($order->start_at, $order->end_at);
        $nights = max(1, $order->count_days);

        $nightLines = [];
        $nightsSubtotal = 0;

        foreach ($period as $date) {
            $day = $date->copy()->startOfDay();
            $amount = $home->getPrice($day);
            $original = $home->getPrice($day, true);
            $nightsSubtotal += $amount;

            $nightLines[] = [
                'label' => persianDate($day)->format('l j F'),
                'hint' => $this->dayPriceHint($home, $day, $amount, $original),
                'amount' => $amount,
            ];
        }

        $extraGuest = (int) $order->extra_guest;
        $extraGuestUnit = (int) $home->price_per_surplus;
        $extraGuestTotal = $extraGuest > 0 ? $extraGuest * $extraGuestUnit * $nights : 0;

        $subtotalBeforeDiscount = $nightsSubtotal + $extraGuestTotal;
        $storedTotal = (int) $order->price;

        // همان منطق calcPrice هنگام ثبت رزرو
        $formulaTotal = $home->calcPrice(
            $order->start_at->copy(),
            $order->end_at->copy(),
            $extraGuest
        );

        $formulaDiscount = max(0, $subtotalBeforeDiscount - $formulaTotal);
        $actualDiscount = max(0, $subtotalBeforeDiscount - $storedTotal);

        $stayDiscount = $actualDiscount;
        $stayDiscountLabel = null;

        if ($stayDiscount > 0) {
            if ((int) $home->daily_off > 0
                && (int) $home->daily_off_amount > 0
                && $nights > (int) $home->daily_off
                && $formulaDiscount > 0) {
                $stayDiscountLabel = 'تخفیف '.persianNumber($home->daily_off_amount).'% برای اقامت بیش از '
                    .persianNumber($home->daily_off).' شب';
            } else {
                $stayDiscountLabel = 'تخفیف اقامت';
            }
        }

        $couponDiscount = (int) $order->discount;

        return [
            'nights' => $nights,
            'night_lines' => $nightLines,
            'nights_subtotal' => $nightsSubtotal,
            'extra_guest' => $extraGuest,
            'extra_guest_unit' => $extraGuestUnit,
            'extra_guest_total' => $extraGuestTotal,
            'subtotal_before_discount' => $subtotalBeforeDiscount,
            'stay_discount' => $stayDiscount,
            'stay_discount_label' => $stayDiscountLabel,
            'total' => $storedTotal,
            'coupon_discount' => $couponDiscount,
            'coupon_code' => $order->discountModel?->code,
            'payable' => $order->payable_price,
        ];
    }

    /**
     * مبلغ تسویه میزبان بر اساس جمع کل رزرو و کمیسیون سیاست لغو اقامتگاه.
     *
     * @return array{policy_title: string|null, commission_percent: int, commission_amount: int, payout_amount: int}
     */
    public function hostSettlement(Order $order): array
    {
        $order->loadMissing('home');
        $home = $order->home;
        $baseAmount = (int) $order->price;
        $commissionPercent = (int) $home->getCommission();
        $commissionAmount = (int) (($commissionPercent * $baseAmount) / 100);

        $policyTitle = null;
        if ($home->reject_policy && isset(Home::REJECT_POLICIES[$home->reject_policy])) {
            $policyTitle = Home::REJECT_POLICIES[$home->reject_policy]['title'];
        }

        return [
            'policy_title' => $policyTitle,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'payout_amount' => $order->getPayoutAmount(),
        ];
    }

    private function dayPriceHint(Home $home, Carbon $date, int $amount, int $original): ?string
    {
        if ($amount < $original) {
            return 'تخفیف لحظه‌آخری';
        }

        $hasCustomPrice = $home->custom_dates
            ->where('price', '!=', 0)
            ->contains(fn ($row) => $row->date->format('Y-m-d') === $date->format('Y-m-d'));

        if ($hasCustomPrice) {
            return 'قیمت اختصاصی این روز';
        }

        if ($date->isFriday()) {
            return 'نرخ جمعه';
        }
        if ($date->isThursday()) {
            return 'نرخ پنج‌شنبه';
        }
        if ($date->isWednesday()) {
            return 'نرخ چهارشنبه';
        }

        return 'نرخ روزهای عادی';
    }
}
