<?php

use Illuminate\Support\Facades\Route;

Route::prefix('sermons/')
    ->middleware('source.site')
    ->group(function () {
    });
