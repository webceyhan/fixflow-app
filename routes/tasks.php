<?php

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('tasks', [TaskController::class, 'index'])
        ->name('tasks.index')
        ->can('viewAny', Task::class);

    Route::get('tickets/{ticket}/tasks/create', [TaskController::class, 'create'])
        ->name('tasks.create')
        ->can('create', Task::class);

    Route::post('tickets/{ticket}/tasks', [TaskController::class, 'store'])
        ->name('tasks.store')
        ->can('create', Task::class);

    Route::get('tasks/{task}', [TaskController::class, 'show'])
        ->name('tasks.show')
        ->can('view', 'task');

    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
        ->name('tasks.edit')
        ->can('update', 'task');

    Route::put('tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update')
        ->can('update', 'task');

    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy')
        ->can('delete', 'task');
});
