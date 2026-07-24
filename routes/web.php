<?php

use Clickman6\LaravelViteEnv\Http\Controllers\ViteEnvController;
use Illuminate\Support\Facades\Route;

Route::get(config('vite-env.route'), ViteEnvController::class)
    ->name('vite-env.js');
