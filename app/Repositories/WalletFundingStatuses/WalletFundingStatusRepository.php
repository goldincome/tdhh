<?php
namespace App\Repositories\WalletFundingStatuses;

use App\Models\WalletFundingStatus;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class WalletFundingStatusRepository extends BaseRepository implements WalletFundingStatusRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WalletFundingStatus::class;
    }


    public function createWalletFundingStatus(array $data): WalletFundingStatus
    {
       return  $this->create($data);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
