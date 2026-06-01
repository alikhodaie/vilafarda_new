<?php

namespace App\Observers;

use App\Classes\SMS;
use App\Models\Order;
use App\Services\HomeStatisticsService;
use App\Services\HostPayoutService;
use App\Services\OrderAdminSmsService;
use Illuminate\Support\Str;


class OrderObserver
{
    public function __construct(
        protected OrderAdminSmsService $orderAdminSmsService
    ) {
    }

    /**
     * Handle the Order "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created(Order $order)
    {
        $rotatingAdmin = $this->orderAdminSmsService->pickNextRotatingAdmin();

        SMS::sendPattern(
            $order->renter->mobile,
            config('sms.patterns.order_created_renter'),
            $this->orderAdminSmsService->buildGuestSmsParameters($order, $rotatingAdmin)
        );

        SMS::sendPattern($order->owner->mobile, config('sms.patterns.order_created_owner'), [
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
                'value' => number_format($order->getPayoutAmount()),
            ],
        ]);

        $adminParameters = $this->orderAdminSmsService->buildAdminSmsParameters($order);
        $adminPattern = config('sms.patterns.order_created_admin');
        $alwaysAdminIds = $this->orderAdminSmsService->getAlwaysAdmins()->pluck('id');

        foreach ($this->orderAdminSmsService->getAlwaysAdmins() as $admin) {
            SMS::sendPattern($admin->mobile, $adminPattern, $adminParameters);
        }

        if ($rotatingAdmin && ! $alwaysAdminIds->contains($rotatingAdmin->id)) {
            SMS::sendPattern($rotatingAdmin->mobile, $adminPattern, $adminParameters);
        }
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->wasChanged('paid_at') && $order->paid_at) {
            app(HomeStatisticsService::class)->recordIncome(
                $order->home,
                (int) $order->price,
                $order->paid_at
            );
        }

        if ($order->isDirty('status')){
            if ($order->status === Order::AWAITING_PAYMENT){
                SMS::sendPattern($order->renter->mobile, '328688', [
                    [
                        'name' => 'ID',
                        'value' => Str::limit($order->home->code, 25, ''),
                    ]
                    ]);
//                SMS::sendPattern($order->owner->mobile, '4pfs6agwusq7gnv', ['name' => $order->home->name]);

                $orders = $order->owner->orders()->where('id', '!=', $order->id)->where('status', Order::PENDING)->get();
                foreach ($orders as $item){
                    if ($order->checkInterference($item)){
                        $item->update(['status' => Order::REJECTED]);
                    }
                }
            }
            if ($order->status === Order::CANCELED){
                SMS::sendPattern($order->renter->mobile, '356697', [
                    [
                        'name' => 'ID',
                        'value' => Str::limit($order->home->code, 25, ''),
                    ]
                    ]);
                SMS::sendPattern($order->owner->mobile, '356697', [
                    [
                        'name' => 'ID',
                        'value' => Str::limit($order->home->code, 25, ''),
                    ]
                ]);


                $amount = 1;
                if ($order->renter->count_canceled_orders === 0){
                   $amount = $order->renter->rents()->whereIn('status', [Order::CANCELED, Order::REJECTED])->count();
                    if ($amount === 0){
                        $amount = 1;
                    }
                }
                $order->renter()->increment('count_canceled_orders', $amount);
            }
            if ($order->status === Order::REJECTED){
                SMS::sendPattern($order->renter->mobile, '277906', [
                    [
                        'name' => 'HOME-NAME',
                        'value' => Str::limit($order->home->name, 25, ''),
                    ]
                    ]);

//                SMS::sendPattern($order->owner->mobile, 'oogvrnsz22do5mc', ['name' => $order->home->name]);

                $amount = 1;
                if ($order->renter->count_canceled_orders === 0){
                    $amount = $order->renter->rents()->whereIn('status', [Order::CANCELED, Order::REJECTED])->count();
                    if ($amount === 0){
                        $amount = 1;
                    }
                }
                $order->renter()->increment('count_canceled_orders', $amount);
            }
            if (in_array($order->status, [Order::CANCELED, Order::REJECTED], true)) {
                app(HostPayoutService::class)->cancelForOrder($order);
            }
            if ($order->status === Order::WAITING_FOR_RENTER){
                app(HostPayoutService::class)->createForOrder($order);
//                SMS::sendPattern([$order->renter->mobile], "درخواست رزور شما برای اقماتگاه {$order->home->name} رد شد.");
                SMS::sendPattern($order->owner->mobile, '588298', [
                    [
                        'name' => 'ID',
                        'value' => Str::limit($order->home->code, 25, ''),
                    ],
                    [
                        'name' => 'START-DATE',
                        'value' => $order->persianDate('start_at', '%A d F Y'),
                    ],
                    [
                        'name' => 'END-DATE',
                        'value' => persianDate($order->end_at->addDay())->format('%A d F Y'),
                    ],
                    [
                        'name' => 'AMOUNT',
                        'value' => number_format($order->getPayoutAmount()),
                    ],
                    [
                        'name' => 'USERNAME',
                        'value' => Str::limit($order->renter->full_name, 25, ''),
                    ],
                    [
                        'name' => 'MOBILE',
                        'value' => $order->renter->mobile,
                    ],
                ]);
            }
            if ($order->status === Order::DONE){
                $amount = 1;
                if ($order->renter->count_done_orders === 0){
                    $amount = $order->renter->rents()->where('status', Order::DONE)->count();
                    if ($amount === 0){
                        $amount = 1;
                    }
                }
                $order->renter()->increment('count_done_orders', $amount);
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param Order $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
