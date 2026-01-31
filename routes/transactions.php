<?php

use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('transactions', [TransactionController::class, 'index'])
        ->name('transactions.index')
        ->can('viewAny', Transaction::class);

    Route::get('invoices/{invoice}/transactions/create', [TransactionController::class, 'create'])
        ->name('transactions.create')
        ->can('create', Transaction::class);

    Route::post('invoices/{invoice}/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store')
        ->can('create', Transaction::class);

    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show')
        ->can('view', 'transaction');

    Route::get('transactions/{transaction}/edit', [TransactionController::class, 'edit'])
        ->name('transactions.edit')
        ->can('update', 'transaction');

    Route::put('transactions/{transaction}', [TransactionController::class, 'update'])
        ->name('transactions.update')
        ->can('update', 'transaction');

    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])
        ->name('transactions.destroy')
        ->can('delete', 'transaction');
});
