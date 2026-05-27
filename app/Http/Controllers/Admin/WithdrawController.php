<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Withdraw\BulkMarkPaidRequest;
use App\Http\Requests\Admin\Withdraw\UpdateRequest;
use App\Models\HostPayout;
use App\Services\HostPayoutService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', HostPayout::class);

        $items = HostPayout::query()
            ->with(['user', 'order.home', 'order.renter'])
            ->orderedForAdmin()
            ->paginate(15)
            ->appends($request->all());

        return view('admin.withdraws.index', compact(['items']));
    }

    public function create()
    {
        abort(404);
    }

    public function store()
    {
        abort(404);
    }

    public function edit(HostPayout $withdraw)
    {
        $this->authorize('update', $withdraw);

        $withdraw->load(['user', 'order.home', 'order.renter']);

        return view('admin.withdraws.edit', ['withdraw' => $withdraw]);
    }

    public function bulkMarkPaid(BulkMarkPaidRequest $request)
    {
        $ids = collect($request->input('ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        try {
            DB::beginTransaction();

            $payouts = HostPayout::query()
                ->whereIn('id', $ids)
                ->get()
                ->filter(fn (HostPayout $payout) => $payout->status === HostPayout::PENDING);

            $service = app(HostPayoutService::class);
            $marked = 0;

            foreach ($payouts as $payout) {
                $service->markPaid($payout);
                $marked++;
            }

            DB::commit();

            if ($marked === 0) {
                return redirect()
                    ->back()
                    ->with('warning', __('text.withdraw_bulk_none_pending'));
            }

            return redirect()
                ->back()
                ->with('success', __('text.success.bulk_mark_withdraw_paid', ['count' => $marked]));
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);

            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function update(HostPayout $withdraw, UpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $status = $request->get('status');
            $paymentReference = trim((string) $request->input('payment_reference', ''));
            $paymentReference = $paymentReference !== '' ? $paymentReference : null;

            if ($status === HostPayout::PAID) {
                app(HostPayoutService::class)->markPaid($withdraw);
                $withdraw->update(['payment_reference' => $paymentReference]);
            } elseif ($status === HostPayout::PENDING) {
                $withdraw->update([
                    'status' => HostPayout::PENDING,
                    'paid_at' => null,
                    'payment_reference' => $paymentReference,
                ]);
            } elseif ($status === HostPayout::CANCELLED && $withdraw->status !== HostPayout::PAID) {
                $withdraw->update([
                    'status' => HostPayout::CANCELLED,
                    'paid_at' => null,
                    'payment_reference' => $paymentReference,
                ]);
            } else {
                $withdraw->update(['payment_reference' => $paymentReference]);
            }

            DB::commit();

            return redirect()->back()->with('success', __('text.success.update_withdraw'));
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __METHOD__);

            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
