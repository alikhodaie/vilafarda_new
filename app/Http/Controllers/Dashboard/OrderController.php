<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Order\RejectOrderRequest;
use App\Models\Order;
use App\Services\OrderDeadlineService;
use App\Services\OrderInvoiceService;
use App\Services\OrderShowPresenter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is_mobile ?? false) {
            $hostId = (int) auth()->id();
            $tab = $request->get('tab', Order::HOST_ORDER_TAB_CURRENT);

            if (! in_array($tab, Order::HOST_ORDER_TABS, true)) {
                $tab = Order::HOST_ORDER_TAB_CURRENT;
            }

            $sort = $request->get('sort', 'checkin');
            $counts = Order::hostOrderTabCounts($hostId);

            $orders = auth()->user()->orders()
                ->forHostOrderTab($tab)
                ->hostOrderSort($sort)
                ->with(['home.province', 'home.city', 'renter'])
                ->paginate(10)
                ->appends(['tab' => $tab, 'sort' => $sort]);

            app(OrderDeadlineService::class)->expireCollectionIfOverdue($orders->getCollection());

            return view('dashboard.orders.index-mobile', compact('orders', 'tab', 'counts', 'sort'));
        }

        $orders = auth()->user()->orders()->search()->latest()->paginate(10)->appends($request->all());

        app(OrderDeadlineService::class)->expireCollectionIfOverdue($orders->getCollection());

        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Request $request, $order)
    {
        $order = auth()->user()->orders()
            ->with(['home.province', 'home.city', 'home.custom_dates', 'renter'])
            ->findOrFail($order);

        if ($request->is_mobile ?? false) {
            $order = app(OrderDeadlineService::class)->expireOrderIfOverdue($order);
            $presenter = app(OrderShowPresenter::class);
            $checkoutPassed = $presenter->checkoutPassed($order);
            $isUnsuccessful = $presenter->isUnsuccessful($order);

            return view('dashboard.orders.show-mobile', [
                'order' => $order,
                'checkoutPassed' => $checkoutPassed,
                'isUnsuccessful' => $isUnsuccessful,
                'isCurrent' => $presenter->isCurrentForHost($order),
                'canDownloadContract' => $presenter->canDownloadContract($order),
                'pendingDeadline' => $order->status === Order::PENDING ? $order->getPendingDeadline() : null,
                'paymentDeadline' => $order->status === Order::AWAITING_PAYMENT ? $order->getPaymentDeadline() : null,
                'canHostRespondToPending' => $presenter->canHostRespondToPending($order),
                'invoice' => app(OrderInvoiceService::class)->breakdown($order),
                'hostSettlement' => app(OrderInvoiceService::class)->hostSettlement($order),
                'cancelPolicy' => $presenter->cancelPolicyLines($order->home),
                'tripSteps' => $presenter->tripSteps($order, $checkoutPassed, $isUnsuccessful),
                'statusContent' => $presenter->statusContentForHost($order, $checkoutPassed, $isUnsuccessful),
                'rejectionReason' => $presenter->rejectionReasonSection($order),
                'unsuccessfulProgressPercent' => $isUnsuccessful
                    ? $presenter->unsuccessfulProgressPercent($order)
                    : 0,
            ]);
        }

        return redirect()->route('dashboard.orders.index');
    }

    public function contract($order)
    {
        $order = auth()->user()->orders()->findOrFail($order);

        if (! app(OrderShowPresenter::class)->canDownloadContract($order)) {
            abort(403);
        }

        return $order->getContractPDF()->download("{$order->id}-contract.pdf");
    }

    public function accept($order)
    {
        $order = auth()->user()->orders()->where('status', Order::PENDING)->findOrFail($order);

        $order = app(OrderDeadlineService::class)->expireOrderIfOverdue($order);

        if (! app(OrderShowPresenter::class)->canHostRespondToPending($order)) {
            return redirect()->back()->with('danger', __('title.expired_host_non_approval'));
        }

        try {
            DB::beginTransaction();

            $order->accept();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.accept_reserve'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function reject(RejectOrderRequest $request, $order)
    {
        $order = auth()->user()->orders()->where('status', Order::PENDING)->findOrFail($order);

        $order = app(OrderDeadlineService::class)->expireOrderIfOverdue($order);

        if (! app(OrderShowPresenter::class)->canHostRespondToPending($order)) {
            return redirect()->back()->with('danger', __('title.expired_host_non_approval'));
        }

        try {
            DB::beginTransaction();

            $order->reject($request->validated()['reject_reason']);

            DB::commit();

            return redirect()
                ->route('dashboard.homes.custom.date.show', $order->home_id)
                ->with('success', __('text.success.reject_reserve_update_calendar'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
