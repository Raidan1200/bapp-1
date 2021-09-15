<?php

use App\Models\Order;
use App\Services\Invoice;
use App\Http\Livewire\Products;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

Route::middleware('auth')->group(function() {
    // Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('venues', VenueController::class);
    Route::resource('rooms', RoomController::class)->except(['index', 'show']);
    Route::resource('packages', PackageController::class)->except(['index']);
    Route::get('products', Products::class)->name('products');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('venues/{venue}/token', [TokenController::class, 'store'])->name('token.store');
});

Route::get('client-demo', function() {
    return view('client-demo');
});

Route::get('pdf', function () {
    $order = Order::findOrFail(1);

    return (new Invoice)
        ->ofType('deposit')
        ->forOrder($order)
        ->makeHtml();
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
