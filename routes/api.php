<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function(){
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('user', [AuthController::class, 'user'])->name('user');

    });
});

Route::middleware('auth:api')->group(function () {
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('user.update.profile');
    /* some problem with put or patch request > to verify */

});
