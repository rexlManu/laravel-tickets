<?php

/*
 * The ticket routes
 */

use RexlManu\LaravelTickets\Controllers\TicketController;
use RexlManu\LaravelTickets\Models\Ticket;
Route::middleware(config('laravel-tickets.guard'))->group(function () {
    Route::bind('ticket', Ticket::class);

    Route::prefix('/tickets')->group(function () {
        Route::get('/', [ TicketController::class, 'index' ])->name('tickets.index');
        Route::post('/', [ TicketController::class, 'store' ])->name('tickets.store');
        Route::prefix('{ticket}')->group(function () {
            Route::get('/', [ TicketController::class, 'show' ])->name('tickets.show');
            Route::post('/', [ TicketController::class, 'close' ])->name('tickets.close');
            Route::post('/message', [ TicketController::class, 'message' ])->name('tickets.message');
        });
    });
});

