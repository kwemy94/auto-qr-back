<?php

use App\Http\Controllers\NotificationPublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE PUBLIQUE — scannable sans compte ni app
// Encodée dans le QR code : https://qrnotify.cm/n/{token}
// ──────────────────────────────────────────────
Route::get('/n/{token}', [NotificationPublicController::class, 'show'])
    ->name('notify.show');

Route::post('/n/{token}/send', [NotificationPublicController::class, 'send'])
    ->name('notify.send')
    ->middleware('throttle:5,1'); // max 5 signalements par minute par IP

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
})->name('privacy-policy');
