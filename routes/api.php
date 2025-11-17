<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Events\EventsController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Middleware\JwtMiddleware;

// Route::middleware([JwtMiddleware::class])->group(function () {

// Eventos
Route::prefix("eventos")->group(function () {
    Route::post('/', [EventsController::class, 'store']);
    Route::get('/', [EventsController::class, 'index']);
    Route::get('/{id}', [EventsController::class, 'show']);
    Route::put('/{id}', [EventsController::class, 'update']);
    Route::delete('/{id}', [EventsController::class, 'destroy']);
});

// Inscrições
Route::prefix("inscricoes")->group(function () {
    Route::post('/', [SubscriptionController::class, 'store']);
    Route::get('/', [SubscriptionController::class, 'index']);
    Route::put('/{id}', [SubscriptionController::class, 'destroy']);
    Route::post('/{id}/checkin', [SubscriptionController::class, 'checkin']);
});

// });
