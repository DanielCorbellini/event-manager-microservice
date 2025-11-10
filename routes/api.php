<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Events\EventsController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Middleware\JwtMiddleware;

// Route::middleware([JwtMiddleware::class])->group(function () {

Route::prefix("eventos")->group(function () {
    Route::post('/', [EventsController::class, 'store']);
    Route::get('/', [EventsController::class, 'index']);
    Route::get('/{id}', [EventsController::class, 'show']);
    Route::put('/{id}', [EventsController::class, 'update']);
    Route::delete('/{id}', [EventsController::class, 'destroy']);

    Route::prefix("inscricao")->group(function () {
        Route::get('/{id}', [SubscriptionController::class, 'index']);
        Route::post('/', [SubscriptionController::class, 'store']);
        Route::put('/{id}', [SubscriptionController::class, 'destroy']);
    });
});
// });
