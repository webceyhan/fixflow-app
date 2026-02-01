<?php

use App\Http\Controllers\AdjustmentController;
use App\Models\Adjustment;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('adjustments', [AdjustmentController::class, 'index'])
        ->name('adjustments.index')
        ->can('viewAny', Adjustment::class);

    Route::get('invoices/{invoice}/adjustments/create', [AdjustmentController::class, 'create'])
        ->name('adjustments.create')
        ->can('create', Adjustment::class);

    Route::post('invoices/{invoice}/adjustments', [AdjustmentController::class, 'store'])
        ->name('adjustments.store')
        ->can('create', Adjustment::class);

    Route::get('adjustments/{adjustment}', [AdjustmentController::class, 'show'])
        ->name('adjustments.show')
        ->can('view', 'adjustment');

    Route::get('adjustments/{adjustment}/edit', [AdjustmentController::class, 'edit'])
        ->name('adjustments.edit')
        ->can('update', 'adjustment');

    Route::put('adjustments/{adjustment}', [AdjustmentController::class, 'update'])
        ->name('adjustments.update')
        ->can('update', 'adjustment');

    Route::delete('adjustments/{adjustment}', [AdjustmentController::class, 'destroy'])
        ->name('adjustments.destroy')
        ->can('delete', 'adjustment');
});
