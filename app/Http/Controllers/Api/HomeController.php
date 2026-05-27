<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Services\HomeIndexSectionService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function last(Request $request)
    {
        $limit = (int) $request->query('limit', 12);

        return response()->json(
            HomeIndexSectionService::homesForCategory('last', $limit)->values()
        );
    }


    public function province(Request $request)
    {
        return response()->json(indexPageCities());
    }



    public function offCities()
    {
        return response()->json(HomeIndexSectionService::offCities());
    }

    public function off(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $cityId = $request->filled('city_id') ? (int) $request->query('city_id') : null;

        $homes = HomeIndexSectionService::offHomes($cityId > 0 ? $cityId : null, $limit);

        return response()->json($homes->values());
    }

    public function popular(Request $request)
    {
        $limit = (int) $request->query('limit', 12);

        return response()->json(
            HomeIndexSectionService::homesForCategory('popular', $limit)->values()
        );
    }

    public function cheap(Request $request)
    {
        $limit = (int) $request->query('limit', 12);

        return response()->json(
            HomeIndexSectionService::homesForCategory('cheap', $limit)->values()
        );
    }

    public function expensive(Request $request)
    {
        $limit = (int) $request->query('limit', 12);

        return response()->json(
            HomeIndexSectionService::homesForCategory('expensive', $limit)->values()
        );
    }

    public function openTomorrow(Request $request)
    {
        $limit = (int) $request->query('limit', 12);

        return response()->json(
            HomeIndexSectionService::homesForCategory('open-tomorrow', $limit)->values()
        );
    }
}
