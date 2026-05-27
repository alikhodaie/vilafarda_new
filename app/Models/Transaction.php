<?php

namespace App\Models;

use App\Classes\Payment\Gateway\IDPay;
use App\Classes\Payment\Gateway\Wallet;
use App\Classes\Payment\Gateway\Zarinpal;
use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Transaction extends Model
{
    use HasFactory, PersianDate;

    # region Variables
    protected $guarded = [];

    public $timestamps = true;
    # endregion

    # region Const
    const IDPAY = 'idpay';
    const ZARINPAL = 'zarinpal';
    const WALLET = 'wallet';

    const GATEWAY = [
        self::IDPAY => [
            'value' => self::IDPAY,
            'text' => 'درگاه پرداخت IDPay',
            'description' => 'انتقال به درگاه پرداخت آنلاین IDPay',
            'class' => IDPay::class
        ],
        self::ZARINPAL => [
            'value' => self::ZARINPAL,
            'text' => 'درگاه پرداخت Zarinpal',
            'description' => 'انتقال به درگاه پرداخت آنلاین Zarinpal',
            'class' => Zarinpal::class
        ],
        self::WALLET => [
            'value' => self::WALLET,
            'text' => 'کیف پول',
            'description' => 'استفاده از مقدار آزاد کیف پول',
            'class' => Wallet::class
        ]
    ];

    const SUCCESS = 'success';
    const IN_PROCESS = 'in_process';
    const FAILED = 'failed';

    const STATUS = [
        self::SUCCESS => [
            'value' => self::SUCCESS,
            'text' => 'موفق',
            'color' => 'success'
        ],
        self::IN_PROCESS => [
            'value' => self::IN_PROCESS,
            'text' => 'درحال پرداخت',
            'color' => 'warning'
        ],
        self::FAILED => [
            'value' => self::FAILED,
            'text' => 'ناموفق',
            'color' => 'danger'
        ]
    ];

    const PURCHASE = 'purchase';
    const WALLET_WITHDRAW = 'wallet_withdraw';
    const WALLET_DEPOSIT = 'wallet_deposit';

    const TYPE = [
        self::PURCHASE => [
            'value' => self::PURCHASE,
            'text' => 'سفارش',
        ],
        self::WALLET_WITHDRAW => [
            'value' => self::WALLET_WITHDRAW,
            'text' => 'برداشت از کیف پول',
        ],
        self::WALLET_DEPOSIT => [
            'value' => self::WALLET_DEPOSIT,
            'text' => 'واریز به کیف پول',
        ],
    ];
    # endregion

    # region Scopes
    public function scopeProcess($query)
    {
        return $query->where('status', self::IN_PROCESS);
    }

    public function scopeReservationPurchases($query)
    {
        return $query
            ->where('type', self::PURCHASE)
            ->whereHas('orders');
    }
    # endregion

    # region Methods
    public function orderIndicatesSuccessfulPayment(?Order $order): bool
    {
        if (! $order) {
            return false;
        }

        if ($order->paid_at) {
            return true;
        }

        return in_array($order->status, [
            Order::WAITING_FOR_RENTER,
            Order::IN_RENT,
            Order::DONE,
        ], true);
    }

    public function effectiveStatus(): string
    {
        $order = $this->relationLoaded('orders')
            ? $this->orders->first()
            : $this->orders()->first();

        if ($this->status === self::IN_PROCESS) {
            if ($this->orderIndicatesSuccessfulPayment($order)) {
                return self::SUCCESS;
            }

            if ($order && in_array($order->status, [Order::CANCELED, Order::REJECTED], true)) {
                return self::FAILED;
            }
        }

        return $this->status;
    }

    public function status($index = 'text', ?string $status = null)
    {
        $status ??= $this->effectiveStatus();

        return self::STATUS[$status][$index];
    }

    public function displayStatusColor(): string
    {
        return $this->status('color');
    }

    /**
     * Align stale gateway rows when the linked reservation was already paid.
     */
    public function syncStatusFromOrders(): bool
    {
        $order = $this->relationLoaded('orders')
            ? $this->orders->first()
            : $this->orders()->first();

        $effective = $this->effectiveStatus();
        $changed = false;

        if ($effective !== $this->status) {
            $this->update(['status' => $effective]);
            $this->status = $effective;
            $changed = true;
        }

        if ($effective === self::SUCCESS
            && $order
            && ! $order->paid_at
            && $this->orderIndicatesSuccessfulPayment($order)) {
            $order->update(['paid_at' => $this->updated_at ?? $this->created_at ?? now()]);
            $changed = true;
        }

        return $changed;
    }

    public function gateway($index = 'text')
    {
        return self::GATEWAY[$this->gateway][$index];
    }

    public static function checkPayment(Request $request): array
    {
        $transaction = null;
        $success_payment = false;

        if ($request->filled('id') && $request->filled('track_id') && $request->filled('status')) {
            $transaction = Transaction::query()->process()->where('code', $request->get('id'))->first();

            if ($request->get('status') == 10) {
                $success_payment = true;
            }
        }
        if ($request->filled('status') && $request->filled('token')) {
            $transaction = Transaction::query()->process()->where('code', $request->get('token'))->first();

            if ($request->get('status') == 1) {
                $success_payment = true;
            }
        }
        if ($request->filled('Status') && $request->filled('Authority')) {
            $transaction = Transaction::query()->process()->where('code', $request->get('Authority'))->first();

            if ($request->get('Status') == 'OK') {
                $success_payment = true;
            }
        }

        return compact(['transaction', 'success_payment']);
    }

    public function pay(): string
    {
        $payment = $this->gateway('class');
        $payment = new $payment($this);
        return $payment->pay();
    }

    public function verify(): bool
    {
        $payment = $this->gateway('class');
        $payment = new $payment($this);
        return $payment->verify();
    }

    public function process(): void
    {
        $data = [
            'status' => Transaction::SUCCESS,
        ];

        # transaction order
        foreach ($this->orders as $order) {
            if ($order->discountModel) {
                $order->discountModel->orders()
                    ->whereNotNull('discount_id')
                    ->where('renter_id', $this->user_id)
                    ->where('id', '!=', $order->id)
                    ->update([
                        'discount_id' => null,
                        'discount' => 0,
                    ]);

                $order->discountModel->increment('count_usage');
                $order->renter->discounts()->updateExistingPivot($order->discount_id, ['is_used' => true]);
            }

            $order->update([
                'paid_at' => now(),
                'status' => Order::WAITING_FOR_RENTER
            ]);

//            $transaction->user->wallet->increment('on_hold', $transaction->price);
//            $message = 'یک سفارش تبلیغ جدید دارید لطفا پنل خود را در سایت برندشیم چک بفرمایید';

//            ISMS::send([$order->service->page->user->mobile], $message);
        }

        # wallet deposit
//        if ($transaction->type === Transaction::WALLET_DEPOSIT) {
//            $transaction->user->wallet()->increment('value', $transaction->price);
//        }

        if ($this->gateway === Transaction::WALLET) {
            $data['reference'] = rand(100000, 999999);
        }

        $this->update($data);
    }
    # endregion

    # region Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->morphedByMany(Order::class, 'transactionable', 'transactionable');
    }
    # endregion
}
