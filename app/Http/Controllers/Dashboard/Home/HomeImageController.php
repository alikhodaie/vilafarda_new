<?php

namespace App\Http\Controllers\Dashboard\Home;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Home\StoreHomeImageRequest;
use App\Http\Requests\Dashboard\Home\UpdateHomeImageRequest;
use App\Models\Home;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HomeImageController extends Controller
{

    public function index(Home $home, Request $request)
    {
        if (! $request->json()){
            abort(404);
        }

        $home->images->map(function ($image){
            $image->path = $image->image_path;
            return $image;
        });

        return response()->json($home->images);
    }

    public function store(Home $home, StoreHomeImageRequest $request)
    {
        try {
            DB::beginTransaction();

            $image = $home->addImage($request->file('file'));

            $home->update([
                'status' => Home::PENDING
            ]);

            DB::commit();
            return $image;
        }
        catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            throw ValidationException::withMessages([
                'file' => $e->getMessage() ?: __('text.whoops'),
            ]);
        }
    }

    public function update(Home $home, $image, UpdateHomeImageRequest $request)
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

    public function destroy($home, $image)
    {
        $home = auth()->user()->findHomeOrFail($home);
        $image = $home->images()->findOrFail($image);

        try {
            DB::beginTransaction();

            $image->deleteImage();
            $image->delete();

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
}
