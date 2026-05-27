<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelUndeterminedOrders implements ShouldQueue
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
        $orders = Order::query()->where('status', Order::PENDING)
            ->where('created_at', '<', Order::getMaxPendingTime())->get();

        foreach ($orders as $order){
            $order->cancel();
            // set custom reserved for that day
            $order->home->custom_dates()->updateOrCreate(['date' => $order->start_at], ['price' => 0]);
        }
    }
}
