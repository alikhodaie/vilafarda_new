<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\LandingPage;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show(Request $request, LandingPage $landingPage)
    {
        if (! $landingPage->is_active) {
            abort(404);
        }

        $landingPage->load(['province', 'city']);

        $isTodayPrice = ($request->filled('sort') && $request->get('sort') === 'open_now')
            || ($request->filled('filter') && $request->get('filter') === 'off');

        $homes = Home::query()
            ->active()
            ->withCount(['sleepPlaces' => function ($query) {
                $query->where('is_share', false);
            }])
            ->tap(fn ($query) => $landingPage->applyHomeFilters($query))
            ->latest()
            ->paginate(6)
            ->appends($request->except('page'));

        foreach ($homes as $home) {
            $home->show_price = $home->getPriceFormatted($isTodayPrice, false).' '.__('title.toman');
            $home->cover_path = $home->cover_path;
            $home->link = $home->link;
        }

        $viewData = compact('landingPage', 'homes', 'isTodayPrice');

        if ($request->is_mobile ?? false) {
            return view('main.landing-pages.show-mobile', $viewData);
        }

        return view('main.landing-pages.show', $viewData);
    }
}
