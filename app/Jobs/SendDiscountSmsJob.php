<?php

namespace App\Jobs;

use App\Classes\SMS;
use App\Models\Discount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SendDiscountSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public readonly Discount $discount)
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
        if ($this->discount->sms_data) {
            $this->discount->users()->oldest()->chunkById(100, function (Collection $users) {
                if ($this->discount->sms_data->type === 'custom') {
                    SMS::sendBulk($users->pluck('mobile')->toArray(), $this->discount->sms_data->sms);
                }
                if ($this->discount->sms_data->type === 'pattern') {
                    foreach ($users as $user) {
                        SMS::sendPattern($user->mobile, $this->discount->sms_data->sms);
                    }
                }
            });
        }
    }
}
