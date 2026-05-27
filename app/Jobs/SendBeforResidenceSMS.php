<?php

namespace App\Jobs;

use App\Classes\SMS;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBeforResidenceSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::query()->where('status', Order::WAITING_FOR_RENTER)
            ->where('start_at', '>', now())
            ->where('start_at', '<', now()->addDays(2)->startOfDay())
            ->get();

        foreach ($orders as $order){
            SMS::sendPattern($order->renter->mobile, '810272', [[
                'name' => 'ID',
                'value' => $order->home->code,
            ]]);
        }
    }
}
