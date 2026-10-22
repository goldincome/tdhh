<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cities\CityRepository;
use App\Repositories\States\StateRepository;
use App\Repositories\Refunds\RefundRepository;
use App\Repositories\Countries\CountryRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Properties\PropertyRepository;
use App\Repositories\Cities\CityRepositoryInterface;
use App\Repositories\States\StateRepositoryInterface;
use App\Repositories\Refunds\RefundRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepository;
use App\Repositories\Countries\CountryRepositoryInterface;
use App\Repositories\WalletFunding\WalletFundingRepository;
use App\Repositories\BookingRefunds\BookingRefundRepository;
use App\Repositories\Properties\PropertyRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepository;
use App\Repositories\PaymentStatuses\PaymentStatusRepository;
use App\Repositories\PropertyStatuses\PropertyStatusRepository;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\PartnerCommissions\PartnerCommissionRepository;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\BookingRefunds\BookingRefundRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;
use App\Repositories\PaymentStatuses\PaymentStatusRepositoryInterface;
use App\Repositories\PropertyStatuses\PropertyStatusRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepository;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepository;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind(ReservationsRepositoryInterface::class, ReservationsRepository::class);
        App::bind(CountryRepositoryInterface::class, CountryRepository::class);
        App::bind(StateRepositoryInterface::class, StateRepository::class);
        App::bind(CityRepositoryInterface::class, CityRepository::class);
        App::bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        App::bind(WalletFundingRepositoryInterface::class, WalletFundingRepository::class);
        App::bind(WalletAccountRepositoryInterface::class, WalletAccountRepository::class);
        App::bind(CartRepositoryInterface::class, CartRepository::class);
        App::bind(BookingRefundRepositoryInterface::class, BookingRefundRepository::class);
        App::bind(PaymentStatusRepositoryInterface::class, PaymentStatusRepository::class);
        App::bind(PartnerCommissionRepositoryInterface::class, PartnerCommissionRepository::class);
        App::bind(PartnerCommissionStatusRepositoryInterface::class, PartnerCommissionStatusRepository::class);
        App::bind(WalletFundingStatusRepositoryInterface::class, WalletFundingStatusRepository::class);       
        App::bind(PropertyStatusRepositoryInterface::class, PropertyStatusRepository::class);    #
        App::bind(RefundRepositoryInterface::class, RefundRepository::class);   

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
