<?php // Admin account

use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\FrontPageCityController;
use App\Http\Controllers\Admin\WalletFundingController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\AdminCommissionController;
use App\Http\Controllers\Admin\AdminReservationController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'noindex'],function () {
    Route::get('login', [AdminAuthController::class, 'login'])->name('login');
    Route::post('login', [AdminAuthController::class, 'loginSubmit'])->name('login.submit');
    Route::group(['middleware'=> ['web', 'auth:admin']],function () {
        Route::get('/', function () {
            return redirect()->route('admin.property.list');
        });

        Route::get('/dashboard', function () {
            return view('admin.account.dashboard.index')->name('dashboard');
        });

        Route::get('property', [PropertyController::class, 'list'])->name('property.list');
        Route::get('property/{id}', [PropertyController::class, 'show'])->name('property.show');
        Route::post('property/disable-enable/{propertyId}', [PropertyController::class, 'disableProperty'])->name('disable-enable-property');

        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservation.list');
        Route::get('/reservations/{id}', [AdminReservationController::class, 'show'])->name('reservation.show');
        Route::get('/reservations/booking/search', [AdminReservationController::class, 'reservationSearch'])->name('reservation.search');
        Route::get('/reservations/cancel/{id}', [AdminReservationController::class, 'cancelReservation'])->name('reservation.cancel');

        Route::get('/finance', [AdminCommissionController::class, 'index'])->name('commission.list');
        Route::get('/finance/commission/search', [AdminCommissionController::class, 'commissionSearch'])->name('commission.search');
        Route::post('/finance/{id}/mark-as-paid', [AdminCommissionController::class, 'markCommissionAsPaid'])->name('commission.mark-as-paid');

        Route::get('general-settings', [GeneralSettingController::class, 'index'])->name('general-setting.list');
        Route::get('general-settings/{settingId}', [GeneralSettingController::class, 'edit'])->name('general-setting.edit');
        Route::post('general-settings/{settingId}', [GeneralSettingController::class, 'update'])->name('general-setting.update');
        Route::post('general-settings', [GeneralSettingController::class, 'store'])->name('general-setting.store');
        Route::get('general-settings-create', [GeneralSettingController::class, 'create'])->name('general-setting.create');

        Route::get('bank-accounts', [BankAccountController::class, 'index'])->name('bank-account.list');
        Route::get('bank-accounts/{settingId}', [BankAccountController::class, 'edit'])->name('bank-account.edit');
        Route::post('bank-accounts/{settingId}', [BankAccountController::class, 'update'])->name('bank-account.update');
        Route::post('bank-accounts', [BankAccountController::class, 'store'])->name('bank-account.store');
        Route::get('bank-accounts-create', [BankAccountController::class, 'create'])->name('bank-account.create');


        Route::get('currencies', [CurrencyController::class, 'index'])->name('currency.list');
        Route::get('currencies/{currencyId}', [CurrencyController::class, 'edit'])->name('currency.edit');
        Route::post('currencies/{currencyId}', [CurrencyController::class, 'update'])->name('currency.update');

        Route::get('front-page-cities', [FrontPageCityController::class, 'index'])->name('fpc.list');
        Route::get('front-page-cities/{fpcId}', [FrontPageCityController::class, 'edit'])->name('fpc.edit');
        Route::post('front-page-cities/{fpcId}', [FrontPageCityController::class, 'update'])->name('fpc.update');
        Route::get('states', [FrontPageCityController::class, 'states']);
        Route::post('add-to-fpc/{stateId}', [FrontPageCityController::class, 'addToFPC'])->name('addToFPC');

        Route::get('wallet-funding', [WalletFundingController::class, 'index'])->name('wallet-funding.list');
        Route::get('wallet-funding/{id}/details', [WalletFundingController::class, 'fundingDetails'])->name('wallet-funding.details');
        Route::get('wallet-funding/first-approve/{id}', [WalletFundingController::class, 'firstApproveWalletFunding'])->name('wallet-funding.first.approval');
        Route::get('wallet-funding/approve/{id}', [WalletFundingController::class, 'finalApproveWalletFunding'])->name('wallet-funding.approval');
        Route::post('wallet-funding/reject-funding/{id}', [WalletFundingController::class, 'rejectWalletFunding'])->name('wallet-funding.reject');

        Route::get('refunds', [RefundController::class, 'index'])->name('refund.list');
        Route::get('refunds/approve/{id}', [RefundController::class, 'refundApprove'])->name('refund.approval');
        Route::post('refund/reject-refund/{id}', [RefundController::class, 'rejectRefund'])->name('refund.reject');

        Route::get('customers', [AdminCustomerController::class, 'index'])->name('customer.list');
        Route::get('customers/{id}', [AdminCustomerController::class, 'show'])->name('customer.show');
        Route::post('customers/disable-enable/{id}', [AdminCustomerController::class, 'enableDisable'])->name('customer.disable-enable');

        Route::get('partners', [PartnerController::class, 'index'])->name('partner.list');
        Route::get('partners/{id}', [PartnerController::class, 'show'])->name('partner.show');
        Route::post('partners/disable-enable/{id}', [PartnerController::class, 'enableDisable'])->name('partner.disable-enable');




        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

    });
});

Route::get('/admin/wallet-funding/{id}', function () {
    return view('admin.account.wallet-funding.details');
})->middleware('noindex');