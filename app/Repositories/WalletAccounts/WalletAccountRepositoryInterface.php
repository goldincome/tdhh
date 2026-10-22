<?php
namespace App\Repositories\WalletAccounts;

use App\Models\WalletAccount;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface WalletAccountRepositoryInterface extends RepositoryInterface
{
    public function createWalletAccount(array $data) : WalletAccount;
    public function generateNewBalance($userId, $amount_paid=0, string $tranx_type);
}
