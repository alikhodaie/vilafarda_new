<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Home\SaveMobileDraftRequest;
use App\Http\Requests\Dashboard\Home\StoreRequest;
use App\Http\Requests\Dashboard\Home\UpdateHostStatusRequest;
use App\Http\Requests\Dashboard\Home\UpdateRequest;
use App\Models\Home;
use App\Support\UploadValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $homes = auth()->user()->homes()
            ->with(['images' => function ($query) {
                $query->oldest();
            }])
            ->orderByDesc('is_draft')
            ->latest('updated_at')
            ->search()
            ->paginate(10);

        if ($request->is_mobile ?? false) {
            return view('dashboard.homes.index-mobile', compact(['homes']));
        }

        return view('dashboard.homes.index', compact(['homes']));
    }

    public function create(Request $request)
    {
        if ($request->boolean('continue') && $request->filled('home')) {
            $home = auth()->user()->homes()
                ->whereKey((int) $request->input('home'))
                ->where('is_draft', true)
                ->firstOrFail();
        } else {
            $home = auth()->user()->homes()->firstOrCreate(
                ['is_draft' => true],
                ['draft_step' => 1]
            );
        }

        $home->load(['sleepPlaces', 'options', 'healths', 'safeties', 'variables', 'documents']);
        $home->syncDocumentsFromDisk();
        $home->load('documents');

        if (! array_key_exists('type', $home->getAttributes()) || $home->getAttributes()['type'] === null) {
            $home->setAttribute('type', '');
        }

        if ($request->is_mobile ?? false) {
            return view('dashboard.homes.create-mobile', ['home' => $home]);
        }

        if ($home->getRawOriginal('cover')) {
            $home->setAttribute('cover', [
                'image_path' => $home->cover_path,
            ]);
        }
        $home->images->map(function ($image) {
            $image->path = $image->image_path;

            return $image;
        });

        return view('dashboard.homes.create', ['home' => $home]);
    }

    public function saveDraft(SaveMobileDraftRequest $request, Home $home): JsonResponse
    {
        $home = auth()->user()->homes()->whereKey($home->id)->where('is_draft', true)->firstOrFail();

        try {
            DB::beginTransaction();

            $galleryWarnings = [];

            $data = $request->validated();
            $step = (int) ($data['step'] ?? $request->input('step', 0));

            switch ($step) {
                case 1:
                    $stepData = [
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'type' => $data['type'],
                        'main_guest' => $data['main_guest'],
                    ];
                    if ($request->has('yard')) {
                        $stepData['yard_meter'] = $request->get('yard');
                    }
                    if ($request->has('infrastructure')) {
                        $stepData['infrastructure_meter'] = $request->get('infrastructure');
                    }
                    if ($request->has('extra_guest')) {
                        $stepData['extra_guest'] = $request->get('extra_guest');
                    }
                    if ($request->filled('atmosphere')) {
                        $stepData['atmosphere'] = $request->get('atmosphere');
                    }
                    if ($request->filled('area')) {
                        $stepData['area'] = $request->get('area');
                    }
                    $home->update($stepData);
                    break;
                case 2:
                    $cover = $request->file('cover');
                    if ($cover instanceof UploadedFile) {
                        UploadValidation::validateUploadedOrFail($cover, 'cover', 'کاور');
                        $home->addCover($cover);
                    }
                    $galleryWarnings = UploadValidation::appendGalleryImagesBestEffort($home, $request->file('images'), 'تصویر');
                    break;
                case 3:
                    $this->syncHomeSleepRooms($home, $request, true);
                    if ($request->has('sleep_area_description')) {
                        $home->update(['sleep_area_description' => $request->get('sleep_area_description')]);
                    }
                    break;
                case 4:
                    $home->update([
                        'province_id' => $data['province_id'],
                        'city_id' => $data['city_id'],
                        'address' => $data['address'],
                        'latitude' => $data['latitude'] ?? null,
                        'longitude' => $data['longitude'] ?? null,
                    ]);
                    break;
                case 5:
                    $home->update([
                        'week_price' => $data['week_price'],
                        'wed_price' => $data['wed_price'] ?? null,
                        'thu_price' => $data['thu_price'] ?? null,
                        'fri_price' => $data['fri_price'] ?? null,
                        'price_per_surplus' => $data['price_per_surplus'] ?? null,
                        'cleaning_fee' => $data['cleaning_fee'] ?? null,
                    ]);
                    break;
                case 6:
                    $home->update([
                        'off' => $data['off'] ?? 0,
                        'daily_off' => $data['daily_off'] ?? 0,
                        'daily_off_amount' => $data['daily_off_amount'] ?? 0,
                    ]);
                    break;
                case 7:
                    $home->options()->sync($request->input('options', []));
                    break;
                case 8:
                    $this->syncHomeSafeties($home, $request);
                    break;
                case 9:
                    $home->healths()->sync($request->input('healths', []));
                    $home->update(['more_health' => $data['more_health'] ?? null]);
                    break;
                case 10:
                    $home->update([
                        'reject_policy' => $data['reject_policy'] ?? null,
                        'rules' => $data['rules'] ?? null,
                    ]);
                    break;
                case 11:
                    $doc = $request->file('document');
                    if ($doc instanceof UploadedFile) {
                        UploadValidation::validateUploadedOrFail($doc, 'document', 'سند');
                        $home->addDocument($doc);
                    }
                    break;
            }

            $home->update([
                'draft_step' => min($step + 1, 11),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'draft_step' => $home->fresh()->draft_step,
                'warnings' => $galleryWarnings,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return response()->json(['message' => 'خطا در ذخیرهٔ پیش‌نویس.'], 500);
        }
    }

    public function store(StoreRequest $request, Home $home)
    {
        
        try {
            DB::beginTransaction();

            // Update home with form data
            
            $home->update([
                'is_draft' => false,
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'type' => $request->get('type'),
                'main_guest' => $request->get('main_guest'),
                'extra_guest' => $request->input('extra_guest', 0),
                'atmosphere' => $request->input('atmosphere'),
                'area' => $request->input('area'),
                'province_id' => $request->get('province_id'),
                'city_id' => $request->get('city_id'),
                'address' => $request->get('address'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'week_price' => $request->get('week_price'),
                'wed_price' => $request->get('wed_price'),
                'thu_price' => $request->get('thu_price'),
                'fri_price' => $request->get('fri_price'),
                'price_per_surplus' => $request->get('price_per_surplus'),
                'security_deposit' => $request->get('security_deposit'),
                'cleaning_fee' => $request->get('cleaning_fee'),
                'off' => $request->input('off', 0),
                'daily_off' => $request->input('daily_off', 0),
                'daily_off_amount' => $request->input('daily_off_amount', 0),
                'reject_policy' => $request->input('reject_policy'),
                'rules' => $request->input('rules'),
                'more_health' => $request->input('more_health'),
                'more_safety' => $request->input('more_safety'),
                'yard_meter' => $request->input('yard'),
                'infrastructure_meter' => $request->input('infrastructure'),
                'sleep_area_description' => $request->input('sleep_area_description'),
                'draft_step' => null,
            ]);

            $this->syncHomeSleepRooms($home, $request, true);
            $this->syncHomeOptionsAndHealths($home, $request);
            $this->syncHomeSafeties($home, $request);

            // Handle cover image (به‌جای hasFile؛ در بعضی سرورها hasFile برای آپلود معتبر false می‌شود)
            $cover = $request->file('cover');
            if ($cover instanceof UploadedFile) {
                UploadValidation::validateUploadedOrFail($cover, 'cover', 'کاور');
                $home->addCover($cover);
            }

            $galleryWarnings = UploadValidation::appendGalleryImagesBestEffort($home, $request->file('images'), 'تصویر');

            // Handle document
            $doc = $request->file('document');
            if ($doc instanceof UploadedFile) {
                UploadValidation::validateUploadedOrFail($doc, 'document', 'سند');
                $home->addDocument($doc);
            }

            DB::commit();

            $redirect = redirect()->route('dashboard.homes.index')->with('success', 'اقامتگاه با موفقیت ایجاد شد');
            if ($galleryWarnings !== []) {
                $redirect->with('warning', implode("\n\n", $galleryWarnings));
            }

            return $redirect;
        }
        catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', 'خطا در ایجاد اقامتگاه: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $home)
    {
        $home = auth()->user()->findHomeOrFail($home, [
            'images', 'documents', 'options', 'healths', 'safeties', 'variables', 'sleepPlaces',
        ]);
        $home->syncDocumentsFromDisk();
        $home->load('documents');
        $home_variables = $home->variables;

        if ($request->is_mobile ?? false) {
            return view('dashboard.homes.edit-mobile', compact(['home', 'home_variables']));
        }

        return view('dashboard.homes.edit', compact(['home', 'home_variables']));
    }

    public function update(Home $home, UpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            // Handle sleep places only if they exist in request
            if ($request->has('sleep_share') || $request->has('sleep_room')) {
                $rooms = [];
                if ($request->filled('sleep_share')){
                    $room = $request->get('sleep_share');

                    $room = $home->sleepPlaces()->updateOrCreate(['is_share' => true], [
                        'single_bed' => $room['single_bed'],
                        'double_bed' => $room['double_bed'],
                        'traditional_bed' => $room['traditional_bed'],
                        'more' => $room['more'],
                    ]);
                    $rooms[] = $room->id;
                }
                else {
                    $home->sleepPlaces()->where(['is_share' => true])->delete();
                }

                foreach ($request->get('sleep_room') ?? [] as $room){
                    $room = $home->sleepPlaces()->updateOrCreate(['id' => $room['id'] ?? null, 'is_share' => false], [
                        'single_bed' => $room['single_bed'],
                        'double_bed' => $room['double_bed'],
                        'traditional_bed' => $room['traditional_bed'],
                        'more' => $room['more'],
                    ]);

                    $rooms[] = $room->id;
                }

                $home->sleepPlaces()->whereNotIn('id', $rooms)->delete();
            }


            // Update basic home information
            $updateData = [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'main_guest' => $request->get('main_guest'),
                'type' => $request->get('type'),
                'province_id' => $request->get('province_id'),
                'city_id' => $request->get('city_id'),
                'address' => $request->get('address'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'week_price' => $request->get('week_price'),
                'price_per_surplus' => $request->get('price_per_surplus'),
                'wed_price' => $request->get('wed_price'),
                'thu_price' => $request->get('thu_price'),
                'fri_price' => $request->get('fri_price'),
                'cleaning_fee' => $request->get('cleaning_fee'),
                'off' => $request->input('off', 0),
                'daily_off' => $request->input('daily_off', 0),
                'daily_off_amount' => $request->input('daily_off_amount', 0),
            ];

            // Add optional fields if they exist
            if ($request->has('reject_policy')) {
                $updateData['reject_policy'] = $request->get('reject_policy') ?: null;
            }
            if ($request->has('rules')) {
                $updateData['rules'] = $request->get('rules');
            }
            if ($request->has('more_health')) {
                $updateData['more_health'] = $request->get('more_health');
            }
            if ($request->has('more_safety')) {
                $updateData['more_safety'] = $request->get('more_safety');
            }
            if ($request->filled('yard')) {
                $updateData['yard_meter'] = $request->get('yard');
            }
            if ($request->filled('infrastructure')) {
                $updateData['infrastructure_meter'] = $request->get('infrastructure');
            }
            if ($request->filled('extra_guest')) {
                $updateData['extra_guest'] = $request->get('extra_guest');
            }
            if ($request->filled('atmosphere')) {
                $updateData['atmosphere'] = $request->get('atmosphere');
            }
            if ($request->filled('area')) {
                $updateData['area'] = $request->get('area');
            }
            if ($request->filled('sleep_area_description')) {
                $updateData['sleep_area_description'] = $request->get('sleep_area_description');
            }

            $home->update($updateData);

            $this->syncHomeOptionsAndHealths($home, $request);
            $this->syncHomeSafeties($home, $request);

            $cover = $request->file('cover');
            if ($cover instanceof UploadedFile) {
                UploadValidation::validateUploadedOrFail($cover, 'cover', 'کاور');
                $home->addCover($cover);
            }

            if ($request->filled('delete_existing_images')) {
                $imagesToDelete = $home->images()->whereIn('id', $request->get('delete_existing_images'))->get();
                foreach ($imagesToDelete as $image) {
                    $image->deleteImage();
                    $image->delete();
                }
            }

            $galleryWarnings = UploadValidation::appendGalleryImagesBestEffort($home, $request->file('images'), 'تصویر');

            $doc = $request->file('document');
            if ($doc instanceof UploadedFile) {
                UploadValidation::validateUploadedOrFail($doc, 'document', 'سند');
                $home->addDocument($doc);
            }

            if ($request->filled('variables')){
                foreach ($request->get('variables') as $variable_id => $value){
                    $home->updateVariable($variable_id, $value);
                }
            }

            if ($home->wasChanged()){
                $home->update([
                    'status' => Home::PENDING
                ]);
            }

            DB::commit();

            $successMessage = 'اطلاعات اقامتگاه با موفقیت به‌روزرسانی شد';
            $hadDocumentUpload = $request->file('document') instanceof UploadedFile;

            if ($request->is_mobile ?? false) {
                $redirect = redirect()
                    ->route('dashboard.homes.edit', $home)
                    ->with('success', $successMessage);

                if ($hadDocumentUpload) {
                    $redirect->with('open_tab', 'tab-document');
                }
            } else {
                $redirect = redirect()
                    ->route('dashboard.homes.index')
                    ->with('success', $successMessage);
            }

            if ($galleryWarnings !== []) {
                $redirect->with('warning', implode("\n\n", $galleryWarnings));
            }

            return $redirect;
        }
        catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('error', 'خطا در به‌روزرسانی اطلاعات اقامتگاه: '.$e->getMessage());
        }
    }

    private function syncHomeOptionsAndHealths(Home $home, Request $request): void
    {
        if ($request->has('options')) {
            $home->options()->sync($request->input('options', []));
        }

        if ($request->has('healths')) {
            $home->healths()->sync($request->input('healths', []));
        }
    }

    private function syncHomeSleepRooms(Home $home, Request $request, bool $alwaysSyncRooms = false): void
    {
        if (! $alwaysSyncRooms && ! $request->has('sleep_share') && ! $request->has('sleep_room')) {
            return;
        }

        $rooms = [];

        if ($request->filled('sleep_share')) {
            $share = $request->get('sleep_share');
            $room = $home->sleepPlaces()->updateOrCreate(['is_share' => true], [
                'single_bed' => $share['single_bed'] ?? 0,
                'double_bed' => $share['double_bed'] ?? 0,
                'traditional_bed' => $share['traditional_bed'] ?? 0,
                'more' => $share['more'] ?? null,
            ]);
            $rooms[] = $room->id;
        } else {
            $home->sleepPlaces()->where(['is_share' => true])->delete();
        }

        foreach ($request->get('sleep_room', []) as $room) {
            $room = $home->sleepPlaces()->updateOrCreate(
                ['id' => $room['id'] ?? null, 'is_share' => false],
                [
                    'single_bed' => $room['single_bed'] ?? 0,
                    'double_bed' => $room['double_bed'] ?? 0,
                    'traditional_bed' => $room['traditional_bed'] ?? 0,
                    'more' => $room['more'] ?? null,
                ]
            );
            $rooms[] = $room->id;
        }

        $home->sleepPlaces()->whereNotIn('id', $rooms)->delete();
    }

    private function syncHomeSafeties(Home $home, Request $request): void
    {
        if (! $request->has('safeties') && ! $request->has('more_safety')) {
            return;
        }

        $home->safeties()->detach();

        foreach ($request->input('safeties', []) as $safety) {
            if (! empty($safety['id'])) {
                $home->safeties()->attach($safety['id'], [
                    'description' => $safety['description'] ?? '',
                ]);
            }
        }

        if ($request->has('more_safety')) {
            $home->update(['more_safety' => $request->input('more_safety')]);
        }
    }

    public function updateHostStatus(UpdateHostStatusRequest $request, $home)
    {
        $home = auth()->user()->findHomeOrFail($home);

        if ($home->is_draft) {
            return redirect()->back()->with('danger', 'پیش‌نویس اقامتگاه را نمی‌توان از این بخش غیرفعال کرد.');
        }

        try {
            if ($request->input('action') === 'activate') {
                $home->update([
                    'is_host_active' => true,
                    'host_deactivation_reason' => null,
                ]);

                return redirect()->back()->with('success', 'اقامتگاه «'.$home->name.'» فعال شد.');
            }

            $home->update([
                'is_host_active' => false,
                'host_deactivation_reason' => $request->input('reason'),
            ]);

            return redirect()->back()->with('success', 'اقامتگاه «'.$home->name.'» غیرفعال شد.');
        } catch (Exception $e) {
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function destroy($home)
    {
        $home = auth()->user()->findHomeOrFail($home);

        if (! $home->is_draft) {
            return redirect()->back()->with('danger', 'به‌جای حذف، اقامتگاه را غیرفعال کنید.');
        }

        try {
            DB::beginTransaction();

            $home->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete home', ['name' => $home->name]));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
