<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\SubscriptionApiController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    // Mot de passe oublié : code à 6 chiffres par e-mail
    Route::post('forgot-password', [PasswordResetController::class, 'sendCode'])
        ->middleware('throttle:3,1')->name('forgot-password');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])
        ->middleware('throttle:5,1')->name('reset-password');

    Route::middleware('auth:api')->group(function(){
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'user'])->name('user');
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');
        Route::delete('account', [AuthController::class, 'deleteAccount'])
            ->middleware('throttle:5,1')->name('delete-account');

    });
});


// Notification serveur-à-serveur de l'agrégateur de paiement (publique ;
// l'authenticité est vérifiée par chaque PaymentGateway::handleWebhook)
Route::post('/payments/webhook/{provider}', [SubscriptionApiController::class, 'webhook'])
    ->middleware('throttle:60,1')->name('payments.webhook');

Route::middleware('auth:api')->group(function () {
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('auth.update.profile');
    // Ancienne route POST /subscribe supprimée : elle activait un abonnement
    // SANS paiement, pour n'importe quel user_id envoyé dans la requête.

    // ── Abonnement premium (Mobile Money) ─────────────────────
    Route::get('/subscription', [SubscriptionApiController::class, 'status'])->name('subscription.status');
    Route::get('/subscription/plans', [SubscriptionApiController::class, 'plans'])->name('subscription.plans');
    Route::post('/subscription/checkout', [SubscriptionApiController::class, 'checkout'])
        ->middleware('throttle:5,1')->name('subscription.checkout');
    Route::get('/subscription/payments/{reference}', [SubscriptionApiController::class, 'payment'])
        ->whereUuid('reference')->middleware('throttle:60,1')->name('subscription.payment');
    Route::post('/scan-qr', [MessageController::class, 'handleScan']);

    /* some problem with put or patch request > to use it, you should add _method=PATCH|PUSH in request url */



    Route::get('/notifications',          [NotificationController::class, 'index']);
    Route::get('/notifications/{id}',     [NotificationController::class, 'show']);
    Route::put('/notifications/read-all', [NotificationController::class, 'readAll']);
    Route::post('/notifications/{id}/respond', [NotificationController::class, 'respond'])
        ->whereNumber('id')->name('notifications.respond');
    Route::post('/notifications/test',    [NotificationController::class, 'test'])
        ->middleware('throttle:5,1')->name('notifications.test');

     Route::put('/auth/fcm-token', [AuthController::class, 'updateFcmToken']);

    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware('throttle:5,1')->name('contact');

});
