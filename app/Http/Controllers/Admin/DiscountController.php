<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discount\StoreDiscountRequest;
use App\Http\Requests\Admin\Discount\UpdateDiscountRequest;
use App\Jobs\SendDiscountSmsJob;
use App\Models\Discount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index()
    {
        $this->authorize('index', Discount::class);

        $discounts = Discount::query()->latest()->paginate(10);

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $this->authorize('create', Discount::class);

        return view('admin.discounts.create');
    }

    public function store(StoreDiscountRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = [
                'code' => $request->get('code'),
                'type' => $request->get('type'),
                'user_type' => $request->get('user_type'),
                'amount' => $request->get('amount'),
                'expired_at' => Carbon::parse($request->get('expired_at'))->endOfDay(),
            ];

            if ($request->filled('sms_type')) {
                $data['sms_data'] = [
                    'type' => $request->get('sms_type'),
                    'sms' => $request->get('sms'),
                ];
            }

            /** @var Discount $discount */
            $discount = Discount::query()->create($data)
                ->attachUsers($request);

            if ($discount->users()->exists()) {
                dispatch(new SendDiscountSmsJob($discount));
            }

            DB::commit();
            return redirect()->route('admin.discounts.index')->with('success', __('text.success.create discount'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function edit(Discount $discount)
    {
        $this->authorize('update', $discount);

        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Discount $discount, UpdateDiscountRequest $request)
    {
        try {
            DB::beginTransaction();

            $discount->update([
                'type' => $request->get('type'),
                'amount' => $request->get('amount'),
                'expired_at' => Carbon::parse($request->get('expired_at'))->endOfDay(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update discount'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(Discount $discount)
    {
        $this->authorize('delete', $discount);

        try {
            DB::beginTransaction();

            $discount->delete();

            DB::commit();
            return redirect()->route('admin.discounts.index')->with('success', __('text.success.delete discount'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
