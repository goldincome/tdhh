<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


// Customer auth
//
// Every page in this file is a transactional or account page. They are
// marked noindex so that they never compete with the public listing pages.
Route::middleware('noindex')->group(function () {

// Route::get('/login', function () {
//     return view('auth.login');
// });

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::get('/forgot-password/success', function () {
    return view('auth.forgot-password-success');
});

Route::get('/reset-password', function () {
    return view('auth.reset-password');
});

Route::get('/reset-password/success', function () {
    return view('auth.reset-password-success');
});

// Route::get('/register', function () {
//     return view('auth.register');
// });

Route::get('/activate-account', function () {
    return view('auth.activate-account');
});

Route::get('/login', [AuthenticatedSessionController::class, 'login'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('user.login.post');
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store'])->name('user.register.post');
Route::get('/register/success', [RegisteredUserController::class, 'resisterSuccess'])->name('user.register.success');
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('user.logout');

// Route::get('/register/success', function () {
//     return view('auth.register-success');
// });

Route::get('/email-confirmation/{id}', function () {
    return view('auth.email-confirmation');
});

});




// Route::middleware('guest')->group(function () {
//     Route::get('register', [RegisteredUserController::class, 'create'])
//                 ->name('register');

//     Route::post('register', [RegisteredUserController::class, 'store']);

//     Route::get('login', [AuthenticatedSessionController::class, 'create'])
//                 ->name('login');

//     Route::post('login', [AuthenticatedSessionController::class, 'store']);

//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//                 ->name('password.request');

//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//                 ->name('password.email');

//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//                 ->name('password.reset');

//     Route::post('reset-password', [NewPasswordController::class, 'store'])
//                 ->name('password.update');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
//                 ->name('verification.notice');

//     Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//                 ->middleware(['signed', 'throttle:6,1'])
//                 ->name('verification.verify');

//     Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware('throttle:6,1')
//                 ->name('verification.send');

//     Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
//                 ->name('password.confirm');

//     Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//                 ->name('logout');
// });

