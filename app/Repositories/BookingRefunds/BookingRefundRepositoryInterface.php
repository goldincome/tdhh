<?php
namespace App\Repositories\BookingRefunds;


use App\Models\BookingRefund;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface BookingRefundRepositoryInterface extends RepositoryInterface
{
    public function createRefund(array $data) : BookingRefund;
}
