<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VenueMemberController;


Route::middleware('auth')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('venues', VenueController::class);
    Route::resource('rooms', RoomController::class)->except(['index', 'show']);
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    Route::post('venues/{venue}/token', [TokenController::class, 'store'])->name('token.store');

    Route::resource('products', ProductController::class)->only([
        'show', 'edit', 'update', 'destroy'
    ]);
});

Route::get('client-demo', function() {
    return view('client-demo');
});

require __DIR__.'/auth.php';
