<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TicketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $faker = Factory::create('fa_IR');

            $users = User::all();

            for ($i = 0; $i < rand(10, 30); $i++) {
                $user = $users->random(1)->first();
                $ticket = $user->tickets()->create([
                    'title' => $faker->realText(50),
                    'status' => Arr::random(array_keys(Ticket::STATUS)),
                ]);

                for ($y = 0; $y < rand(3, 7); $y++) {
                    $user_id = (rand(0, 1)) ? $user->id : User::query()->get('id')->random(1)->first()->id;
                    $sent_from = ($user_id === $user->id) ? TicketMessage::USER : TicketMessage::ADMIN;

                    $message = $ticket->messages()->create([
                        'content' => $faker->realText(rand(400, 800)),
                        'sent_from' => $sent_from,
                        'user_id' => $user_id
                    ]);

                    if (rand(0, 1)) {
                        for ($x = 0; $x < rand(1, 5); $x++) {
                            $message->attachments()->create(['file' => 'about2.png']);
                        }
                    }
                }
            }
        }
    }
}
