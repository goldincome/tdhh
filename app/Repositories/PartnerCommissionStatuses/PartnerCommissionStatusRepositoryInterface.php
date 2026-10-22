<?php
namespace App\Repositories\PartnerCommissionStatuses;

use App\Models\PartnerCommissionStatus;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface PartnerCommissionStatusRepositoryInterface extends RepositoryInterface
{
    public function createPartnerCommissionStatus(array $data) : PartnerCommissionStatus;
}
