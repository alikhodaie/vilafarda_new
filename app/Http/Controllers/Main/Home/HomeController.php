<?php

namespace App\Http\Controllers\Main\Home;

use App\Classes\Error;
use App\Services\HomeSimilarHomesService;
use App\Services\HomeSmartSearchService;
use App\Services\HomeStatisticsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\ReserveRequest;
use App\Models\Home;
use App\Models\Order;
use App\Models\Province;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (($request->filled('name') || $request->filled('search')) && ! $request->has('q')) {
            $legacy = trim((string) ($request->get('name') ?: $request->get('search')));
            if ($legacy !== '') {
                $terms = preg_split('/[\s,،]+/u', $legacy, -1, PREG_SPLIT_NO_EMPTY) ?: [$legacy];
                $terms = array_values(array_filter(array_map('trim', $terms)));
                if ($terms !== []) {
                    return redirect()->route('main.homes.index', array_merge(
                        $request->except(['name', 'search', 'page']),
                        ['q' => $terms]
                    ));
                }
            }
        }

        $is_today_price = ($request->filled('sort') && $request->get('sort') === 'open_now') ||
            ($request->filled('filter') && $request->get('filter') === 'off');

        $province = ($request->filled('province'))
            ? Province::query()->findOrFail($request->get('province')): null;

        $homes = Home::query()->active()->withCount(['sleepPlaces' => function ($query) {
            $query->where('is_share', false);
        }])
            ->search()->latest()->paginate(6)->appends($request->all());

        // تنظیم متغیرهای مورد نیاز برای هر home
        foreach ($homes as $home){
            $home->show_price = $home->getPriceFormatted($is_today_price, false) .' '. __('title.toman');
            $home->cover_path = $home->cover_path;
            $home->link = $home->link;
        }

        $priceBoundsMin = (int) (Home::query()->active()->min('week_price') ?? 0);
        $priceBoundsMax = (int) (Home::query()->active()->max('week_price') ?? 0);
        if ($priceBoundsMax <= $priceBoundsMin) {
            $priceBoundsMax = $priceBoundsMin + 1_000_000;
        }

        if ($request->is_mobile ?? false) {
            $provinceMapCenters = Province::query()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->mapWithKeys(fn (Province $p) => [(string) $p->id => $p->mapViewConfig()]);

            return view('main.homes.index-mobile', compact(
                'homes',
                'is_today_price',
                'priceBoundsMin',
                'priceBoundsMax',
                'provinceMapCenters'
            ));
        }
        $min = $priceBoundsMin;
        $max = $priceBoundsMax;

        foreach ($homes as $home){
            $home->show_price = $home->price($is_today_price) .' '. __('title.toman');
            $home->cover_path = $home->cover_path;
            $home->link = $home->link;
        }

        return view('main.homes.index', compact(['homes', 'min', 'max', 'is_today_price', 'province']));
    }

    public function show(Request $request, Home $home)
    {
        $home = Home::query()->with([
            'user', 'options', 'variables', 'province', 'city', 'images', 'sleepPlaces', 'safeties', 'healths', 'custom_dates',
            'activeComments' => function ($query) {
                return $query->parents();
            },
            'activeComments.activeChildren',
            'activeComments.activeChildren.user',
            'activeComments.user'
        ])
        ->active()
        ->findOrFail($home->id);

        app(HomeStatisticsService::class)->recordView($home);

        $similarHomeGroups = app(HomeSimilarHomesService::class)->groupsFor($home);
        $similarCategories = $similarHomeGroups['categories'];
        $similarHomesByGroup = $similarHomeGroups['homes'];

        if ($request->is_mobile ?? false) {
            $ratingsSummary = app(\App\Services\HomeRatingsSummaryService::class)->forHome($home);

            return view('main.homes.show_mobile', compact('home', 'similarCategories', 'similarHomesByGroup', 'ratingsSummary'));
        }

       // if user was desktop user
        return view('main.homes.show', compact('home', 'similarCategories', 'similarHomesByGroup'));
    }

    public function trackClick(Home $home)
    {
        $home = Home::query()->active()->findOrFail($home->id);

        app(HomeStatisticsService::class)->recordClick($home);

        return redirect()->route('main.homes.show', $home);
    }

    public function reserve(ReserveRequest $request, Home $home)
    {
        $home = Home::query()->active()->findOrFail($home->id);

        if ($home->user_id === auth()->id()){
            throw ValidationException::withMessages([
                'user' => 'نمی توانید ملک خود را رزرو کنید'
            ]);
        }

        $start = Carbon::parse($request->get('start_date'));
        $end = Carbon::parse($request->get('end_date'))->subDay();

        $has_order_in_same_date = auth()->user()->rents()
            ->where('home_id', $home->id)
            ->whereIn('status', [Order::PENDING, Order::AWAITING_PAYMENT])
            ->get()
            ->filter(function ($order) use ($start, $end){
                $period = CarbonPeriod::create($order->start_at, $order->end_at);

                if ($period->contains($start) || $period->contains($end))
                {
                    return $order;
                }
            })
            ->isNotEmpty();

        if ($has_order_in_same_date)
        {
            throw ValidationException::withMessages([
                'user' => 'نمی‌توانید تاریخی که درخواست داده‌اید را دوباره انتخاب کنید'
            ]);
        }

        foreach ($home->disable_dates as $date){
            if (in_array($date, $request->get('date'))){
                throw ValidationException::withMessages([
                    'date' => __('validation.in', ['attribute' => __('title.date')])
                ]);
            }
        }

        try {
            DB::beginTransaction();

            $guest = ($request->get('guests') > $home->main_guest) ? $home->main_guest: $request->get('guests');
            $extra_guest = ($request->get('guests') > $home->main_guest) ? $request->get('guests') - $home->main_guest: 0;

            $order = auth()->user()->rent($home, $start, $end, $guest, $extra_guest);

            $message = __('text.success.reserve');
            if ($order->status === Order::AWAITING_PAYMENT){
                $message = __('text.success.reserve_payment');
            }

            DB::commit();
            return redirect()->route('dashboard.rents.show', $order)->with('success', $message);
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function nearby(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius = $request->get('radius', 50); // Default 50km

        if (!$latitude || !$longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Latitude and longitude are required'
            ], 400);
        }

        $homes = Home::query()
            ->active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['province', 'city', 'images'])
            ->get()
            ->map(function ($home) use ($latitude, $longitude, $radius) {
                $distance = $home->getDistance($latitude, $longitude);
                if ($distance !== null && $distance <= $radius) {
                    $home->distance = $distance;
                    return $home;
                }
                return null;
            })
            ->filter()
            ->sortBy('distance')
            ->values()
            ->take(20) // Limit to 20 closest homes
            ->map(function ($home) {
                return [
                    'id' => $home->id,
                    'name' => $home->name,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($home->description ?? ''), 150),
                    'cover_path' => $home->cover_path,
                    'link' => $home->link,
                    'latitude' => $home->latitude,
                    'longitude' => $home->longitude,
                    ...$home->guestRatingPayload(),
                    'province' => $home->province->name ?? '',
                    'city' => $home->city->name ?? '',
                    'price' => $home->price(false),
                    'distance' => round($home->distance, 1),
                    'week_price' => $home->week_price,
                ];
            });

        return response()->json([
            'success' => true,
            'homes' => $homes
        ]);
    }

    public function mapData(Request $request)
    {
        $isTodayPrice = ($request->filled('sort') && $request->get('sort') === 'open_now') ||
            ($request->filled('filter') && $request->get('filter') === 'off');

        $query = Home::query()
            ->active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['province', 'city'])
            ->withCount([
                'sleepPlaces as bedroom_count' => function ($q) {
                    $q->where('is_share', false);
                },
                'orders as successful_bookings_count' => function ($q) {
                    $q->where('status', Order::DONE);
                },
            ]);

        $query = $this->applyMobileMapFilters($query, $request);
        $query->search();

        $homes = $query->limit(500)->get();

        $minPrice = $homes->min(fn ($home) => $home->price($isTodayPrice)) ?: 0;

        $payload = $homes->map(function ($home) use ($isTodayPrice) {
            $price = (int) $home->price($isTodayPrice);
            $maxGuests = (int) $home->main_guest + (int) $home->extra_guest;

            return [
                'id' => $home->id,
                'name' => $home->name,
                'cover_path' => $home->cover_path,
                'link' => $home->link,
                'latitude' => (float) $home->latitude,
                'longitude' => (float) $home->longitude,
                ...$home->guestRatingPayload(),
                'province' => $home->province->name ?? '',
                'city' => $home->city->name ?? '',
                'price' => $price,
                'price_label' => $home->getPriceFormatted($isTodayPrice, false) . ' ' . __('title.toman'),
                'bedroom_count' => (int) ($home->bedroom_count ?? 0),
                'infrastructure_meter' => (int) ($home->infrastructure_meter ?? 0),
                'main_guest' => (int) $home->main_guest,
                'max_guests' => $maxGuests,
                'successful_bookings_count' => (int) ($home->successful_bookings_count ?? 0),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'count' => $payload->count(),
            'min_price' => (int) $minPrice,
            'homes' => $payload,
        ]);
    }

    private function applyMobileMapFilters(Builder $query, Request $request): Builder
    {
        $searchTerms = $request->get('q', []);
        if (! is_array($searchTerms)) {
            $searchTerms = $searchTerms ? [(string) $searchTerms] : [];
        }
        $searchTerms = array_values(array_filter(array_map(
            fn ($t) => trim((string) $t),
            $searchTerms
        ), fn ($t) => $t !== ''));

        if ($searchTerms !== []) {
            app(HomeSmartSearchService::class)->applySearchTerms($query, $searchTerms);
        } else {
            $searchTerm = $request->filled('name')
                ? trim((string) $request->get('name'))
                : ($request->filled('search') ? trim((string) $request->get('search')) : '');

            if ($searchTerm !== '') {
                app(HomeSmartSearchService::class)->applySearchTerm($query, $searchTerm);
            }
        }

        $features = $request->get('features', []);
        if (is_array($features) && $features !== []) {
            app(HomeSmartSearchService::class)->applyFeatureSlugs($query, $features);
        }

        if ($request->filled('guest_count')) {
            $query->whereRaw('(main_guest + COALESCE(extra_guest, 0)) >= ?', [(int) $request->get('guest_count')]);
        }

        if ($request->filled('min_price')) {
            $query->where('week_price', '>=', (int) $request->get('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('week_price', '<=', (int) $request->get('max_price'));
        }

        if ($request->filled('type')) {
            $typeMap = [
                'villa' => Home::VILAIY,
                'apartment' => Home::APARTEMAN,
                'house' => Home::KHANE_ROOSTANIY,
            ];
            $type = $typeMap[$request->get('type')] ?? $request->get('type');
            $query->where('type', $type);
        }

        return $query;
    }
}
