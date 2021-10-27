<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;


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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('config', [Api\ZauberController::class, 'config']);
    Route::get('rooms/{room}/bookings', [Api\ZauberController::class, 'bookings']);
    Route::post('venues/{venue}/orders', [Api\ZauberController::class, 'order']);
});
