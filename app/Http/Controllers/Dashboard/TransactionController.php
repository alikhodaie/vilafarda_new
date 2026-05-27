<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HostPayout;
use App\Models\Transaction;
use App\Services\OrderDeadlineService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function hostIndex(Request $request)
    {
        $tab = $request->get('tab', HostPayout::TAB_PENDING);

        if (! in_array($tab, HostPayout::TABS, true)) {
            $tab = HostPayout::TAB_PENDING;
        }

        $userId = (int) auth()->id();
        $counts = HostPayout::tabCounts($userId);

        $payouts = auth()->user()->hostPayouts()
            ->forTab($tab)
            ->with(['order.home.province', 'order.home.city', 'order.renter'])
            ->latest()
            ->paginate(10)
            ->appends(['tab' => $tab]);

        $lifecycle = app(OrderDeadlineService::class);
        foreach ($payouts as $payout) {
            if ($payout->order) {
                $lifecycle->syncOrderLifecycle($payout->order);
            }
        }

        if ($request->is_mobile ?? false) {
            return view('dashboard.transactions.host-index-mobile', compact('payouts', 'tab', 'counts'));
        }

        return view('dashboard.transactions.host-index', compact('payouts', 'tab', 'counts'));
    }

    public function guestIndex(Request $request)
    {
        $transactions = auth()->user()->transactions()
            ->reservationPurchases()
            ->with(['orders.home.province', 'orders.home.city'])
            ->latest()
            ->paginate(10);

        $lifecycle = app(OrderDeadlineService::class);
        foreach ($transactions as $transaction) {
            foreach ($transaction->orders as $order) {
                $lifecycle->syncOrderLifecycle($order);
            }
            $transaction->syncStatusFromOrders();
            $transaction->refresh();
            $transaction->load('orders.home');
        }

        if ($request->is_mobile ?? false) {
            return view('dashboard.transactions.guest-index-mobile', compact('transactions'));
        }

        return view('dashboard.transactions.guest-index', compact('transactions'));
    }
}
