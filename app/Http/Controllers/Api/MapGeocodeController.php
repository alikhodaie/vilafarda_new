<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MapGeocodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapGeocodeController extends Controller
{
    public function reverse(Request $request, MapGeocodeService $geocoder): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $address = $geocoder->reverse(
            (float) $validated['lat'],
            (float) $validated['lng']
        );

        return response()->json([
            'address' => $address,
            'display_name' => $address,
        ]);
    }
}
