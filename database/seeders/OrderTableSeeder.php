<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Home;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $users = User::all();
            $homes = Home::all();
            $consultants = Consultant::getFromCache();
            $dates = collect(CarbonPeriod::between(now()->addDays(10), now()->addMonths(2))->toArray());

            foreach ($users as $user) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    $home = $homes->where('user_id', '!=', $user->id)->shuffle()->first();
                    $start = $dates->shuffle()->first();
                    $end = Carbon::instance($start)->addDays(rand(0, 10));
                    $main_guest = rand(1, $home->main_guest);
                    $extra_guest = rand(0, $home->extra_guest);

                    $price = $home->calcPrice($start, $end, $extra_guest);
                    $status = Arr::random(array_keys(Order::STATUSES));
                    $accepted_at = ($status === Order::AWAITING_PAYMENT) ? now() : null;

                    $user->rents()->create([
                        'home_id' => $home->id,
                        'user_id' => $home->user_id,
                        'start_at' => $start,
                        'end_at' => $end,
                        'price' => $price,
                        'main_guest' => $main_guest,
                        'extra_guest' => $extra_guest,
//                    'consultant_id' => $consultants->random()->first()->id,
                        'status' => $status,
                        'accepted_at' => $accepted_at
                    ]);
                }
            }
        }
    }
}
