<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\HourController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::get('/vendor', [VendorController::class, 'index']);
Route::get('/vendor/{id}', [VendorController::class, 'show']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/addOutlet', [VendorController::class, 'store']);
    Route::post('/updateOutlet/{id}', [OutletController::class, 'update']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'vendor'

], function ($router) {
    Route::post('/addHour', [HourController::class, 'store']);
    Route::post('/updateHour/{id}', [HourController::class, 'update']);
});

Route::group([
    'middleware' => ['api', 'jwtVerify'],
    'prefix' => 'auth'

], function ($router) {
    Route::get('/profile', [ProfileController::class, 'userProfile']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/updateImage', [ProfileController::class, 'updateImage']);
});
