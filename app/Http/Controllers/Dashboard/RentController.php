<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Rent\RentDiscountRequest;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\OrderDeadlineService;
use App\Services\OrderInvoiceService;
use App\Services\OrderShowPresenter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is_mobile ?? false) {
            $user = auth()->user();
            $tab = $request->get('tab', Order::TRIP_TAB_CURRENT);

            if (! in_array($tab, Order::TRIP_TABS, true)) {
                $tab = Order::TRIP_TAB_CURRENT;
            }

            $counts = Order::tripTabCounts($user->id);
            $rents = $user->rents()
                ->forTripTab($tab, $user->id)
                ->with(['home.province', 'home.city', 'owner'])
                ->latest()
                ->paginate(10)
                ->appends(['tab' => $tab]);

            app(OrderDeadlineService::class)->expireCollectionIfOverdue($rents->getCollection());

            return view('dashboard.rents.index-mobile', compact('rents', 'tab', 'counts'));
        }

        $rents = auth()->user()->rents()->search()->latest()->paginate(10)->appends($request->all());

        app(OrderDeadlineService::class)->expireCollectionIfOverdue($rents->getCollection());

        return view('dashboard.rents.index', compact('rents'));
    }

    public function show(Request $request, $rent)
    {
        $rent = auth()->user()->rents()
            ->with(['home.province', 'home.city', 'home.custom_dates', 'owner', 'discountModel'])
            ->findOrFail($rent);

        if ($request->is_mobile ?? false) {
            $renterId = (int) auth()->id();
            $rent = app(OrderDeadlineService::class)->expireOrderIfOverdue($rent);
            $presenter = app(OrderShowPresenter::class);
            $checkoutPassed = $presenter->checkoutPassed($rent);
            $isUnsuccessful = $presenter->isUnsuccessful($rent);
            $hasReviewed = $rent->home->comments()
                ->where('user_id', $renterId)
                ->whereNull('parent_id')
                ->exists();
            $canReview = $checkoutPassed && ! $isUnsuccessful && ! $hasReviewed;

            return view('dashboard.rents.show-mobile', [
                'rent' => $rent,
                'checkoutPassed' => $checkoutPassed,
                'isUnsuccessful' => $isUnsuccessful,
                'canReview' => $canReview,
                'hasReviewed' => $hasReviewed,
                'canDownloadContract' => $presenter->canDownloadContract($rent),
                'pendingDeadline' => $rent->status === Order::PENDING ? $rent->getPendingDeadline() : null,
                'paymentDeadline' => $rent->status === Order::AWAITING_PAYMENT ? $rent->getPaymentDeadline() : null,
                'canRenterCancel' => $presenter->canRenterCancel($rent),
                'canRenterPay' => $presenter->canRenterPay($rent),
                'invoice' => app(OrderInvoiceService::class)->breakdown($rent),
                'cancelPolicy' => $presenter->cancelPolicyLines($rent->home),
                'tripSteps' => $presenter->tripSteps($rent, $checkoutPassed, $isUnsuccessful),
                'statusContent' => $presenter->statusContentForRenter($rent, $checkoutPassed, $isUnsuccessful),
            ]);
        }

        return view('dashboard.rents.show', compact(['rent']));
    }

    public function contract($order)
    {
        $order = auth()->user()->rents()->findOrFail($order);

        if (! app(OrderShowPresenter::class)->canDownloadContract($order)) {
            abort(403);
        }

        return $order->getContractPDF()->download("{$order->id}-contract.pdf");
    }

    public function discount($order, RentDiscountRequest $request)
    {
        $order = auth()->user()->rents()->where('status', Order::AWAITING_PAYMENT)->findOrFail($order);

        /** @var ?Discount $discount */
        $discount = auth()->user()->discounts()
            ->wherePivot('is_used', false)
            ->where('expired_at', '>', now())
            ->where('code', $request->get('code'))
            ->first();

        if (! $discount) {
            throw ValidationException::withMessages([
                'code' => __('validation.exists', ['attribute' => __('title.code')]),
            ]);
        }

        try {
            DB::beginTransaction();

            $order->update([
                'discount_id' => $discount->id,
                'discount' => $discount->calc($order->price),
            ]);

            DB::commit();

            return response()->json([
                'discount' => $order->discount,
                'payable_price' => $order->payable_price,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops'),
            ]);
        }
    }

    public function pay($order)
    {
        $order = auth()->user()->rents()
            ->where('status', Order::AWAITING_PAYMENT)
            ->findOrFail($order);

        $order = app(OrderDeadlineService::class)->expireOrderIfOverdue($order);

        if (! app(OrderShowPresenter::class)->canRenterPay($order)) {
            return redirect()->back()->with('danger', __('title.expired_guest_non_payment'));
        }

        // اگر قبلاً پرداخت شده باشد، اجازه پرداخت مجدد نده
        if ($order->paid_at) {
            return redirect()->back()->with('info', __('text.already_paid'));
        }

        try {
            DB::beginTransaction();

            $transaction = auth()->user()->transactions()->create([
                'price' => $order->payable_price,
                'description' => 'پرداخت سفارش',
                'gateway' => Transaction::ZARINPAL,
                'status' => Transaction::IN_PROCESS,
                'type' => Transaction::PURCHASE
            ]);

            $order->attachTransaction($transaction);

            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }

        return redirect($transaction->pay());
    }

    public function cancel($order)
    {
        $order = auth()->user()->rents()->findOrFail($order);

        $order = app(OrderDeadlineService::class)->expireOrderIfOverdue($order);

        if (! app(OrderShowPresenter::class)->canRenterCancel($order)) {
            return redirect()->back()->with('danger', __('text.time_expired'));
        }

        try {
            DB::beginTransaction();

            $order->cancel();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.cancel_reserve'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
