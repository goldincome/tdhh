<?php

namespace App\Repositories\WalletFunding;

use App\Models\WalletFunding;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface WalletFundingRepositoryInterface extends RepositoryInterface
{
    public function createWalletFunding(array $data) : WalletFunding;
}
