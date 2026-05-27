<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\StoreHomeRequest;
use App\Http\Requests\Admin\Home\UpdateHomeRequest;
use App\Models\City;
use App\Models\Home;
use App\Models\Province;
use App\Models\User;
use App\Support\UploadValidation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('index', Home::class);

        $homes = Home::query()
            ->with('user')
            ->where('is_draft', false)
            ->search()
            ->orderBy('status')
            ->paginate(10)
            ->appends($request->all());
        return view('admin.homes.index', compact('homes'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.homes.create');
    }

    public function store(StoreHomeRequest $request)
    {
        try {
            DB::beginTransaction();

            $home = User::query()->findOrFail($request->get('user'))->homes()->create([
                'is_draft' => false,
                'code' => $request->get('code'),
                'status' => $request->get('status'),
                'reject_policy' => $request->get('reject_policy'),
                'name' => $request->get('name'),
                'province_id' => $request->get('province'),
                'city_id' => $request->get('city'),
                'address' => $request->get('address'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'description' => $request->get('description'),
                'atmosphere' => $request->get('atmosphere'),
                'type' => $request->get('type'),
                'area' => $request->get('area'),
                'rules' => $request->get('rules'),
                'yard_meter' => $request->get('yard'),
                'infrastructure_meter' => $request->get('infrastructure'),
                'main_guest' => $request->get('main_guest'),
                'extra_guest' => $request->get('extra_guest'),
                'week_price' => $request->get('week_price'),
                'wed_price' => $request->get('wed_price'),
                'thu_price' => $request->get('thu_price'),
                'fri_price' => $request->get('fri_price'),
                'shaba' => $request->get('shaba'),
                'price_per_surplus' => $request->get('price_per_surplus'),
                'sleep_area_description' => $request->get('sleep_area_description')
            ]);

            foreach ($request->get('variables') ?? [] as $variable_id => $value){
                $home->updateVariable($variable_id, $value);
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
                'more_safety' => $request->get('more_safety')
            ]);

            if ($request->filled('sleep_share')){
                $room = $request->get('sleep_share');

                $home->sleepPlaces()->create([
                    'is_share' => true,
                    'single_bed' => $room['single_bed'],
                    'double_bed' => $room['double_bed'],
                    'traditional_bed' => $room['traditional_bed'],
                    'more' => $room['more']
                ]);
            }

            foreach ($request->get('sleep_room') ?? [] as $room){
                $home->sleepPlaces()->create([
                    'is_share' => false,
                    'single_bed' => $room['single_bed'],
                    'double_bed' => $room['double_bed'],
                    'traditional_bed' => $room['traditional_bed'],
                    'more' => $room['more']
                ]);
            }

            UploadValidation::validateUploadedOrFail($request->file('cover'), 'cover', 'کاور');

            try {
                $home->updateCover($request->file('cover'));
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'cover' => $e->getMessage(),
                ]);
            }

            $documentFile = $request->file('document');
            if ($documentFile instanceof UploadedFile) {
                $home->addDocument($documentFile);
            }

            DB::commit();
            return redirect()->route('admin.homes.edit', $home)->with('success', __('text.success.create home', ['name' => $home->name]));
        }
        catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function edit(Home $home)
    {
        $this->authorize('update', $home);

        $provinces = Province::query()->orderBy('name')->get();
        $cities = City::query()->orderBy('name')->get();
        $usersForSelect = User::query()
            ->select(['id', 'first_name', 'last_name'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $sleepSharePlace = $home->sleepPlaces->first(function ($p) {
            return (bool) $p->is_share;
        });
        $sleepPrivatePlaces = $home->sleepPlaces->filter(function ($p) {
            return ! (bool) $p->is_share;
        })->values();

        $home->load('documents');
        $home->syncDocumentsFromDisk();
        $home->load('documents');

        return view('admin.homes.edit', compact(
            'home',
            'provinces',
            'cities',
            'usersForSelect',
            'sleepSharePlace',
            'sleepPrivatePlaces'
        ));
    }

    public function update(Home $home, UpdateHomeRequest $request)
    {
        try {
            DB::beginTransaction();

            /** @var UploadedFile|null $coverFile */
            $coverFile = $request->file('cover');
            if ($coverFile instanceof UploadedFile) {
                UploadValidation::validateUploadedOrFail($coverFile, 'cover', 'کاور');
            }

            $slugInput = trim((string) $request->input('slug', ''));
            $slug = $slugInput !== ''
                ? Home::normalizeSlug($slugInput)
                : $home->suggestSlug();

            $home->update([
                'code' => $request->get('code'),
                'status' => $request->get('status'),
                'reject_policy' => $request->get('reject_policy'),
                'name' => $request->get('name'),
                'slug' => $slug !== '' ? $slug : $home->suggestSlug(),
                'user_id' => $request->get('user'),
                'province_id' => $request->get('province'),
                'city_id' => $request->get('city'),
                'address' => $request->get('address'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'description' => $request->get('description'),
                'atmosphere' => $request->get('atmosphere'),
                'type' => $request->get('type'),
                'area' => $request->get('area'),
                'rules' => $request->get('rules'),
                'yard_meter' => $request->get('yard'),
                'infrastructure_meter' => $request->get('infrastructure'),
                'fake_score' => $request->get('score'),
                'main_guest' => $request->get('main_guest'),
                'extra_guest' => $request->get('extra_guest'),
                'week_price' => $request->get('week_price'),
                'wed_price' => $request->get('wed_price'),
                'thu_price' => $request->get('thu_price'),
                'fri_price' => $request->get('fri_price'),
                'price_per_surplus' => $request->get('price_per_surplus'),
                'cleaning_fee' => $request->get('cleaning_fee'),
                'daily_off_amount' => $request->get('daily_off_amount'),
                'daily_off' => $request->get('daily_off'),
                'off'       => $request->get('off'),
                'shaba'     => $request->get('shaba'),
                'sleep_area_description' => $request->get('sleep_area_description')
            ]);

            foreach ($request->get('variables') ?? [] as $variable_id => $value){
                $home->updateVariable($variable_id, $value);
            }

            if ($coverFile instanceof UploadedFile) {
                try {
                    $home->updateCover($coverFile);
                } catch (\Throwable $e) {
                    throw ValidationException::withMessages([
                        'cover' => $e->getMessage(),
                    ]);
                }
            }

            if ($request->filled('delete_existing_images')) {
                $imagesToDelete = $home->images()->whereIn('id', $request->get('delete_existing_images'))->get();
                foreach ($imagesToDelete as $image) {
                    $image->deleteImage();
                    $image->delete();
                }
            }

            $home->update([
                'cover_alt' => trim((string) $request->input('cover_alt', '')) ?: null,
            ]);

            foreach ($request->input('image_alts', []) as $imageId => $altText) {
                $altText = trim((string) $altText);
                $home->images()->where('id', (int) $imageId)->update([
                    'alt_text' => $altText !== '' ? $altText : null,
                ]);
            }

            $galleryWarnings = UploadValidation::appendGalleryImagesBestEffort($home, $request->file('gallery'));

            $videoFile = $request->file('video');
            if ($videoFile instanceof UploadedFile) {
                $home->updateVideo($videoFile);
            }

            $documentFile = $request->file('document');
            if ($documentFile instanceof UploadedFile) {
                $home->addDocument($documentFile);
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
                'more_safety' => $request->get('more_safety')
            ]);

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

            DB::commit();

            $redirect = redirect()
                ->route('admin.homes.edit', $home)
                ->with('success', __('text.success.update home', ['name' => $home->name]))
                ->with('open_tab', $request->get('open_tab', 'tab-admin'));

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
            throw ValidationException::withMessages([
                'error' => __('text.whoops')
            ]);
        }
    }

    public function destroy(Home $home)
    {
        $this->authorize('delete', $home);

        try {
            DB::beginTransaction();

            $home->delete();

            DB::commit();
            return redirect()->route('admin.homes.index')->with('success', __('text.success.delete home', ['name' => $home->name]));
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
