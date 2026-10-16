<?php

use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('tickets', [TicketController::class, 'index'])
        ->name('tickets.index')
        ->can('viewAny', Ticket::class);

    Route::get('devices/{device}/tickets/create', [TicketController::class, 'create'])
        ->name('tickets.create')
        ->can('create', Ticket::class);

    Route::post('devices/{device}/tickets', [TicketController::class, 'store'])
        ->name('tickets.store')
        ->can('create', Ticket::class);

    Route::get('tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show')
        ->can('view', 'ticket');

    Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])
        ->name('tickets.edit')
        ->can('update', 'ticket');

    Route::put('tickets/{ticket}', [TicketController::class, 'update'])
        ->name('tickets.update')
        ->can('update', 'ticket');

    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy')
        ->can('delete', 'ticket');

    Route::post('tickets/{ticket}/attachments', [TicketAttachmentController::class, 'store'])
        ->name('tickets.attachments.store')
        ->can('update', 'ticket');

    Route::get('tickets/{ticket}/attachments/{attachment}', [TicketAttachmentController::class, 'download'])
        ->name('tickets.attachments.download')
        ->can('view', 'ticket');

    Route::get('tickets/{ticket}/attachments/{attachment}/preview', [TicketAttachmentController::class, 'preview'])
        ->name('tickets.attachments.preview')
        ->can('view', 'ticket');

    Route::get('tickets/{ticket}/attachments/{attachment}/thumbnail', [TicketAttachmentController::class, 'thumbnail'])
        ->name('tickets.attachments.thumbnail')
        ->can('view', 'ticket');

    Route::delete('tickets/{ticket}/attachments/{attachment}', [TicketAttachmentController::class, 'destroy'])
        ->name('tickets.attachments.destroy')
        ->can('update', 'ticket');
});
