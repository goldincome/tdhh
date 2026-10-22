<?php

namespace App\Repositories\Reservations;

use App\Models\Reservations;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface ReservationsRepositoryInterface extends RepositoryInterface
{
    public function createReservation(array $data) : Reservations;
}
