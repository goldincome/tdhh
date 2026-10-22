<?php
namespace App\Repositories\PaymentStatuses;

use App\Models\PaymentStatus;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PaymentStatuses\PaymentStatusRepositoryInterface;

/**
 * Class BookingsRefundRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PaymentStatusRepository extends BaseRepository implements PaymentStatusRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PaymentStatus::class;
    }


    public function createPaymentStatus(array $data): PaymentStatus
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
