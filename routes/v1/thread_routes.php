<?php

use App\Http\Controllers\API\v1\Thread\SubscribeController;
use App\Http\Controllers\API\v1\Thread\AnswerController;
use App\Http\Controllers\API\v1\Thread\ThreadController;
use Illuminate\Support\Facades\Route;

Route::resource('threads', ThreadController::class)->except(['create', 'edit']);

Route::prefix('threads')->group(function () {
    Route::resource('answers', AnswerController::class)->except(['create', 'edit', 'show']);

    Route::post('/{thread}/subscribe', [SubscribeController::class, 'subscribe'])->name('threads.subscribe');
    Route::post('/{thread}/unsubscribe', [SubscribeController::class, 'unsubscribe'])->name('threads.unsubscribe');
});
