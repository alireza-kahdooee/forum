<?php

use App\Http\Controllers\API\v1\Channel\ChannelController;
use Illuminate\Support\Facades\Route;

Route::prefix('channels')->group(function () {
    Route::get('/', [ChannelController::class, 'index'])->name('channels.index');

    Route::middleware('can:channel management')->group(function () {
        Route::post('/', [ChannelController::class, 'store'])->name('channels.store');
        Route::put('/', [ChannelController::class, 'update'])->name('channels.update');
        Route::delete('/', [ChannelController::class, 'destroy'])->name('channels.destroy');
    });
});
