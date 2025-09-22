<?php

use Illuminate\Support\Facades\Route;
use Snawbar\Guardian\Controllers\GuardianController;

Route::middleware(['web', 'auth'])->prefix('guardian')->name('guardian.')->group(function () {
    // Email 2FA routes (for master users)
    Route::get('/email', [GuardianController::class, 'showEmail'])->name('email');
    Route::post('/email/send', [GuardianController::class, 'sendEmail'])->name('email.send');
    Route::post('/email/verify', [GuardianController::class, 'verifyEmail'])->name('email.verify');

    // Google Authenticator routes (for regular users)
    Route::get('/authenticator', [GuardianController::class, 'showAuthenticator'])->name('authenticator');
    Route::post('/authenticator/verify', [GuardianController::class, 'verifyAuthenticator'])->name('authenticator.verify');

    // Setup routes
    Route::get('/setup', [GuardianController::class, 'showSetup'])->name('setup');
    Route::post('/setup', [GuardianController::class, 'completeSetup'])->name('setup.complete');
});
