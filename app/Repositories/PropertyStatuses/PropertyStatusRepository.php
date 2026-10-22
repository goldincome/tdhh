<?php
namespace App\Repositories\PropertyStatuses;

use App\Models\Property\PropertyStatus;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PropertyStatuses\PropertyStatusRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PropertyStatusRepository extends BaseRepository implements PropertyStatusRepositoryInterface
{
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PropertyStatus::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
