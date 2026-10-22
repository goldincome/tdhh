<?php

namespace App\Repositories\Properties;

use App\Models\Property as ModelsProperty;
use App\Models\Property\Property;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Properties\PropertyRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PropertyRepository extends BaseRepository implements PropertyRepositoryInterface
{
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Property::class;
    }

    public function getPropertyCurrencySymbol($property)
    {
        $accept = config('currency.acceptable_currency');
        if(isset($accept)){
            foreach($accept as $key => $currencyAccept)
            { 
                if( $key === $property->country->currency_code){
                    $symbol = $currencyAccept;
                    break;
                }
                else{
                    $symbol= config('currency.acceptable_currency')[config('currency.default')];
                }
            }
        }
        return $symbol;
    }

    public function checkIfTotalAllowedGuestExceedLimit(int $numOfGuest=1): bool
    {
        if($numOfGuest > $this->model->max_guest){
            dd($numOfGuest, $this->model->max_guest);
            return true;
        }
        return false;
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
