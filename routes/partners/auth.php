<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Partners\PartnerAuthController;

// Partners auth


Route::group(['prefix' => 'partners', 'as' => 'partner.'], function(){
    Route::group(['middleware' => ['guest:partner', 'noindex']], function () {
        // Route::get('/', function () {
        //     return view('partners.auth.login');
        // });

        // Route::get('login', function () {
        //     return view('partners.auth.login');
        // });
        // Route::get('/', [PartnerAuthController::class, 'login'])->name('login');

        Route::get('login', [PartnerAuthController::class, 'login'])->name('login');
        Route::post('login', [PartnerAuthController::class, 'loginSubmit'])->name('login.submit');

        // Route::get('/partners', [PartnerAuthController::class, 'login']);

        Route::get('forgot-password', function () {
            return view('partners.auth.forgot-password');
        });

        Route::get('forgot-password/success', function () {
            return view('partners.auth.forgot-password-success');
        });

        Route::get('reset-password', function () {
            return view('partners.auth.reset-password');
        });

        Route::get('reset-password/success', function () {
            return view('partners.auth.reset-password-success');
        });

        Route::get('register', [PartnerAuthController::class, 'register'])->name('register');
        Route::post('register', [PartnerAuthController::class, 'registerSubmit'])->name('register.submit');
    });

    Route::get('register/success', [PartnerAuthController::class, 'resisterSuccess'])->name('register.success');
    Route::get('logout', [PartnerAuthController::class, 'logout'])->name('logout');
    // Route::get('register', function () {
    //     return view('partners.auth.register');
    // });

    // Route::get('register/success', function () {
    //     return view('partners.auth.register-success');
    // });

    Route::get('email-confirmation/{id}', function () {
        return view('partners.auth.email-confirmation');
    })->middleware('noindex');
});
