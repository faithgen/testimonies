<?php

use Faithgen\Testimonies\Http\Controllers\TestimonyController;
use Illuminate\Support\Facades\Route;

Route::prefix('testimonies/')
    ->name('testimonies.')
    ->group(function () {
        Route::get('', [TestimonyController::class, 'index']);
        Route::post('', [TestimonyController::class, 'create']);
        Route::post('update', [TestimonyController::class, 'update']);
        Route::get('{testimony}', [TestimonyController::class, 'show']);
        Route::get('user/{user}', [TestimonyController::class, 'userTestimonies']);
        Route::delete('{testimony}', [TestimonyController::class, 'destroy']);
        Route::post('delete-image', [TestimonyController::class, 'destroyImage'])->name('delete-image');
    });
