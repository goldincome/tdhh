<?php

namespace App\Repositories\WalletFunding;

use App\Models\WalletFunding;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WalletFundingRepository extends BaseRepository implements WalletFundingRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WalletFunding::class;
    }


    public function createWalletFunding(array $data): WalletFunding
    {
       return  $this->create($data);
    }

    public function getWalletTotalDeposited($userId = null)
    {
        $query = $this->query();
       
        if(isset($userId)){
            $userId = $userId ?? auth('web')->id();
            $query = $query->where('user_id', $userId);
        }
        $totalDeposited = $query->where('wallet_funding_status_id', app(WalletFundingStatusRepositoryInterface::class)->model()::APPROVED)
        ->orWhere('wallet_funding_status_id', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUND_REQUESTED)
        ->sum('converted_amount');
        return $totalDeposited ?? 0;
    }

    public function getWalletTotalSpent($userId = null)
    {
        $query = app(ReservationsRepositoryInterface::class);
        if(isset($userId)){
            $query = $query->where('user_id', $userId);
        }
        $totalSpent = $query->where('payment_method', config('wallet-payment.name'))->sum('total');
        return $totalSpent ?? 0;
    }
    public function getWalletBalance($userId = null)
    {
        return $this->getWalletTotalDeposited($userId ) - $this->getWalletTotalSpent($userId );
    }

    public function getInitialQuery($query)
    {
        return $query->where('wallet_funding_status_id', '<>', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUNDED)
        ->where('wallet_funding_status_id', '<>', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUND_REQUESTED)
        ->where('wallet_funding_status_id', '<>', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUND_REJECTED);
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
