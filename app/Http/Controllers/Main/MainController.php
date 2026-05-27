<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Consultant;
use App\Models\FAQ;
use App\Models\Home;
use App\Models\Order;
use App\Models\Province;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\HomeIndexSectionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $consultants = Consultant::getFromCache();
        $sleep_place = function ($query) {
            $query->where('is_share', false);
        };

        $open_tomorrow = Home::query()->active()->openTomorrow()
            ->withCount(['sleepPlaces' => $sleep_place])->latest()->take(6)->get();

        $last_homes = Home::query()->active()->withCount(['sleepPlaces' => $sleep_place])->latest()->take(6)->get();
        $popular_homes = Home::query()->active()->withCount(['sleepPlaces' => $sleep_place])->orderByDesc('fake_score')->take(6)->get();
        $cheap_homes = Home::query()->active()->withCount(['sleepPlaces' => $sleep_place])->orderBy('week_price')->take(6)->get();
        $expensive_homes = Home::query()->active()->withCount(['sleepPlaces' => $sleep_place])->orderByDesc('week_price')->take(6)->get();
        $off_homes = Home::query()
            ->active()
            ->withCount(['sleepPlaces' => $sleep_place])
            ->lastMinuteOffAvailable()
            ->limit(6)
            ->get();

        $cities = indexPageCities();
        $slider = indexPageSlider();
        $bannerType = indexBannerType();

        $articles = Article::query()->with(['author', 'categories'])->latest()->take(3)->get();
        $comments = Comment::query()->with(['user', 'commentable'])->whereHas('user')->active()->latest()->take(6)->get();

        if ($request->is_mobile ?? false) {
            $suggestionCategories = HomeIndexSectionService::suggestionCategories();

            return view('main.index-mobile', compact(
                'cities',
                'slider',
                'bannerType',
                'last_homes',
                'open_tomorrow',
                'off_homes',
                'popular_homes',
                'cheap_homes',
                'expensive_homes',
                'comments',
                'suggestionCategories'
            ) + [
                'showOpenTomorrow' => HomeIndexSectionService::hasOpenTomorrowHomes(),
                'showOffHomes' => HomeIndexSectionService::hasOffHomes(),
                'offCities' => HomeIndexSectionService::offCities(),
                'offHomesInitial' => HomeIndexSectionService::offHomes(null, 10),
                'showSuggestions' => count($suggestionCategories) > 0,
                'showConsultants' => $consultants->isNotEmpty(),
                'showArticles' => $articles->isNotEmpty(),
                'showComments' => $comments->isNotEmpty(),
            ]);
        }

        return view('main.index', compact([
            'slider',
            'bannerType',
            'cities',
            'consultants',
            'open_tomorrow',
            'off_homes',
            'last_homes',
            'popular_homes',
            'cheap_homes',
            'expensive_homes',
            'articles',
            'comments',
        ]));
    }

    public function privacy(Request $request)
    {
        $articles = Article::query()->with(['author', 'categories'])->latest()->take(3)->get();

        if ($request->is_mobile ?? false) {
            return view('main.privacy-mobile', compact('articles'));
        }

        return view('main.privacy', compact('articles'));
    }

    public function contactUs(Request $request)
    {
        $articles = Article::query()->with(['author', 'categories'])->latest()->take(3)->get();

        if ($request->is_mobile ?? false) {
            return view('main.contact-us-mobile', compact('articles'));
        }

        return view('main.contact-us', compact('articles'));
    }

    public function submitHome(Request $request)
    {
        if ($request->is_mobile ?? false) {
            return view('main.submit-home-mobile');
        }

        return view('main.submit-home');
    }

    public function faq(Request $request)
    {
        $categories = FAQ::getFromCache();

        if (request()->filled('search')){
            $categories = $categories->map(function ($category){
                $category->faq = $category->faq->filter(function ($faq){
                    return Str::contains($faq->question, request()->get('search')) || Str::contains($faq->answer, request()->get('search'));
                });

                return $category;
            });
        }

        if ($request->is_mobile ?? false) {
            return view('main.faq-mobile', compact('categories'));
        }

        return view('main.faq', compact('categories'));
    }

    public function aboutUs(Request $request)
    {
        $articles = Article::query()->with(['author', 'categories'])->latest()->take(3)->get();

        if ($request->is_mobile ?? false) {
            return view('main.about-us-mobile', compact('articles'));
        }

        return view('main.about-us', compact('articles'));
    }

    public function addToHomeIos()
    {
        return view('main.add-to-home-ios-mobile');
    }

    public function addToHomeAndroid()
    {
        return view('main.add-to-home-android-mobile');
    }

    public function callBack(Request $request)
    {
        $result = Transaction::checkPayment($request);

        $status = false;
        $transaction = $result['transaction'];
        $success_payment = $result['success_payment'];

        if ($transaction && $success_payment) {
            $status = $transaction->verify();
        }

        if ($status) {
            $transaction->process();
        }
        else if ($transaction){
            $transaction->update(['status' => Transaction::FAILED]);
        }

        // دریافت اولین order مرتبط با transaction
        $order = null;
        if ($transaction) {
            $transaction->load('orders');
            $order = $transaction->orders->first();
        }

        return view('main.call-back', compact(['status', 'transaction', 'order']));
    }

    public function provinces(Request $request)
    {
        if (request()->ajax()){
            $provinces = collect(Province::getFromCache()->toArray());
            if ($request->filled('has_home')){
                $provinces = $provinces->where('homes_count', '!=', 0);
                $provinces = $provinces->map(function ($province){
                    $province['cities'] = collect($province['cities'])->where('homes_count', '!=', 0);

                    return $province;
                });
            }

            return response()->json($provinces);
        }

        abort(Response::HTTP_NOT_FOUND);
    }
}
