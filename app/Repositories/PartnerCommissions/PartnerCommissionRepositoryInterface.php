<?php
namespace App\Repositories\PartnerCommissions;

use App\Models\PartnerCommission;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface PartnerCommissionRepositoryInterface extends RepositoryInterface
{
    public function createPartnerCommission(array $data) : PartnerCommission;
}
