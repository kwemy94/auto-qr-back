<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function(){
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'user'])->name('user');
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');

    });
});


Route::middleware('auth:api')->group(function () {
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('auth.update.profile');
    Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('user.subscribe');

    /* some problem with put or patch request > to use it, you should add _method=PATCH|PUSH in request url */

});
