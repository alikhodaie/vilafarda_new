<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;

class LocationController extends Controller
{
    /**
     * لیست استان‌ها برای اپ موبایل / کلاینت‌های بیرونی (ایران مرکزی دیتابیس provinces).
     */
    public function provinces()
    {
        return response()->json(
            Province::query()->select('id', 'name')->orderBy('name')->get()
        );
    }

    public function cities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($cities);
    }
}

