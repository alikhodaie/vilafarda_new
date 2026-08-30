<?php

namespace App\Http\Controllers\Admin\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Home\Image\StoreImageRequest;
use App\Http\Requests\Admin\Home\Image\UpdateImageRequest;
use App\Models\Home;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ImageController extends Controller
{
    public function index(Home $home, Request $request)
    {
        $this->authorize('index', Home::class);

        if (! $request->json()){
            abort(404);
        }

        $home->images->map(function ($image){
            $image->path = $image->image_path;
            return $image;
        });

        return response()->json($home->images);
    }

    public function store(Home $home, StoreImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $image = $home->addImage($request->file('file'));

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json($image->fresh(['home']));
            }

            return $image;
        } catch (\Throwable $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'file' => $e->getMessage() ?: __('text.whoops'),
            ]);
        }
    }

    public function update(Home $home, $image, UpdateImageRequest $request)
    {
        $image = $home->images()->findOrFail($image);

        try {
            DB::beginTransaction();

            $image->update(['position' => $request->get('position')]);

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'image' => __('text.whoops')
            ]);
        }
    }

    public function bulkDestroy(Home $home, Request $request)
    {
        $this->authorize('update', $home);

        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', Rule::exists('home_images', 'id')->where('home_id', $home->id)],
        ]);

        $ids = array_values(array_unique(array_map('intval', $validated['ids'])));

        try {
            DB::beginTransaction();

            foreach ($ids as $id) {
                $image = $home->images()->find($id);
                if (! $image) {
                    continue;
                }
                $image->deleteImage();
                $image->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.homes.edit', $home)
                ->with('open_tab', 'tab-media')
                ->with('success', count($ids).' تصویر حذف شد.');
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'image' => __('text.whoops'),
            ]);
        }
    }

    public function destroyAll(Home $home)
    {
        $this->authorize('update', $home);

        $images = $home->images()->get();
        $hadCover = (bool) $home->cover;

        if ($images->isEmpty() && ! $hadCover) {
            return redirect()
                ->route('admin.homes.edit', $home)
                ->with('open_tab', 'tab-media')
                ->with('warning', 'تصویری برای حذف وجود ندارد.');
        }

        try {
            DB::beginTransaction();

            foreach ($images as $image) {
                $image->deleteImage();
                $image->delete();
            }

            if ($hadCover) {
                $home->deleteCover();
            }

            DB::commit();

            $parts = [];
            if ($images->isNotEmpty()) {
                $parts[] = $images->count().' تصویر گالری';
            }
            if ($hadCover) {
                $parts[] = 'کاور';
            }

            return redirect()
                ->route('admin.homes.edit', $home)
                ->with('open_tab', 'tab-media')
                ->with('success', implode(' و ', $parts).' حذف شد.');
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'image' => __('text.whoops'),
            ]);
        }
    }

    public function destroy(Home $home, $image, Request $request)
    {
        $this->authorize('update', $home);
        $image = $home->images()->findOrFail($image);

        try {
            DB::beginTransaction();

            $image->deleteImage();
            $image->delete();

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()
                ->route('admin.homes.edit', $home)
                ->with('success', __('text.success.delete_item'));
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'image' => __('text.whoops')
            ]);
        }
    }
}
