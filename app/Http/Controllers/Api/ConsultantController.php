<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Consultant;

class ConsultantController extends Controller
{
    public function index()
    {
        $consultant = Consultant::query()->get();
        return response()->json($consultant);
    }
}
