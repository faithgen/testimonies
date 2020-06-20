<?php

use Faithgen\Testimonies\Http\Controllers\TestimonyController;
use Illuminate\Support\Facades\Route;

Route::prefix('testimonies/')
    ->name('testimonies.')
    ->middleware('source.site')
    ->group(function () {
        Route::put('{testimony}/toggle-approval', [TestimonyController::class, 'toggleApproval'])->name('toggle-approval');
    });
