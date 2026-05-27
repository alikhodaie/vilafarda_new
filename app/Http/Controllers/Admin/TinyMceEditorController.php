<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TinyMceEditorController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->store($request->get('directory'));

            return response()->json([
                'location' => asset($fileName),
            ]);
        }
    }

}
