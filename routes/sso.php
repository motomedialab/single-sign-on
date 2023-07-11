<?php
/**
 * Single Sign On Routes
 */

use Illuminate\Support\Facades\Route;
use Motomedialab\SingleSignOn\Controllers\CallbackController;
use Motomedialab\SingleSignOn\Controllers\LoginController;

Route::middleware(config('sso.middleware'))->group(function () {
    Route::get('login', LoginController::class)->name('login-sso');

    Route::get('login/sso', CallbackController::class)->name('login-sso-callback');
});
