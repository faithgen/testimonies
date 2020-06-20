<?php

use Faithgen\Testimonies\Http\Controllers\TestimonyController;
use Illuminate\Support\Facades\Route;

Route::prefix('testimonies/')
    ->name('testimonies.')
    ->group(function () {
        Route::get('', [TestimonyController::class, 'index']);
        Route::get('{testimony}', [TestimonyController::class, 'show']);
        Route::get('user/{user}', [TestimonyController::class, 'userTestimonies']);
        Route::get('comments/{testimony}', [TestimonyController::class, 'comments']);
        Route::post('comment/{testimony}', [TestimonyController::class, 'comment']);
        Route::delete('{testimony}/{image}', [TestimonyController::class, 'destroyImage'])->name('delete-image');
        Route::post('', [TestimonyController::class, 'create']);
        Route::post('update/{testimony}', [TestimonyController::class, 'update']);
        Route::post('add-images', [TestimonyController::class, 'addImages']);
        Route::delete('{testimony}', [TestimonyController::class, 'destroy']);
    });
