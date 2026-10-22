<?php
namespace App\Repositories\WalletFundingStatuses;


use App\Models\WalletFundingStatus;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface WalletFundingStatusRepositoryInterface extends RepositoryInterface
{
    public function createWalletFundingStatus(array $data) : WalletFundingStatus;
}
