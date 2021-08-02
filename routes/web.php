<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VenueMemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('venues', VenueController::class);
    Route::resource('rooms', RoomController::class)->except(['index', 'show']);
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    Route::post('venues/{venue}/token', [TokenController::class, 'store'])->name('token.store');

    Route::resource('products', ProductController::class)->only([
        'show', 'edit', 'update', 'destroy'
    ]);

    // TODO IMPORTANT: Make this a resource route and change the 2 following routes? Pass venue via Query params?
    // Route::get('/venues/{venue}/products/create', [ProductController::class, 'create'])->name('products.create');
    // Route::post('/venues/{venue}/products', [ProductController::class, 'store'])->name('products.store');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show'); // TODO
});

Route::get('client-demo', function() {
    return view('client-demo');
});

require __DIR__.'/auth.php';
