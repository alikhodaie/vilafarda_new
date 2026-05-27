<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Rent\StoreHomeReviewRequest;
use App\Models\Order;
use App\Services\RentReviewService;
use App\Support\HomeReviewCriteria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentReviewController extends Controller
{
    public function create(Request $request, Order $order)
    {
        $order = auth()->user()->rents()
            ->with(['home', 'owner'])
            ->findOrFail($order->getKey());

        $renterId = (int) auth()->id();
        $reviewService = app(RentReviewService::class);

        if (! $reviewService->canSubmit($order, $renterId)) {
            if ($reviewService->hasReviewed($order->home, $renterId)) {
                return redirect()
                    ->route('dashboard.rents.index', ['tab' => Order::TRIP_TAB_FINISHED])
                    ->with('info', 'شما قبلاً برای این اقامت نظر ثبت کرده‌اید.');
            }

            abort(403);
        }

        $checkinJ = $order->checkin ? persianDate($order->checkin->copy()) : null;
        $host = $order->owner;
        $hostName = $host?->first_name ?: ($host?->full_name ?? 'میزبان');

        return view('dashboard.rents.review-mobile', [
            'rent' => $order,
            'criteria' => HomeReviewCriteria::labels(),
            'hostName' => $hostName,
            'travelDate' => $checkinJ ? persianNumber($checkinJ->format('j F Y')) : null,
        ]);
    }

    public function store(StoreHomeReviewRequest $request, Order $order)
    {
        $order = auth()->user()->rents()
            ->with('home')
            ->findOrFail($order->getKey());

        $user = auth()->user();
        $scores = $request->input('scores', []);
        $averageScore = HomeReviewCriteria::average($scores);

        try {
            DB::beginTransaction();

            $order->home->addComment(
                trim((string) $request->input('comment', '')),
                $user->email,
                $user->full_name,
                null,
                $averageScore,
                $user->id,
                $scores
            );

            DB::commit();

            return redirect()
                ->route('dashboard.rents.index', ['tab' => Order::TRIP_TAB_FINISHED])
                ->with('success', __('text.success.store_comment'));
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()
                ->back()
                ->withInput()
                ->with('danger', __('text.whoops'));
        }
    }
}
