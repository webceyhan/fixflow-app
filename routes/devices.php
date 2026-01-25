<?php

use App\Http\Controllers\DeviceController;
use App\Models\Device;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('devices', [DeviceController::class, 'index'])
        ->name('devices.index')
        ->can('viewAny', Device::class);

    Route::get('customers/{customer}/devices/create', [DeviceController::class, 'create'])
        ->name('devices.create')
        ->can('create', Device::class);

    Route::post('customers/{customer}/devices', [DeviceController::class, 'store'])
        ->name('devices.store')
        ->can('create', Device::class);

    Route::get('devices/{device}', [DeviceController::class, 'show'])
        ->name('devices.show')
        ->can('view', 'device');

    Route::get('devices/{device}/edit', [DeviceController::class, 'edit'])
        ->name('devices.edit')
        ->can('update', 'device');

    Route::put('devices/{device}', [DeviceController::class, 'update'])
        ->name('devices.update')
        ->can('update', 'device');

    Route::delete('devices/{device}', [DeviceController::class, 'destroy'])
        ->name('devices.destroy')
        ->can('delete', 'device');
});
