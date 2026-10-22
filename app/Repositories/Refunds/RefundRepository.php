<?php
namespace App\Repositories\Refunds;

use App\Models\WalletFunding;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Refunds\RefundRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RefundRepository extends BaseRepository implements RefundRepositoryInterface
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


    public function getTotalRefunded($userId = null)
    {
        $query = $this->query();
       
        if(isset($userId)){
            $userId = $userId ?? auth('web')->id();
            $query = $query->where('user_id', $userId);
        }
        $totalDeposited = $query->where('wallet_funding_status_id', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUNDED)
        ->where('is_refund', true)
        ->sum('converted_amount');
        return $totalDeposited ?? 0;
    }

    public function getTotalRefundPending($userId = null)
    {
        $query = $this->query();
       
        if(isset($userId)){
            $userId = $userId ?? auth('web')->id();
            $query = $query->where('user_id', $userId);
        }
        $totalDeposited = $query->where('wallet_funding_status_id', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUND_REQUESTED)
        ->sum('converted_amount');
        return $totalDeposited ?? 0;
    }

    public function getTotalRefundRejected($userId = null)
    {
        $query = $this->query();
       
        if(isset($userId)){
            $userId = $userId ?? auth('web')->id();
            $query = $query->where('user_id', $userId);
        }
        $totalDeposited = $query->where('wallet_funding_status_id', app(WalletFundingStatusRepositoryInterface::class)->model()::REFUND_REJECTED)
        ->sum('converted_amount');
        return $totalDeposited ?? 0;
    }

   
   
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
