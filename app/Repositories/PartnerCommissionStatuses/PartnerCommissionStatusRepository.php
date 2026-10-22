<?php
namespace App\Repositories\PartnerCommissionStatuses;


use App\Models\PartnerCommissionStatus;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;

/**
 * Class BookingsRefundRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PartnerCommissionStatusRepository extends BaseRepository implements PartnerCommissionStatusRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PartnerCommissionStatus::class;
    }


    public function createPartnerCommissionStatus(array $data): PartnerCommissionStatus
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
