<?php

namespace App\Models;

use App\Classes\Traits\HasTransaction;
use App\Classes\Traits\PersianDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class Order extends Model
{
    use HasFactory, PersianDate, HasTransaction;

    public $timestamps = true;

    protected $guarded = [];

    protected $with = ['home', 'owner', 'renter'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'accepted_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    # region Const
    const START_DATE_HOUR = 14;
    const END_DATE_HOUR = 12;

    const MAX_PAY_TIME_IN_MINUTE = 30;

    const MAX_PENDING_TIME_IN_MINUTE = 30;

    const PENDING = 'pending';
    const REJECTED = 'rejected';
    const AWAITING_PAYMENT = 'awaiting_payment';
    const WAITING_FOR_RENTER = 'waiting_for_renter';
    const IN_RENT = 'in_rent';
    const DONE = 'done';
    const CANCELED = 'canceled';

    const REJECT_REASON_BOOKED = 'booked';
    const REJECT_REASON_WRONG_PRICE = 'wrong_price';
    const REJECT_REASON_MAINTENANCE = 'maintenance';

    const REJECT_REASONS = [
        self::REJECT_REASON_BOOKED => 'اقامتگاه رزرو است',
        self::REJECT_REASON_WRONG_PRICE => 'قیمت درست نیست',
        self::REJECT_REASON_MAINTENANCE => 'تعمیرات دارم',
    ];

    const TRIP_TAB_CURRENT = 'current';
    const TRIP_TAB_FINISHED = 'finished';
    const TRIP_TAB_UNSUCCESSFUL = 'unsuccessful';
    const TRIP_TAB_AWAITING_REVIEW = 'awaiting_review';

    const TRIP_TABS = [
        self::TRIP_TAB_CURRENT,
        self::TRIP_TAB_FINISHED,
        self::TRIP_TAB_UNSUCCESSFUL,
        self::TRIP_TAB_AWAITING_REVIEW,
    ];

    const HOST_ORDER_TAB_CURRENT = 'current';
    const HOST_ORDER_TAB_FINISHED = 'finished';
    const HOST_ORDER_TAB_UNSUCCESSFUL = 'unsuccessful';

    const HOST_ORDER_TABS = [
        self::HOST_ORDER_TAB_CURRENT,
        self::HOST_ORDER_TAB_FINISHED,
        self::HOST_ORDER_TAB_UNSUCCESSFUL,
    ];

    const STATUSES = [
        self::PENDING => [
            'value' => self::PENDING,
            'color' => 'warning',
            'fa_text_renter' => 'در انتظار تایید',
            'fa_text' => 'در انتظار تایید'
        ],
        self::AWAITING_PAYMENT => [
            'value' => self::AWAITING_PAYMENT,
            'color' => 'info',
            'fa_text_renter' => 'در انتظار پرداخت',
            'fa_text' => 'در انتظار پرداخت مهمان'
        ],
        self::WAITING_FOR_RENTER => [
            'value' => self::WAITING_FOR_RENTER,
            'color' => 'success',
            'fa_text_renter' => 'در انتظار مراجعه',
            'fa_text' => 'در انتظار مراجعه مهمان'
        ],
        self::IN_RENT => [
            'value' => self::IN_RENT,
            'color' => 'success',
            'fa_text_renter' => 'در حال استفاده',
            'fa_text' => 'در حال استفاده مهمان'
        ],
        self::DONE => [
            'value' => self::DONE,
            'color' => 'success',
            'fa_text_renter' => 'تکمیل شد',
            'fa_text' => 'تکمیل شد'
        ],
        self::REJECTED => [
            'value' => self::REJECTED,
            'color' => 'danger',
            'fa_text_renter' => 'رد شد',
            'fa_text' => 'رد شده'
        ],
        self::CANCELED => [
            'value' => self::CANCELED,
            'color' => 'danger',
            'fa_text_renter' => 'لفو شد',
            'fa_text' => 'لغو شده'
        ],
    ];
    # endregion

    # region Scopes
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request('id'));
        }
        if (request()->filled('owner')){
            $query->where('user_id', request('owner'));
        }
        if (request()->filled('renter')){
            $query->where('renter_id', request('renter'));
        }
        if (request()->filled('status')){
            $query->where('status', request('status'));
        }

        return $query;
    }

    public function scopeCheckoutPassed($query)
    {
        return $query->where('end_at', '<=', now()->subDay()->startOfDay());
    }

    public function scopeForTripTab($query, string $tab, int $renterId)
    {
        if (! in_array($tab, self::TRIP_TABS, true)) {
            $tab = self::TRIP_TAB_CURRENT;
        }

        return match ($tab) {
            self::TRIP_TAB_UNSUCCESSFUL => $query->whereIn('status', [self::REJECTED, self::CANCELED]),
            self::TRIP_TAB_FINISHED => $query
                ->checkoutPassed()
                ->whereNotIn('status', [self::REJECTED, self::CANCELED])
                ->whereHas('home.comments', fn ($q) => $q
                    ->where('user_id', $renterId)
                    ->whereNull('parent_id')),
            self::TRIP_TAB_AWAITING_REVIEW => $query
                ->checkoutPassed()
                ->whereNotIn('status', [self::REJECTED, self::CANCELED])
                ->whereDoesntHave('home.comments', fn ($q) => $q
                    ->where('user_id', $renterId)
                    ->whereNull('parent_id')),
            default => $query
                ->whereNotIn('status', [self::REJECTED, self::CANCELED])
                ->where(function ($q) {
                    $q->whereIn('status', [self::PENDING, self::AWAITING_PAYMENT])
                        ->orWhere(function ($q2) {
                            $q2->whereIn('status', [self::WAITING_FOR_RENTER, self::IN_RENT])
                                ->where('end_at', '>', now()->subDay()->startOfDay());
                        });
                }),
        };
    }

    public static function tripTabCounts(int $renterId): array
    {
        $base = static::query()->where('renter_id', $renterId);

        return [
            self::TRIP_TAB_CURRENT => (clone $base)->forTripTab(self::TRIP_TAB_CURRENT, $renterId)->count(),
            self::TRIP_TAB_FINISHED => (clone $base)->forTripTab(self::TRIP_TAB_FINISHED, $renterId)->count(),
            self::TRIP_TAB_UNSUCCESSFUL => (clone $base)->forTripTab(self::TRIP_TAB_UNSUCCESSFUL, $renterId)->count(),
            self::TRIP_TAB_AWAITING_REVIEW => (clone $base)->forTripTab(self::TRIP_TAB_AWAITING_REVIEW, $renterId)->count(),
        ];
    }

    public function scopeForHostOrderTab($query, string $tab)
    {
        if (! in_array($tab, self::HOST_ORDER_TABS, true)) {
            $tab = self::HOST_ORDER_TAB_CURRENT;
        }

        return match ($tab) {
            self::HOST_ORDER_TAB_UNSUCCESSFUL => $query->whereIn('status', [self::REJECTED, self::CANCELED]),
            self::HOST_ORDER_TAB_FINISHED => $query
                ->checkoutPassed()
                ->whereNotIn('status', [self::REJECTED, self::CANCELED]),
            default => $query
                ->whereNotIn('status', [self::REJECTED, self::CANCELED])
                ->where(function ($q) {
                    $q->whereIn('status', [self::PENDING, self::AWAITING_PAYMENT])
                        ->orWhere(function ($q2) {
                            $q2->whereIn('status', [self::WAITING_FOR_RENTER, self::IN_RENT, self::DONE])
                                ->where('end_at', '>', now()->subDay()->startOfDay());
                        });
                }),
        };
    }

    public function scopeHostOrderSort($query, ?string $sort = null)
    {
        $query->orderByRaw(
            'CASE status
                WHEN ? THEN 1
                WHEN ? THEN 2
                WHEN ? THEN 3
                WHEN ? THEN 4
                WHEN ? THEN 5
                ELSE 99
            END',
            [
                self::PENDING,
                self::AWAITING_PAYMENT,
                self::WAITING_FOR_RENTER,
                self::IN_RENT,
                self::DONE,
            ]
        );

        return match ($sort) {
            'checkin_desc' => $query->orderByDesc('start_at'),
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'newest' => $query->latest(),
            default => $query->orderBy('start_at'),
        };
    }

    public static function hostOrderTabCounts(int $hostUserId): array
    {
        $base = static::query()->where('user_id', $hostUserId);

        return [
            self::HOST_ORDER_TAB_CURRENT => (clone $base)->forHostOrderTab(self::HOST_ORDER_TAB_CURRENT)->count(),
            self::HOST_ORDER_TAB_FINISHED => (clone $base)->forHostOrderTab(self::HOST_ORDER_TAB_FINISHED)->count(),
            self::HOST_ORDER_TAB_UNSUCCESSFUL => (clone $base)->forHostOrderTab(self::HOST_ORDER_TAB_UNSUCCESSFUL)->count(),
        ];
    }
    # endregion

    # region Accessors
    public function getFullDatesAttribute(): array
    {
        return CarbonPeriod::create($this->start_at, $this->end_at)->toArray();
    }

    public function getPeriodTextAttribute(): string
    {
        return "$this->start_date $this->end_date";
    }

    public function getStartDateAttribute(): string
    {
        $start = $this->persianDate('start_at', 'Y/m/d');

        return __('text.start_date_text', [
            'date' => $start,
            'hour' => self::START_DATE_HOUR,
            'time' => __('title.noon')
        ]);
    }

    public function getEndDateAttribute(): string
    {
        $end = persianDate($this->end_at->addDay())->format('Y/m/d');

        return __('text.end_date_text', [
            'date' => $end,
            'hour' => self::END_DATE_HOUR,
            'time' => __('title.noon')
        ]);
    }

    public function getCountDaysAttribute(): int
    {
        $period = CarbonPeriod::between($this->start_at, $this->end_at);

        return $period->count();
    }

    public function getCountGuestAttribute(): int
    {
        return $this->main_guest + $this->extra_guest;
    }

    public function getContractTextAttribute(): string
    {
        return ($this->isPreContract())
            ? __('title.pre-contract')
            : __('title.contract');
    }

    public function getCheckinAttribute(): ?Carbon
    {
        return $this->start_at;
    }

    public function getCheckoutAttribute(): ?Carbon
    {
        return $this->end_at;
    }
    # endregion

    # region Methods
    public function checkInterference(Order $order): bool
    {
        return
            ($this->start_at <= $order->start_at && $order->start_at <= $this->end_at)
            ||
            ($this->start_at <= $order->end_at && $order->end_at <= $this->end_at);
    }

    public static function getMaxPayTime(Carbon $date = null): Carbon
    {
        return ($date)
            ? $date->copy()->addMinutes(self::MAX_PAY_TIME_IN_MINUTE)
            : now()->subMinutes(self::MAX_PAY_TIME_IN_MINUTE);
    }

    public static function getMaxPendingTime(Carbon $date = null): Carbon
    {
        return ($date)
            ? $date->copy()->addMinutes(self::MAX_PENDING_TIME_IN_MINUTE)
            : now()->subMinutes(self::MAX_PENDING_TIME_IN_MINUTE);
    }

    public static function getMinReserveDate(): Carbon
    {
        return now()->startOfDay();
    }

    public static function getMaxReserveDate(): Carbon
    {
        return now()->startOfDay()->addMonths(3);
    }

    public function getContractPDF(): \niklasravnsborg\LaravelPdf\Pdf
    {
        return Pdf::loadView('dashboard.orders.contract-pdf', ['order' => $this]);
    }

    public function isPreContract(): bool
    {
        return !in_array($this->status, [self::WAITING_FOR_RENTER, self::IN_RENT]);
    }

    public function status($index = 'fa_text')
    {
        return self::STATUSES[$this->status][$index];
    }

    public function getAwaitingPaymentTime(): ?Carbon
    {
        return $this->getPaymentDeadline();
    }

    public function getPendingDeadline(): ?Carbon
    {
        return $this->created_at
            ? self::getMaxPendingTime($this->created_at)
            : null;
    }

    public function getPaymentDeadline(): ?Carbon
    {
        return $this->accepted_at
            ? self::getMaxPayTime($this->accepted_at)
            : null;
    }

    public function isPendingDeadlinePassed(): bool
    {
        if ($this->status !== self::PENDING || ! $this->created_at) {
            return false;
        }

        $deadline = $this->getPendingDeadline();

        return $deadline && now()->gte($deadline);
    }

    public function isPaymentDeadlinePassed(): bool
    {
        if ($this->status !== self::AWAITING_PAYMENT || ! $this->accepted_at) {
            return false;
        }

        $deadline = $this->getPaymentDeadline();

        return $deadline && now()->gte($deadline);
    }

    public function wasExpiredDueToHostNonApproval(): bool
    {
        if ($this->status !== self::CANCELED || $this->accepted_at || $this->paid_at) {
            return false;
        }

        $deadline = $this->getPendingDeadline();

        return $deadline && $this->updated_at && $this->updated_at->gte($deadline);
    }

    public function wasExpiredDueToGuestNonPayment(): bool
    {
        if ($this->status !== self::CANCELED || ! $this->accepted_at || $this->paid_at) {
            return false;
        }

        $deadline = $this->getPaymentDeadline();

        return $deadline && $this->updated_at && $this->updated_at->gte($deadline);
    }

    public function expireIfOverdue(): bool
    {
        if ($this->isPendingDeadlinePassed()) {
            return $this->cancelDueToHostTimeout();
        }

        if ($this->isPaymentDeadlinePassed()) {
            return $this->cancelDueToPaymentTimeout();
        }

        return false;
    }

    /**
     * Promote paid reservations through lifecycle when stay dates have passed
     * (mirrors SetInRentStatusForOrders / SetDoneStatusForOrders).
     */
    public function advanceStatusIfDue(): bool
    {
        if (! in_array($this->status, [self::WAITING_FOR_RENTER, self::IN_RENT], true)) {
            return false;
        }

        if (! $this->paid_at) {
            return false;
        }

        if ($this->end_at && $this->end_at->lte(now()->subDay()->startOfDay())) {
            return (bool) $this->update(['status' => self::DONE]);
        }

        if ($this->status === self::WAITING_FOR_RENTER
            && $this->start_at
            && $this->start_at->lte(now()->startOfDay())) {
            return (bool) $this->update(['status' => self::IN_RENT]);
        }

        return false;
    }

    public function cancelDueToHostTimeout(): bool
    {
        if ($this->status !== self::PENDING) {
            return false;
        }

        $result = $this->cancel();

        if ($result && $this->home) {
            $this->home->custom_dates()->updateOrCreate(
                ['date' => $this->start_at],
                ['price' => 0]
            );
        }

        return $result;
    }

    public function cancelDueToPaymentTimeout(): bool
    {
        if ($this->status !== self::AWAITING_PAYMENT) {
            return false;
        }

        return $this->cancel();
    }

    public function accept(): bool
    {
        return $this->update(['status' => self::AWAITING_PAYMENT, 'accepted_at' => now()]);
    }

    public function reject(?string $reason = null): bool
    {
        return $this->update([
            'status' => self::REJECTED,
            'reject_reason' => $reason,
        ]);
    }

    public function rejectReasonLabel(): ?string
    {
        if (! $this->reject_reason) {
            return null;
        }

        return self::REJECT_REASONS[$this->reject_reason] ?? null;
    }

    public function cancel(): bool
    {
        return $this->update(['status' => self::CANCELED]);
    }

    public function canShowMap(): bool
    {
        return in_array($this->status, [self::WAITING_FOR_RENTER, self::IN_RENT]);
    }

    public function getPayoutAmount(): int
    {
        $commission = ($this->home->getCommission() * $this->price) / 100;
        return $this->price - $commission;
    }
    # endregion

    # region Relations
    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }

    public function discountModel()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function getPayablePriceAttribute(): int
    {
        return max(0, $this->price - (int) $this->discount);
    }
    # endregion
}
