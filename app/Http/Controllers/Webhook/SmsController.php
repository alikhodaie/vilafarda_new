<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function __invoke(Request $request)
    {
        $from = '0'.$request->get('from');
        $text = $request->get('text');

        if ($text && $from)
        {
            $method = null;
            if ($text == 1){
                $method = 'accept';
            }
            if ($text == 2){
                $method = 'reject';
            }

            $user = User::query()->where('mobile', $from)->first();

            if ($user && $method){
                $order = $user->orders()->latest()->where('status', Order::PENDING)->first();

                if ($order){
                    $order->$method();
                }
            }
        }

        return response()->json();
    }
}
