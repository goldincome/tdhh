<?php

namespace App\Repositories\Reservations;

use App\Models\Reservations;
use App\Models\PaymentStatus;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Reservations\ReservationsRepositoryInterface;

/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ReservationsRepository extends BaseRepository implements ReservationsRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Reservations::class;
    }


    public function createReservation(array $data): Reservations
    {
       return  $this->create($data);
    }

    public function createInvoice(array $reservation)
    {  
        return createPDF($reservation, 'invoices.booking',$fileName='td-holiday-homes-booking-invoice');
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

  
}
