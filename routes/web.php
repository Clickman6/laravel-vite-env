<?php

use Clickman6\LaravelViteEnv\Http\Controllers\FrontendConfigController;
use Illuminate\Support\Facades\Route;

Route::get(config('frontend.route', '/config.js'), FrontendConfigController::class)
    ->name('frontend.js');
