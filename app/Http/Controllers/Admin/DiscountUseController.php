<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discount\UpdateDiscountUseRequest;
use App\Models\Discount;
use Exception;
use Illuminate\Support\Facades\DB;

class DiscountUseController extends Controller
{
    public function edit(Discount $discount)
    {
        $this->authorize('update', $discount);

        return view('admin.discounts.use', compact('discount'));
    }

    public function update(Discount $discount, UpdateDiscountUseRequest $request)
    {
        try {
            DB::beginTransaction();

            $discount->increment('count_usage');
            $discount->orders()->whereNotNull('discount_id')->where('renter_id', $request->get('user'))->update([
                'discount_id' => null,
                'discount' => 0
            ]);
            $discount->users()->updateExistingPivot($request->get('user'), ['is_used' => true]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.user now marked as used discount'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
