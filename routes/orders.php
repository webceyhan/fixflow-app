<?php

use App\Http\Controllers\OrderController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('orders', [OrderController::class, 'index'])
        ->name('orders.index')
        ->can('viewAny', Order::class);

    Route::get('tickets/{ticket}/orders/create', [OrderController::class, 'create'])
        ->name('orders.create')
        ->can('create', Order::class);

    Route::post('tickets/{ticket}/orders', [OrderController::class, 'store'])
        ->name('orders.store')
        ->can('create', Order::class);

    Route::get('orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show')
        ->can('view', 'order');

    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])
        ->name('orders.edit')
        ->can('update', 'order');

    Route::put('orders/{order}', [OrderController::class, 'update'])
        ->name('orders.update')
        ->can('update', 'order');

    Route::delete('orders/{order}', [OrderController::class, 'destroy'])
        ->name('orders.destroy')
        ->can('delete', 'order');
});
