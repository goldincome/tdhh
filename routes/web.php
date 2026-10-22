<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Front\FrontCartController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Front\ContactUsController;
use App\Http\Controllers\Front\FrontPropertyController;
use App\Http\Controllers\Front\WalletFundingController;
use App\Http\Controllers\Partners\PartnerAuthController;
use App\Http\Controllers\Front\FrontReservationController;
use App\Http\Controllers\Front\FrontUserAccountController;
use App\Http\Controllers\Front\ReviewController;
use App\Http\Controllers\Partners\PartnerPropertyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/show-now',[FrontReservationController::class, 'show']);
Route::get('/contact',[ContactUsController::class, 'index'])->name('contact-us.index');
Route::post('/contact',[ContactUsController::class, 'store'])->name('contact-us.store');

// General area
Route::group(['as' => 'front.'], function(){
    Route::get('/',[FrontPropertyController::class, 'home'])->name('home');
    
    // Legacy property URL with numeric ID. Redirects to the SEO-friendly /stays route.
    // The redirect is a 301 (permanent) and preserves the query string so filters
    // (check_in_date, check_out_date, etc.) carry over.
    Route::get('/property/{id}', [FrontPropertyController::class, 'legacyPropertyRedirect']);
    
    Route::get('/property',[FrontPropertyController::class, 'propertyList'])->name('list');
    Route::get('/search',[FrontPropertyController::class, 'propertySearch'])->name('property.search');
    Route::get('/property/location/{location}',[FrontPropertyController::class, 'propertyLocationList'])->name('property.location.list');
    Route::post('/property/reserve',[FrontCartController::class, 'initiatePropertyReserve'])->name('property.start.reserve');

    // SEO-friendly property detail page with slug and area.
    Route::get('/stays/{area}/{slug}', [FrontPropertyController::class, 'showBySlug'])->name('stays.show');

    Route::get('/reservation',[FrontCartController::class, 'index'])->name('cart.index');
    Route::post('/reservation',[FrontCartController::class, 'store'])->name('cart.store');
    Route::get('/reservation/success',[FrontCartController::class, 'paymentSuccess'])->name('reservation.success');
    Route::get('/checkout/success',[FrontCartController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/wallet/checkout',[FrontCartController::class, 'walletStore'])->name('wallet.checkout.success');
    Route::get('/reservation/checkout/wallet',[FrontCartController::class, 'walletCheckout'])->name('reservation.wallet.checkout');



    Route::group(['middleware' => ['auth', 'noindex'], 'prefix' => 'account', 'as' => 'account.'], function(){
        Route::get('bookings', [FrontReservationController::class, 'index'])->name('reservation.index');
        Route::get('bookings/show/{id}', [FrontReservationController::class, 'show'])->name('reservation.show');
        Route::get('bookings/detail/{id}', [FrontReservationController::class, 'reservationDetails'])->name('reservation.detail');
        Route::get('bookings/invoice/{id}', [FrontReservationController::class, 'downloadInvoice'])->name('reservation.invoice');
        Route::post('bookings/cancel/{id}',[FrontReservationController::class, 'cancelReservation'])->name('cancel.reservation');

        Route::get('wallet-funding', [WalletFundingController::class, 'index'])->name('wallet-funding.index');
        Route::get('wallet-funding/create', [WalletFundingController::class, 'create'])->name('wallet-funding.create');
        Route::post('wallet-funding/create', [WalletFundingController::class, 'store'])->name('wallet-funding.store');
        Route::get('wallet-funding/{id}', [WalletFundingController::class, 'show'])->name('wallet-funding.show');
        Route::get('wallet-funding/refund-request/{id}', [WalletFundingController::class, 'initiateRefund'])->name('wallet-funding.request.refund');

        Route::get('/', [FrontUserAccountController::class, 'index'])->name('profile.index');
        Route::get('/profile', [FrontUserAccountController::class, 'profile'])->name('profile.view');
        Route::get('/profile/edit', [FrontUserAccountController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/{user}', [FrontUserAccountController::class, 'update'])->name('profile.update');
        Route::get('/settings/change-password', [FrontUserAccountController::class, 'passwordForm'])->name('profile.password.form');
        Route::post('/settings/change-password/{user}', [FrontUserAccountController::class, 'resetPassword'])->name('profile.password.reset');
        Route::get('/settings', [FrontUserAccountController::class, 'settings'])->name('profile.settings.index');
        Route::get('/settings/delete-account', [FrontUserAccountController::class, 'deleteAccount'])->name('profile.delete.account');
        Route::get('/settings/delete-account/{user}', [FrontUserAccountController::class, 'destroy'])->name('profile.account.destroy');
    });
});

Route::get('list-booking-calender-view/{start?}/{end?}', [PartnerPropertyController::class, 'listBookingsForCalenderViewAjax'])->name('booking.list.calender');


// Route::get('/property', function () {
//     return view('property-list');
// });

// Route::get('/property/{id}', function () {
//     return view('property-details');
// });

Route::get('/property/{id}/reviews', function () {
    return view('property-reviews');
})->middleware('noindex');

// Route::get('/property/{id}/reserve', function () {
//     return view('property-reserve');
// });

// Route::get('/checkout/wallet/{id}', function () {
//     return view('checkout-wallet');
// });

// Route::get('/checkout/status/{id}', function () {
//     return view('checkout-status');
// });






// Customer account

// Route::get('/account', function () {
//     return view('account.index');
// });

// Route::get('/account/profile', function () {
//     return view('account.profile.index');
// });

// Route::get('/account/profile/edit', function () {
//     return view('account.profile.edit');
// });

// Route::get('/account/bookings', function () {
//     return view('account.bookings.list');
// });

// Route::get('/account/bookings/{id}', function () {
//     return view('account.bookings.details');
// });

// Route::get('/account/bookings/{id}/review', function () {
//     return view('account.bookings.review');
// });

// Route::get('/account/reviews', function () {
//     return view('account.reviews.list');
// });


Route::get('/account/reviews', [ReviewController::class, 'reviewList'])->name('customer.review.list')->middleware('noindex');
Route::get('/account/reviews/{id}', [ReviewController::class, 'reviewDetails'])->name('customer.review.detail')->middleware('noindex');
Route::get('/account/bookings/{id}/review', [ReviewController::class, 'createReview'])->middleware('noindex');
Route::post('/account/bookings/{id}/review', [ReviewController::class, 'submitReview'])->name('customer.review.submit')->middleware('noindex');



// Route::get('/account/reviews/{id}', function () {
//     return view('account.reviews.details');
// });

// Route::get('/account/settings', function () {
//     return view('account.settings.index');
// });

// Route::get('/account/settings/change-password', function () {
//     return view('account.settings.change-password');
// });

// Route::get('/account/settings/delete-account', function () {
//     return view('account.settings.delete-account');
// });

// Route::get('/account/settings/delete-account/success', function () {
//     return view('account.settings.delete-account-success');
// });





// Public host landing page. This is the only indexable page under
// /partners, so it is deliberately kept outside the noindex group below.
Route::get('/partners/property', function () {
    return view('partners.property-landing', \App\Support\Seo::partnerLanding());
});

// Partner account pages. These are private workspace screens and must never
// be indexed. The noindex directive is applied by middleware so it survives
// any change of view.
Route::middleware('noindex')->group(function () {

Route::get('/partners/register/success', function () {
    return view('partners.auth.register-success');
});

Route::get('/partners/email-confirmation/{id}', function () {
    return view('partners.auth.email-confirmation');
});




// Partners account

Route::get('/partners', function () {
    return redirect('/partners/dashboard');
});

Route::get('/partners/dashboard', function () {
    return view('partners.account.dashboard.index');
});

Route::get('/partners/property/create', function () {
    return view('partners.account.property.create.general');
});

Route::get('/partners/property/create/{id}/options', function () {
    return view('partners.account.property.create.options');
});

Route::get('/partners/property/create/{id}/room', function () {
    return view('partners.account.property.create.room');
});

Route::get('/partners/property/create/{id}/preview', function () {
    return view('partners.account.property.create.preview');
});

Route::get('/partners/property/create/{id}/success', function () {
    return view('partners.account.property.create.success');
});

Route::get('/partners/property/edit/{id}', function () {
    return view('partners.account.property.edit.general');
});

Route::get('/partners/property/edit/{id}/options', function () {
    return view('partners.account.property.edit.options');
});

Route::get('/partners/property/edit/{id}/room', function () {
    return view('partners.account.property.edit.room');
});

Route::get('/partners/property/edit/{id}/preview', function () {
    return view('partners.account.property.edit.preview');
});

Route::get('/partners/property/edit/{id}/success', function () {
    return view('partners.account.property.edit.success');
});

// Route::get('/partners/property/sync-calendar/{id}', function () {
//     return view('partners.account.property.sync-calendar.index');
// });

});








// Admin auth

Route::middleware('noindex')->group(function () {

Route::get('/admin/login', function () {
    return view('admin.auth.login');
});

Route::get('/admin/forgot-password', function () {
    return view('admin.auth.forgot-password');
});

Route::get('/admin/forgot-password/success', function () {
    return view('admin.auth.forgot-password-success');
});

Route::get('/admin/reset-password', function () {
    return view('admin.auth.reset-password');
});

Route::get('/admin/reset-password/success', function () {
    return view('admin.auth.reset-password-success');
});




Route::get('/admin/property/{id}', function () {
    return view('admin.account.property.details');
});



Route::get('/admin/partners', function () {
    return view('admin.account.partners.list');
});

Route::get('/admin/partners/{id}', function () {
    return view('admin.account.partners.details');
});

Route::get('/admin/admins', function () {
    return view('admin.account.admins.list');
});

Route::get('/admin/admins/create', function () {
    return view('admin.account.admins.create');
});

Route::get('/admin/admins/edit/{id}', function () {
    return view('admin.account.admins.edit');
});

Route::get('/admin/messages', function () {
    return view('admin.account.messages.list');
});

Route::get('/admin/messages/{id}', function () {
    return view('admin.account.messages.details');
});

});





// Route::get();




Route::get('/404', function () {
    return view('errors.404');
})->middleware('noindex');




// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');



// invoices
// Route::get('/invoices/booking', function () {
//     return view('invoices.booking');
// });




// emails
//
// These are live previews of the transactional emails. They are not content
// pages and must never be indexed.
Route::middleware('noindex')->group(function () {

Route::get('/emails/contact-us-message', function () {
    return view('emails.php-builds.contact-us-message');
});

Route::get('/emails/customer-payment-confirmation', function () {
    return view('emails.php-builds.customer-payment-confirmation');
});

Route::get('/emails/customer-registration', function () {
    return view('emails.php-builds.customer-registration');
});

Route::get('/emails/customer-reservation-confirmation', function () {
    return view('emails.php-builds.customer-reservation-confirmation');
});

Route::get('/emails/forgot-password', function () {
    return view('emails.php-builds.forgot-password');
});

Route::get('/emails/partner-registration', function () {
    return view('emails.php-builds.partner-registration');
});

Route::get('/emails/partner-reservation-notification', function () {
    return view('emails.php-builds.partner-reservation-notification');
});

});



require __DIR__.'/partners/auth.php';
require __DIR__.'/partners/route.php';
require __DIR__.'/customers/auth.php';
require __DIR__.'/admins/auth.php';
