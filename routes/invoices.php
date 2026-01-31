<?php

use App\Http\Controllers\InvoiceController;
use App\Models\Invoice;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index')
        ->can('viewAny', Invoice::class);

    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
        ->name('invoices.show')
        ->can('view', 'invoice');

    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])
        ->name('invoices.edit')
        ->can('update', 'invoice');

    Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])
        ->name('invoices.update')
        ->can('update', 'invoice');
});
