<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Home\DestroyCustomDateRequest;
use App\Http\Requests\Dashboard\Home\StoreCustomDateRequest;
use App\Http\Requests\Dashboard\Home\UpdateAddressRequest;
use App\Http\Requests\Dashboard\Home\UpdateCoverRequest;
use App\Http\Requests\Dashboard\Home\UpdateFastReserveRequest;
use App\Http\Requests\Dashboard\Home\UpdateOptionRequest;
use App\Http\Requests\Dashboard\Home\UpdatePriceRequest;
use App\Models\Home;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeCustomController extends Controller
{
    public function showDate(Request $request, $home)
    {
        $home = auth()->user()->findHomeOrFail($home, ['custom_dates']);

        if ($request->is_mobile ?? false) {
            return view('dashboard.homes.custom.date-mobile', compact('home'));
        }

        return view('dashboard.homes.custom.date', compact('home'));
    }

    public function storeDate(StoreCustomDateRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $price = $request->get('price');
            $is_active = $request->get('is_active');

            if ($is_active === 'false')
            {
                $price = 0;
            }

            $minNights = max(1, (int) $request->get('min_nights', 1));

            foreach ($request->get('dates') as $date) {
                $home->upsertCustomDate($date, (int) $price, $minNights);
            }

            DB::commit();

            $home->refresh();
            $warnings = [];

            if ($is_active !== 'false' && $minNights > 1) {
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

    public function destroyDate(DestroyCustomDateRequest $request, Home $home)
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

    public function updateFastReserve(Home $home, UpdateFastReserveRequest $request)
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

    public function showPrice($home)
    {
        $home = auth()->user()->findHomeOrFail($home);

        return view('dashboard.homes.custom.price', compact(['home']));
    }

    public function updatePrice(UpdatePriceRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'daily_off_amount' => $request->get('daily_off_amount'),
                'daily_off' => $request->get('daily_off'),
                'week_price' => $request->get('week_price'),
                'off'       => $request->get('off'),
                'wed_price' => $request->get('wed_price'),
                'thu_price' => $request->get('thu_price'),
                'fri_price' => $request->get('fri_price'),
                'price_per_surplus' => $request->get('price_per_surplus'),
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update home', ['name' => $home->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function showAddress($home)
    {
        $home = auth()->user()->findHomeOrFail($home);

        return view('dashboard.homes.custom.address', compact(['home']));
    }

    public function updateAddress(UpdateAddressRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'province_id' => $request->get('province'),
                'city_id' => $request->get('city'),
                'address' => $request->get('address'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
            ]);

            if ($home->wasChanged()){
                $home->update([
                    'status' => Home::PENDING
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update home', ['name' => $home->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function showOption($home)
    {
        $home = auth()->user()->findHomeOrFail($home);

        return view('dashboard.homes.custom.option', compact(['home']));
    }

    public function updateOption(UpdateOptionRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $must_pending = false;

            if ($home->options->pluck('id')->toArray() != $request->get('options')){
                $must_pending = true;
            }

            if ($home->healths->pluck('id')->toArray() != $request->get('healths')){
                $must_pending = true;
            }

            $safeties = $home->safeties->map(function ($item){
                return  [
                    'id' => "$item->id",
                    'description' => $item->description,
                ];
            })->toArray();

            if (empty($safeties)){
                foreach ($request->get('safeties') as $safety) {
                    $safeties[] = ['description' => $safety['description']];
                }
            }

            if ($safeties != $request->get('safeties')){
                $must_pending = true;
            }

            $home->options()->sync($request->get('options'));
            $home->healths()->sync($request->get('healths'));
            $home->safeties()->detach();
            foreach ($request->get('safeties') as $safety) {
                if (isset($safety['id'])){
                    $home->safeties()->attach($safety['id'], ['description' => $safety['description']]);
                }
            }

            $home->update([
                'more_health' => $request->get('more_health'),
                'more_safety' => $request->get('more_safety'),
            ]);

            if ($must_pending || $home->wasChanged()){
                $home->update([
                    'status' => Home::PENDING
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', __('text.success.update home', ['name' => $home->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function showImage($home, Request $request)
    {
        $home = auth()->user()->findHomeOrFail($home, ['images']);
        if ($home->cover){
            $home->cover = [
                'image_path' => $home->cover_path
            ];
        }
        $home->images->map(function ($image){
            $image->path = $image->image_path;
            return $image;
        });

        return view('dashboard.homes.custom.image', compact(['home']));
    }

    public function updateImage(UpdateCoverRequest $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->updateCover($request->file('file'));

                $home->update([
                    'status' => Home::PENDING
                ]);


            DB::commit();
            if ($request->ajax()){
                return true;
            }
            return redirect()->back()->with('success', __('text.success.update home', ['name' => $home->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function deleteImage(Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->deleteCover();

                $home->update([
                    'status' => Home::PENDING
                ]);

            DB::commit();
            if ($request->ajax()){
                return true;
            }
            return redirect()->back()->with('success', __('text.success.update home', ['name' => $home->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
