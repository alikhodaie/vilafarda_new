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

class SendAfterResidenceSMS implements ShouldQueue
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

        $orders = Order::query()->where('status', Order::IN_RENT)
            ->where('end_at', now()->startOfDay())
            ->get();

        foreach ($orders as $order){
            SMS::sendPattern($order->renter->mobile, '928220', [[
                'name' => 'ID',
                'value' => $order->home->id,
            ]]);
        }
    }
}
