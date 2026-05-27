<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::query();

        if ($request->filled('search')){
            $tags->where('name', 'LIKE', "%$request->search%");
        }

        $tags = $tags->paginate(10)->appends($request->all());
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag' => ['required', 'string', 'max:250']
        ]);

        DB::beginTransaction();
        try {
            $tag = Tag::query()->firstOrCreate(['name' => $request->get('tag')]);

            DB::commit();
            return response()->json($tag);
        }
        catch (\Exception $e){
            DB::rollBack();
            Error::catch($e, [__CLASS__, __FUNCTION__]);
        }

    }
}
