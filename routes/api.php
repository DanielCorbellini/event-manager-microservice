<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Events\EventsController;
use App\Http\Controllers\Subscription\SubscriptionController;

Route::prefix("eventos")->group(function () {
    Route::post('/', [EventsController::class, 'store']);
    Route::get('/', [EventsController::class, 'index']);
    Route::get('/{id}', [EventsController::class, 'show']);
    Route::put('/{id}', [EventsController::class, 'update']);
    Route::delete('/{id}', [EventsController::class, 'destroy']);
});

Route::prefix("inscricoes")->group(function () {
    Route::post('/', [SubscriptionController::class, 'store']);
    Route::delete('/{id}', [SubscriptionController::class, 'destroy']);
});
