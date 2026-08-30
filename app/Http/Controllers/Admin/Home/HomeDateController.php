<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Date\DestroyDateRequest;
use App\Http\Requests\Admin\Home\Date\StoreDateRequest;
use App\Http\Requests\Admin\Home\Date\UpdateDateFastReserveRequest;
use App\Models\Home;
use App\Support\HomeSlug;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeDateController extends Controller
{
    public function show(Request $request, $home)
    {
        $home = Home::query()->with(['custom_dates'])->whereKey(HomeSlug::resolveKeyForQuery($home))->firstOrFail();
        $this->authorize('showDate', $home);

        if ($request->is_mobile ?? false) {
            return view('admin.homes.date-mobile', compact(['home']));
        }

        return view('admin.homes.date', compact(['home']));
    }

    public function store(StoreDateRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->unsetRelation('custom_dates');

            $basePrices = [];
            foreach (['week_price', 'wed_price', 'thu_price', 'fri_price'] as $field) {
                if ($request->filled($field)) {
                    $basePrices[$field] = (int) $request->get($field);
                }
            }

            if ($basePrices !== []) {
                $home->update($basePrices);
            }

            $isActive = $request->get('is_active') !== 'false';
            $minNights = max(1, (int) $request->get('min_nights', 1));

            if ($request->input('calendar_action') === 'reset_base') {
                $home->resetCustomDatesToBasePrice($request->get('dates', []));
                $isActive = false;
            } else {
                $price = (int) $request->get('price', 0);

                foreach ($request->get('dates') as $date) {
                    $home->upsertCustomDate($date, $price, $minNights, $isActive);
                }
            }

            DB::commit();

            $home->unsetRelation('custom_dates');
            $home->refresh();
            $warnings = [];

            if ($isActive && $minNights > 1) {
                foreach ($request->get('dates') as $date) {
                    $explanation = $home->explainMinNightsLimitation($date, $minNights);

                    if ($explanation) {
                        $warnings[] = $explanation['message'];
                    }
                }
            }

            $redirect = redirect()->back()->with('success', __('text.success.update_calendar'));

            if ($warnings !== []) {
                $redirect->with('warning', __('text.min_nights_saved_with_limits')."\n".implode("\n", $warnings));
            }

            return $redirect;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(DestroyDateRequest $request, Home $home)
    {
        $custom_date = $home->custom_dates()->where('date', $request->get('date'))->firstOrFail();

        try {
            DB::beginTransaction();

            $custom_date->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_calendar'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function updateFastReserve(Home $home, UpdateDateFastReserveRequest $request)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'fast_reserve_start_at' => $request->get('min_date'),
                'fast_reserve_end_at' => $request->get('max_date'),
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update_calendar'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
