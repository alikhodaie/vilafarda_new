<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request)
    {
        if ($request->is_mobile ?? false) {
            // if user was mobile user
            return view('dashboard.index-mobile');
        }

        return view('dashboard.index');
    }

    public function provinces()
    {
        return response()->json(Province::getFromCache());
    }
}
