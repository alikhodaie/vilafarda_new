<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\ConsultantController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\LocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobile Login Routes - Using web middleware for session support
Route::middleware('web')->group(function () {
Route::post('/check-phone', [AuthController::class, 'checkPhone']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/register-user', [AuthController::class, 'registerUser']);
Route::post('/login-with-password', [AuthController::class, 'loginWithPassword']);
});

// Home category APIs
Route::get('/homes/last', [HomeController::class, 'last']);
Route::get('/homes/off/cities', [HomeController::class, 'offCities']);
Route::get('/homes/off', [HomeController::class, 'off']);
Route::get('/homes/popular', [HomeController::class, 'popular']);
Route::get('/homes/cheap', [HomeController::class, 'cheap']);
Route::get('/homes/expensive', [HomeController::class, 'expensive']);
Route::get('/homes/open-tomorrow', [HomeController::class, 'openTomorrow']);

//all province
Route::get('/province', [HomeController::class, 'province']);

// Location routes (برای فرم‌های ویرایش ملک در موبایل: ابتدا استان، سپن شهر)
Route::get('/locations/provinces', [LocationController::class, 'provinces']);
Route::get('/cities/{province}', [LocationController::class, 'cities']);


//comment
Route::get('/comment', [CommentController::class, 'comment']);


//slider
Route::get('/slider', [SliderController::class, 'index']);


//Consultant 
Route::get('/consultant', [ConsultantController::class, 'index']);

//blog post api
Route::get('/post', [ArticleController::class, 'index']);