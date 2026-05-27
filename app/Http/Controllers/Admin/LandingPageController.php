<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LandingPage\StoreLandingPageRequest;
use App\Http\Requests\Admin\LandingPage\UpdateLandingPageRequest;
use App\Models\Home;
use App\Models\LandingPage;
use App\Services\LandingPageFilterParser;
use App\Services\SitemapService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', LandingPage::class);

        $landingPages = LandingPage::query()
            ->search()
            ->with(['province', 'city'])
            ->orderBy('sort')
            ->latest('id')
            ->paginate(15)
            ->appends($request->all());

        return view('admin.landing-pages.index', compact('landingPages'));
    }

    public function create()
    {
        $this->authorize('create', LandingPage::class);

        $homeTypes = Home::TYPES;

        return view('admin.landing-pages.create', compact('homeTypes'));
    }

    public function store(StoreLandingPageRequest $request)
    {
        try {
            DB::beginTransaction();

            LandingPage::query()->create($this->payloadFromRequest($request));

            DB::commit();
            SitemapService::forgetCache();

            return redirect()
                ->route('admin.landing-pages.index')
                ->with('success', __('text.success.create_landing_page', ['title' => $request->get('title')]));
        } catch (InvalidArgumentException $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('danger', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->withInput()->with('danger', __('text.whoops'));
        }
    }

    public function edit(LandingPage $landingPage)
    {
        $this->authorize('update', $landingPage);

        $landingPage->load(['province', 'city']);
        $homeTypes = Home::TYPES;

        return view('admin.landing-pages.edit', compact('landingPage', 'homeTypes'));
    }

    public function update(UpdateLandingPageRequest $request, LandingPage $landingPage)
    {
        try {
            DB::beginTransaction();

            $landingPage->update($this->payloadFromRequest($request));

            DB::commit();
            SitemapService::forgetCache();

            return redirect()
                ->route('admin.landing-pages.index')
                ->with('success', __('text.success.update_landing_page', ['title' => $landingPage->title]));
        } catch (InvalidArgumentException $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('danger', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->withInput()->with('danger', __('text.whoops'));
        }
    }

    public function destroy(LandingPage $landingPage)
    {
        $this->authorize('delete', $landingPage);

        try {
            $title = $landingPage->title;
            $landingPage->delete();
            SitemapService::forgetCache();

            return redirect()
                ->route('admin.landing-pages.index')
                ->with('success', __('text.success.delete_landing_page', ['title' => $title]));
        } catch (Exception $e) {
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function payloadFromRequest(StoreLandingPageRequest|UpdateLandingPageRequest $request): array
    {
        $filterBundle = $this->resolveFilters($request);

        return [
            'slug' => $request->get('slug'),
            'title' => $request->get('title'),
            'meta_title' => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
            'intro' => $request->get('intro'),
            'filter_source_url' => $filterBundle['filter_source_url'],
            'filters' => $filterBundle['filters'],
            'faq' => $this->normalizeFaq($request->input('faq', [])),
            'province_id' => $filterBundle['province_id'],
            'city_id' => $filterBundle['city_id'],
            'home_type' => $filterBundle['home_type'],
            'city_only' => $request->boolean('city_only'),
            'is_active' => $request->boolean('is_active'),
            'sort' => (int) $request->get('sort', 0),
        ];
    }

    /**
     * @return array{
     *     filters: ?array<string, mixed>,
     *     filter_source_url: ?string,
     *     province_id: ?int,
     *     city_id: ?int,
     *     home_type: ?string
     * }
     */
    private function resolveFilters(StoreLandingPageRequest|UpdateLandingPageRequest $request): array
    {
        $filterSourceUrl = trim((string) $request->get('filter_source_url', ''));
        $parser = app(LandingPageFilterParser::class);

        $filters = [];
        $provinceId = $request->filled('province') ? (int) $request->get('province') : null;
        $cityId = $request->filled('city') ? (int) $request->get('city') : null;
        $homeType = $request->get('home_type') ?: null;

        if ($filterSourceUrl !== '') {
            $parsed = $parser->parse($filterSourceUrl);
            $filters = $parsed['filters'];
            $provinceId = $provinceId ?: $parsed['province_id'];
            $cityId = $cityId ?: $parsed['city_id'];
            $homeType = $homeType ?: $parsed['home_type'];
        }

        if ($provinceId) {
            $filters['province'] = (string) $provinceId;
        } else {
            unset($filters['province']);
        }

        if ($cityId) {
            $filters['city'] = (string) $cityId;
        } else {
            unset($filters['city']);
        }

        if ($homeType) {
            $filters['type'] = $homeType;
            unset($filters['types']);
        }

        $filters = array_filter($filters, function ($value) {
            if (is_array($value)) {
                return $value !== [];
            }

            return $value !== null && $value !== '';
        });

        return [
            'filter_source_url' => $filterSourceUrl !== '' ? $filterSourceUrl : null,
            'filters' => $filters !== [] ? $filters : null,
            'province_id' => $provinceId,
            'city_id' => $cityId,
            'home_type' => $homeType,
        ];
    }

    /**
     * @param  mixed  $faq
     * @return array<int, array{question: string, answer: string}>|null
     */
    private function normalizeFaq($faq): ?array
    {
        if (! is_array($faq)) {
            return null;
        }

        $items = array_values(array_filter(array_map(function ($item) {
            if (! is_array($item)) {
                return null;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                return null;
            }

            return compact('question', 'answer');
        }, $faq)));

        return $items === [] ? null : $items;
    }
}
