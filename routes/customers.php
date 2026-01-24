<?php

use App\Http\Controllers\CustomerController;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('customers', [CustomerController::class, 'index'])
        ->name('customers.index')
        ->can('viewAny', Customer::class);

    Route::get('customers/create', [CustomerController::class, 'create'])
        ->name('customers.create')
        ->can('create', Customer::class);

    Route::post('customers', [CustomerController::class, 'store'])
        ->name('customers.store')
        ->can('create', Customer::class);

    Route::get('customers/{customer}', [CustomerController::class, 'show'])
        ->name('customers.show')
        ->can('view', 'customer');

    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('customers.edit')
        ->can('update', 'customer');

    Route::put('customers/{customer}', [CustomerController::class, 'update'])
        ->name('customers.update')
        ->can('update', 'customer');

    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])
        ->name('customers.destroy')
        ->can('delete', 'customer');
});
