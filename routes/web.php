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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('venues', VenueController::class);
    Route::post('venues/{venue}/token', [TokenController::class, 'store'])->name('token.store');
    Route::resource('rooms', RoomController::class)->except(['index', 'show']);
    Route::resource('packages', PackageController::class)->except(['index']);
    Route::get('products', Products::class)->name('products');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

});

Route::get('client-demo', function() {
    return view('client-demo');
});

require __DIR__.'/auth.php';
// require __DIR__.'/admin.php';
