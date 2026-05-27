<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep10Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep11Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep12Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep13Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep14Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep1Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep2Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep3Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep4Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep5Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep6Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep7Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep8Request;
use App\Http\Requests\Dashboard\Home\Validate\StoreStep9Request;
use App\Models\Home;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HomeValidateController extends Controller
{
    public function storeStep1(StoreStep1Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'province_id' => $request->get('province'),
                'city_id' => $request->get('city'),
                'address' => $request->get('address'),
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep2(StoreStep2Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude')
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep3(Home $home)
    {
        return true;
    }

    public function storeStep4(StoreStep4Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep5(StoreStep5Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'atmosphere' => $request->get('atmosphere'),
                'type' => $request->get('type'),
                'area' => $request->get('area')
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep6(StoreStep6Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'main_guest' => $request->get('main_guest'),
                'extra_guest' => $request->get('extra_guest'),
                'yard_meter' => $request->get('yard'),
                'infrastructure_meter' => $request->get('infrastructure')
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep7(StoreStep7Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update(['sleep_area_description' => $request->get('sleep_area_description')]);

            $home->sleepPlaces()->delete();

            if ($request->filled('share_room')){
                $room = $request->get('share_room');

                $home->sleepPlaces()->updateOrCreate(['is_share' => true], [
                    'single_bed' => $room['single_bed'],
                    'double_bed' => $room['double_bed'],
                    'traditional_bed' => $room['traditional_bed'],
                    'more' => $room['more'],
                ]);
            }

            foreach ($request->get('rooms') as $room){
                $home->sleepPlaces()->updateOrCreate(['id' => $room['id'], 'is_share' => false], [
                    'single_bed' => $room['single_bed'],
                    'double_bed' => $room['double_bed'],
                    'traditional_bed' => $room['traditional_bed'],
                    'more' => $room['more'],
                ]);
            }

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep8(StoreStep8Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->options()->sync($request->get('options'));

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep9(StoreStep9Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update(['more_health' => $request->get('more')]);
            $home->healths()->sync($request->get('healths'));

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep10(StoreStep10Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'week_price' => $request->get('week_price'),
                'wed_price' => $request->get('wed_price'),
                'thu_price' => $request->get('thu_price'),
                'fri_price' => $request->get('fri_price'),
                'price_per_surplus' => $request->get('price_per_surplus')
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep11(StoreStep11Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update([
                'off' => $request->get('off'),
                'daily_off' => $request->get('daily_off'),
                'daily_off_amount' => $request->get('daily_off_amount'),
            ]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep12(StoreStep12Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update(['more_safety' => $request->get('more')]);

            $home->safeties()->detach();
            foreach ($request->get('safeties') as $safety) {
                $home->safeties()->attach($safety['id'], ['description' => $safety['description']]);
            }

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep13(StoreStep13Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update(['rules' => $request->rules]);
            foreach ($request->get('variables') as $variable_id => $value){
                $home->updateVariable($variable_id, $value);
            }

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function storeStep14(StoreStep14Request $request, Home $home)
    {
        try {
            DB::beginTransaction();

            $home->update(['reject_policy' => $request->get('reject_policy')]);

            DB::commit();
            return true;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }
}
