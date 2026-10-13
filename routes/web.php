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

// Suivi d'un signalement par le signaleur (vu ? « J'arrive » ?) — interrogé
// toutes les quelques secondes par la page de scan et l'app.
Route::get('/n/report/{publicId}', [NotificationPublicController::class, 'status'])
    ->whereUuid('publicId')
    ->name('notify.status')
    ->middleware('throttle:40,1');

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
})->name('privacy-policy');
