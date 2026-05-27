<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Home;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function users(Request $request)
    {
        $users = User::query()->search()->paginate(10)->appends($request->all());

        foreach ($users as $user){
            $user->full_name = $user->full_name;
        }

        return response()->json($users);
    }

    public function homes(Request $request)
    {
        $homes = Home::query()->search()->when($request->filled('user_id'), fn (Builder $query) =>
            $query->whereHas('orders', fn (Builder $orders) =>
                $orders
                    ->where('status', Order::DONE)
                    ->where('renter_id', $request->get('user_id'))
            )
        )->paginate(10)->appends($request->all());

        return response()->json($homes);
    }

    public function articles(Request $request)
    {
        $articles = Article::query()->search()->paginate(10)->appends($request->all());

        return response()->json($articles);
    }
}
