<?php

namespace App\Repositories\States;

use App\Models\State;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\States\StateRepositoryInterface;


/**
 * Class StateRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class StateRepository extends BaseRepository implements StateRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return State::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
