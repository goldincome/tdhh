<?php

use App\Http\Controllers\Partners\ReviewController;
use App\Http\Controllers\Partners\DashboardController;
use App\Http\Controllers\Partners\PartnerPropertyController;
use App\Http\Controllers\Partners\PartnerCommissionController;
use App\Http\Controllers\Partners\PartnerReservationController;
use App\Http\Controllers\Partners\PartnerPropertyEditController;

Route::post('api/fetch-states', [PartnerPropertyController::class, 'fetchState']);
Route::post('api/fetch-cities', [PartnerPropertyController::class, 'fetchCity']);

Route::group(['prefix' => 'partners', 'as' => 'partner.'], function(){

    // Partners account
    Route::group(['middleware' => ['auth:partner', 'noindex']], function(){
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // The authenticated property dashboard. /partners/property is the
        // public host landing page, so the workspace list lives under
        // /partners/dashboard/property instead of shadowing it.
        Route::get('dashboard/property',[PartnerPropertyController::class, 'list'])->name('property.list');
        Route::get('property/create',[PartnerPropertyController::class, 'generalInfo'])->name('property.create.generalInfo');
        Route::post('property/create',[PartnerPropertyController::class, 'generalInfoSubmit'])->name('property.create.generalInfo.submit');
        Route::get('property/create/{id}/options',[PartnerPropertyController::class, 'options'])->name('property.create.options');
        Route::post('property/create/{id}/options',[PartnerPropertyController::class, 'optionsSubmit'])->name('property.create.options.submit');
        Route::get('property/create/{id}/room',[PartnerPropertyController::class, 'room'])->name('property.create.room');
        Route::post('property/create/{id}/room',[PartnerPropertyController::class, 'roomSubmit'])->name('property.create.room.submit');
        Route::get('property/create/{id}/preview',[PartnerPropertyController::class, 'preview'])->name('property.create.preview');
        Route::post('property/create/{id}/preview',[PartnerPropertyController::class, 'previewSubmit'])->name('property.create.preview.submit');
        Route::get('property/create/{id}/success',[PartnerPropertyController::class, 'success'])->name('property.create.success');
        Route::post('property/create/{id}/success',[PartnerPropertyController::class, 'successSubmit'])->name('property.create.success.submit');

        Route::get('/reservations',[PartnerReservationController::class, 'index'])->name('reservation.list');
        Route::get('/reservations/{id}',[PartnerReservationController::class, 'show'])->name('reservation.show');
        Route::get('/reservations/booking/search',[PartnerReservationController::class, 'reservationSearch'])->name('reservation.search');
        Route::get('/reservations/cancel/{id}',[PartnerReservationController::class, 'cancelReservation'])->name('reservation.cancel');

        Route::get('/finance',[PartnerCommissionController::class, 'index'])->name('commission.list');
        Route::get('/finance/commission/search',[PartnerCommissionController::class, 'commissionSearch'])->name('commission.search');

        // Route::get('/partners/reservations', function () {
        //     return view('partners.account.reservations.list');
        // });

        // Route::get('/partners/reservations/{id}', function () {
        //     return view('partners.account.reservations.details');
        // });
        // Route::get('property', function () {
        //     return view('partners.account.property.list');
        // });


        // Route::get('dashboard', function () {
        //     return view('partners.account.dashboard.index');
        // });

        // Route::get('/partners/finance', function () {
        //     return view('partners.account.finance.list');
        // });

        // Route::get('property', function () {
        //     return view('partners.account.property.list');
        // });

        // Route::get('property/create', function () {
        //     return view('partners.account.property.c.general');
        // });

        Route::get('property/create/{id}', function () {
            return view('partners.account.property.create.general');
        });

        // Route::get('property/create/{id}/options', function () {
        //     return view('partners.account.property.create.options');
        // });

        // Route::get('property/create/{id}/room', function () {
        //     return view('partners.account.property.create.room');
        // });

        // Route::get('property/create/{id}/preview', function () {
        //     return view('partners.account.property.create.preview');
        // });

        // Route::get('property/create/{id}/success', function () {
        //     return view('partners.account.property.create.success');
        // });

        Route::get('property/edit/{id}', [PartnerPropertyEditController::class, 'generalInfo'])->name('property.edit.generalInfo');
        Route::put('property/edit/{id}', [PartnerPropertyEditController::class, 'generalInfoSubmit'])->name('property.edit.generalInfo.submit');
        Route::get('property/edit/{id}/options', [PartnerPropertyEditController::class, 'options'])->name('property.edit.options');
        Route::put('property/edit/{id}/options', [PartnerPropertyEditController::class, 'optionsSubmit'])->name('property.edit.options.submit');
        Route::get('property/edit/{id}/room', [PartnerPropertyEditController::class, 'room'])->name('property.edit.room');
        Route::put('property/edit/{id}/room', [PartnerPropertyEditController::class, 'roomSubmit'])->name('property.edit.room.submit');
        Route::get('property/edit/{id}/preview', [PartnerPropertyEditController::class, 'preview'])->name('property.edit.preview');
        Route::get('property/edit/{id}/success', [PartnerPropertyEditController::class, 'success'])->name('property.edit.success');


        Route::post('property/edit/price-settings', [PartnerPropertyEditController::class, 'priceSetting'])->name('property.edit.price-setting.submit');
        Route::post('property/block-rom', [PartnerPropertyEditController::class, 'blockRoom'])->name('property.block-room.submit');
        Route::get('property/sync-calendar/{propertyId}', [PartnerPropertyEditController::class, 'syncCalendar'])->name('property.sync-calendar');
        Route::get('property/download-calender-sync/{propertyId}', [PartnerPropertyController::class, 'downloadSyncLink'])->name('property.download-sync-calendar');
        Route::post('property/integrate-sync-calendar/{propertyId}', [PartnerPropertyEditController::class, 'integrateSyncCalendar'])->name('property.integrate-sync-calendar');

        Route::get('/property/{id}/reviews', [ReviewController::class, 'reviewList']);


        // Route::get('property/edit/{id}', function () {
        //     return view('partners.account.property.edit.general');
        // });

        // Route::get('property/edit/{id}/options', function () {
        //     return view('partners.account.property.edit.options');
        // });

        // Route::get('property/edit/{id}/room', function () {
        //     return view('partners.account.property.edit.room');
        // });

        // Route::get('property/edit/{id}/preview', function () {
        //     return view('partners.account.property.edit.preview');
        // });

        // Route::get('property/edit/{id}/success', function () {
        //     return view('partners.account.property.edit.success');
        // });

        // Route::get('/property/sync-calendar/{id}', function () {
        // return view('partners.account.property.sync-calendar.index');
        // });
    });


});
