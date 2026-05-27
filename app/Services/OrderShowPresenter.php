<?php

namespace App\Services;

use App\Models\Home;
use App\Models\Order;

class OrderShowPresenter
{
    public function checkoutPassed(Order $order): bool
    {
        return $order->end_at && $order->end_at->lte(now()->subDay()->startOfDay());
    }

    public function isUnsuccessful(Order $order): bool
    {
        if (in_array($order->status, [Order::REJECTED, Order::CANCELED], true)) {
            return true;
        }

        return $order->isPendingDeadlinePassed() || $order->isPaymentDeadlinePassed();
    }

    public function canHostRespondToPending(Order $order): bool
    {
        return $order->status === Order::PENDING && ! $order->isPendingDeadlinePassed();
    }

    public function canRenterCancel(Order $order): bool
    {
        if ($order->status === Order::PENDING) {
            return ! $order->isPendingDeadlinePassed();
        }

        if ($order->status === Order::AWAITING_PAYMENT) {
            return ! $order->isPaymentDeadlinePassed();
        }

        return false;
    }

    public function canRenterPay(Order $order): bool
    {
        return $order->status === Order::AWAITING_PAYMENT
            && ! $order->paid_at
            && ! $order->isPaymentDeadlinePassed();
    }

    public function displayStatusLabel(Order $order, string $audience = 'host'): string
    {
        if ($order->isPendingDeadlinePassed() || $order->wasExpiredDueToHostNonApproval()) {
            return __('title.expired_host_non_approval');
        }

        if ($order->isPaymentDeadlinePassed() || $order->wasExpiredDueToGuestNonPayment()) {
            return __('title.expired_guest_non_payment');
        }

        if ($order->status === Order::DONE && ! $order->paid_at) {
            return 'پرداخت نشده';
        }

        if ($this->checkoutPassed($order) && ! $this->isUnsuccessful($order)) {
            return $order->status === Order::DONE
                ? $order->status($audience === 'renter' ? 'fa_text_renter' : 'fa_text')
                : 'خاتمه یافته';
        }

        $index = $audience === 'renter' ? 'fa_text_renter' : 'fa_text';

        return $order->status($index);
    }

    public function displayStatusColor(Order $order): string
    {
        if ($order->isPendingDeadlinePassed()
            || $order->isPaymentDeadlinePassed()
            || $order->wasExpiredDueToHostNonApproval()
            || $order->wasExpiredDueToGuestNonPayment()) {
            return 'danger';
        }

        return $order->status('color');
    }

    public function isCurrentForHost(Order $order): bool
    {
        if ($this->isUnsuccessful($order)) {
            return false;
        }

        if ($this->checkoutPassed($order)) {
            return false;
        }

        return true;
    }

    public function canDownloadContract(Order $order): bool
    {
        if ($this->isUnsuccessful($order) || $this->checkoutPassed($order)) {
            return false;
        }

        return (bool) $order->paid_at;
    }

    /**
     * @return array<int, array{label: string, state: string, is_current: bool}>
     */
    public function tripSteps(Order $order, bool $checkoutPassed, bool $isUnsuccessful): array
    {
        $labels = ['درخواست', 'بررسی', 'پرداخت', 'تحویل'];

        if ($isUnsuccessful) {
            return $this->unsuccessfulTripSteps($order, $labels);
        }

        $current = match ($order->status) {
            Order::PENDING => 1,
            Order::AWAITING_PAYMENT => 2,
            Order::WAITING_FOR_RENTER, Order::IN_RENT => 3,
            Order::DONE => 3,
            default => 1,
        };

        $steps = [];
        foreach ($labels as $i => $label) {
            if ($checkoutPassed || ($order->status === Order::DONE && $i <= 3)) {
                $state = 'done';
                $isCurrent = $i === 3;
            } elseif ($i === 0) {
                $state = 'done';
                $isCurrent = false;
            } elseif ($i < $current) {
                $state = 'done';
                $isCurrent = false;
            } elseif ($i === $current) {
                $state = 'active';
                $isCurrent = true;
            } else {
                $state = 'pending';
                $isCurrent = false;
            }

            if ($checkoutPassed) {
                $state = 'done';
                $isCurrent = $i === 3;
            }

            $steps[] = [
                'label' => $label,
                'state' => $state,
                'is_current' => $isCurrent,
            ];
        }

        return $steps;
    }

    /**
     * @return array{title: string, text: string, icon: string, icon_modifier: string}
     */
    public function statusContentForHost(Order $order, bool $checkoutPassed, bool $isUnsuccessful): array
    {
        if ($order->wasExpiredDueToHostNonApproval() || $order->isPendingDeadlinePassed()) {
            return [
                'title' => __('title.expired_host_non_approval'),
                'text' => 'مهلت ۳۰ دقیقه‌ای تأیید درخواست به پایان رسید و این رزرو منقضی شد.',
                'icon' => 'bi-x-lg',
                'icon_modifier' => 'danger',
            ];
        }

        if ($order->wasExpiredDueToGuestNonPayment() || $order->isPaymentDeadlinePassed()) {
            return [
                'title' => __('title.expired_guest_non_payment'),
                'text' => 'مهلت ۳۰ دقیقه‌ای پرداخت مهمان به پایان رسید و این رزرو منقضی شد.',
                'icon' => 'bi-x-lg',
                'icon_modifier' => 'danger',
            ];
        }

        if ($isUnsuccessful) {
            return match ($order->status) {
                Order::REJECTED => [
                    'title' => 'درخواست رزرو، توسط شما رد شد',
                    'text' => 'رد درخواست‌های رزرو می‌تواند باعث کاهش امتیاز میزبانی، کاهش بازدید اقامتگاه و در موارد مکرر تعلیق آگهی شود. لطفاً در رد درخواست‌ها دقت کنید.',
                    'icon' => 'bi-x-lg',
                    'icon_modifier' => 'danger',
                ],
                default => [
                    'title' => 'این رزرو لغو شد',
                    'text' => 'این رزرو توسط مهمان لغو شده و در فرآیند رزرو ادامه پیدا نکرده است.',
                    'icon' => 'bi-x-lg',
                    'icon_modifier' => 'danger',
                ],
            };
        }

        if ($checkoutPassed || $order->status === Order::DONE) {
            return [
                'title' => 'رزرو پایان یافته است',
                'text' => 'از زحمات شما برای ایجاد تجربه‌ای خوب برای مهمانان سپاسگزاریم. امیدواریم مهمان بعدی هم از اقامت در اقامتگاه شما لذت ببرد.',
                'icon' => 'bi-check-lg',
                'icon_modifier' => 'success',
            ];
        }

        return match ($order->status) {
            Order::PENDING => [
                'title' => 'در انتظار تأیید شما',
                'text' => 'مهمان درخواست رزرو داده است. لطفاً تا پایان مهلت ۳۰ دقیقه‌ای درخواست را تأیید یا رد کنید.',
                'icon' => 'bi-hourglass-split',
                'icon_modifier' => 'warning',
            ],
            Order::AWAITING_PAYMENT => [
                'title' => 'در انتظار پرداخت مهمان',
                'text' => 'درخواست را تأیید کرده‌اید. مهمان ۳۰ دقیقه برای پرداخت زمان دارد.',
                'icon' => 'bi-credit-card',
                'icon_modifier' => 'warning',
            ],
            Order::WAITING_FOR_RENTER => [
                'title' => 'آماده پذیرش مهمان',
                'text' => 'پرداخت انجام شد. مهمان در تاریخ ورود به اقامتگاه مراجعه می‌کند.',
                'icon' => 'bi-door-open',
                'icon_modifier' => 'success',
            ],
            Order::IN_RENT => [
                'title' => 'مهمان در حال اقامت است',
                'text' => 'مهمان در اقامتگاه شما حضور دارد. در صورت نیاز با مهمان هماهنگ کنید.',
                'icon' => 'bi-house-check',
                'icon_modifier' => 'success',
            ],
            default => [
                'title' => $order->status(),
                'text' => 'وضعیت این رزرو در همین صفحه قابل مشاهده است.',
                'icon' => 'bi-info-circle',
                'icon_modifier' => '',
            ],
        };
    }

    /**
     * @return array{title: string, text: string, icon: string, icon_modifier: string}
     */
    public function statusContentForRenter(Order $order, bool $checkoutPassed, bool $isUnsuccessful): array
    {
        if ($order->wasExpiredDueToHostNonApproval() || $order->isPendingDeadlinePassed()) {
            return [
                'title' => __('title.expired_host_non_approval'),
                'text' => 'میزبان در مهلت مقرر درخواست را تأیید نکرد. می‌توانید اقامتگاه دیگری انتخاب کنید.',
                'icon' => 'bi-x-lg',
                'icon_modifier' => 'danger',
            ];
        }

        if ($order->wasExpiredDueToGuestNonPayment() || $order->isPaymentDeadlinePassed()) {
            return [
                'title' => __('title.expired_guest_non_payment'),
                'text' => 'مهلت پرداخت به پایان رسید. برای رزرو مجدد درخواست جدید ثبت کنید.',
                'icon' => 'bi-x-lg',
                'icon_modifier' => 'danger',
            ];
        }

        if ($isUnsuccessful) {
            return match ($order->status) {
                Order::REJECTED => [
                    'title' => 'رزرو رد شد',
                    'text' => 'متأسفانه میزبان این درخواست رزرو را تأیید نکرد. می‌توانید اقامتگاه دیگری انتخاب کنید.',
                    'icon' => 'bi-x-lg',
                    'icon_modifier' => 'danger',
                ],
                default => [
                    'title' => 'رزرو لغو شد',
                    'text' => 'این رزرو لغو شده است. در صورت نیاز می‌توانید مجدداً اقدام به رزرو کنید.',
                    'icon' => 'bi-x-lg',
                    'icon_modifier' => 'danger',
                ],
            };
        }

        if ($checkoutPassed || $order->status === Order::DONE) {
            return [
                'title' => 'رزرو پایان یافته است',
                'text' => 'از اینکه همراه ما بودید سپاسگزاریم. امیدواریم اقامت خوبی داشته باشید و دوباره شما را ببینیم.',
                'icon' => 'bi-check-lg',
                'icon_modifier' => 'success',
            ];
        }

        return match ($order->status) {
            Order::PENDING => [
                'title' => 'در انتظار تأیید میزبان',
                'text' => 'درخواست رزرو شما ثبت شد. میزبان تا ۳۰ دقیقه برای تأیید یا رد زمان دارد.',
                'icon' => 'bi-hourglass-split',
                'icon_modifier' => 'warning',
            ],
            Order::AWAITING_PAYMENT => [
                'title' => 'در انتظار پرداخت',
                'text' => 'رزرو شما تأیید شد. لطفاً تا پایان مهلت ۳۰ دقیقه‌ای هزینه اقامت را پرداخت کنید.',
                'icon' => 'bi-credit-card',
                'icon_modifier' => 'warning',
            ],
            Order::WAITING_FOR_RENTER => [
                'title' => 'آماده ورود',
                'text' => 'پرداخت شما با موفقیت انجام شد. در تاریخ ورود به اقامتگاه مراجعه کنید.',
                'icon' => 'bi-door-open',
                'icon_modifier' => 'success',
            ],
            Order::IN_RENT => [
                'title' => 'اقامت در جریان است',
                'text' => 'امیدواریم اقامت خوبی داشته باشید. در صورت نیاز با میزبان تماس بگیرید.',
                'icon' => 'bi-house-check',
                'icon_modifier' => 'success',
            ],
            default => [
                'title' => $order->status('fa_text_renter'),
                'text' => 'جزئیات وضعیت رزرو شما در همین صفحه قابل مشاهده است.',
                'icon' => 'bi-info-circle',
                'icon_modifier' => '',
            ],
        };
    }

    /**
     * @return array{label: string, text: string|null, is_placeholder: bool}
     */
    public function rejectionReasonSection(Order $order): array
    {
        $reason = $order->rejectReasonLabel();

        return [
            'label' => $order->status === Order::REJECTED ? 'علت رد درخواست' : 'علت لغو رزرو',
            'text' => $reason,
            'is_placeholder' => $reason === null || trim($reason) === '',
        ];
    }

    public function unsuccessfulProgressPercent(Order $order): int
    {
        $failedIndex = $this->unsuccessfulFailedStepIndex($order);
        $totalSteps = 4;

        if ($totalSteps <= 1) {
            return 0;
        }

        return (int) round(($failedIndex / ($totalSteps - 1)) * 100);
    }

    /**
     * @param array<int, string> $labels
     * @return array<int, array{label: string, state: string, is_current: bool}>
     */
    private function unsuccessfulTripSteps(Order $order, array $labels): array
    {
        $failedIndex = $this->unsuccessfulFailedStepIndex($order);
        $steps = [];

        foreach ($labels as $i => $label) {
            if ($i < $failedIndex) {
                $state = 'done';
            } elseif ($i === $failedIndex) {
                $state = 'failed';
            } else {
                $state = 'pending';
            }

            $steps[] = [
                'label' => $label,
                'state' => $state,
                'is_current' => $i === $failedIndex,
            ];
        }

        return $steps;
    }

    private function unsuccessfulFailedStepIndex(Order $order): int
    {
        if ($order->status === Order::REJECTED) {
            return 1;
        }

        if ($order->status === Order::CANCELED) {
            if ($order->paid_at) {
                return 3;
            }
            if ($order->accepted_at) {
                return 2;
            }

            return 1;
        }

        return 1;
    }

    /**
     * @return array{title: string, lines: array<int, string>}|null
     */
    public function cancelPolicyLines(Home $home): ?array
    {
        if (! $home->reject_policy || ! isset(Home::REJECT_POLICIES[$home->reject_policy])) {
            return null;
        }

        $description = Home::getRejectPolicyDescription($home->reject_policy);
        $lines = $description !== ''
            ? array_values(array_filter(array_map('trim', preg_split('/\.\s+/u', rtrim((string) $description, '.')))))
            : [];

        if ($lines === []) {
            return null;
        }

        return [
            'title' => $home->rejectPolicy(),
            'lines' => $lines,
        ];
    }
}
