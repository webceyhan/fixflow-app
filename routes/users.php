<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', [UserController::class, 'index'])
        ->name('users.index')
        ->can('viewAny', User::class);

    Route::get('users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->can('create', User::class);

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store')
        ->can('create', User::class);

    Route::get('users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->can('view', 'user');

    Route::get('users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->can('update', 'user');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->can('update', 'user');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->can('delete', 'user');
});
