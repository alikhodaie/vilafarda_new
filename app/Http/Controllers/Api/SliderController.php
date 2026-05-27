<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SliderController extends Controller
{
    public function index()
    {
        $slider = setting('index:slider') ;
        $slider = json_decode($slider, true);
        

        foreach ($slider as $index => $item){
            if (isset($item['image'])){
                $slider[$index]['image'] = asset(Setting::FILE_PATH.'slider/'.$item['image']);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $slider
        ]);
    }
}
