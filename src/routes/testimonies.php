<?php

use Faithgen\Testimonies\Http\Controllers\TestimonyController;
use Illuminate\Support\Facades\Route;

Route::prefix('testimonies/')->group(function () {
    Route::post('', [TestimonyController::class, 'create']);
});
