<?php

use App\Http\Controllers\API\v1\Thread\AnswerController;
use App\Http\Controllers\API\v1\Thread\ThreadController;
use Illuminate\Support\Facades\Route;

Route::resource('threads', ThreadController::class)->except(['create', 'edit']);

Route::prefix('threads')->group(function () {
    Route::resource('answers', AnswerController::class)->except(['create', 'edit', 'show']);
});
