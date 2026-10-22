<?php

namespace App\Repositories\Cart;

use App\Models\User;
use Darryldecode\Cart\Cart;
use App\Models\Property\Property;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CartRepository.
 *
 * @package namespace App\Repositories\Cart;
 */
interface CartRepositoryInterface extends RepositoryInterface
{
    public function addToCart(Property $property, $quantity, $options = []) : Cart;

    public function getCartItems() : Collection;

    public function removeToCart(string $rowId);

    public function countItems() : int;

    public function getCartSubTotal();

    public function getCartTotal();

    public function updateAll(string $rowId, array $values) : bool;

    public function findItem(string $rowId) : Cart;

    public function getTax(int $decimals = 2);

   // public function getShippingFee(Courier $courier);

    public function clearCart();

    public function saveCart(User $user, $instance = 'default');

    public function openCart(User $user, $instance = 'default');

}
