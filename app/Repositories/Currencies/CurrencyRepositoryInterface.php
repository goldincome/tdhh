<?php
namespace App\Repositories\Currencies;

use App\Models\Currency;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BookingsRepository.
 *
 * @package namespace App\Repositories;
 */
interface CurrencyRepositoryInterface extends RepositoryInterface
{
    public function createCurrency(array $data) : Currency;

}
