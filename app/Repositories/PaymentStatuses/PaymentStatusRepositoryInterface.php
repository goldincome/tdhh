<?php
namespace App\Repositories\PaymentStatuses;

use App\Models\PaymentStatus;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface PaymentStatusRepositoryInterface extends RepositoryInterface
{
    public function createPaymentStatus(array $data) : PaymentStatus;
}
