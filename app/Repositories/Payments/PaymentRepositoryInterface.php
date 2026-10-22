<?php
namespace App\Repositories\Payments;

use Darryldecode\Cart\Cart;
use Prettus\Repository\Contracts\RepositoryInterface;

interface PaymentRepositoryInterface extends RepositoryInterface
{
    public function processPayment();
    public function cancelPayment();
    public function successPayment(): bool;
}