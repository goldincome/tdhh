<?php
namespace App\Repositories\Cart;

use Darryldecode\Cart\Cart;


class MainCart extends Cart
{
    //public static $defaultCurrency;

    protected $session;

    protected $event;

    protected $sessionId;

    public function __construct()
    {
        $this->session = $this->getSession();
        $this->event = $this->getEvents();
        $this->sessionId =  '6u55rews'. uniqid().time();
        parent::__construct($this->session, $this->event, 'tdholiday','6u55rews',config('shopping_cart'));

        //self::$defaultCurrency = currency()->getUserCurrency();
    }

    public function getSession()
    {
        return app()->make('session');
    }

    public function getEvents()
    {
        return app()->make('events');
    }

    /**
     * Get the total price of the items in the cart.
     *
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     * @param float $shipping
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeparator = null, $shipping = 0.00)
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, CartItem $cartItem) {
            return $total + ($cartItem->qty * $cartItem->priceTax);
        }, 0);

        $grandTotal = $total + $shipping;

        return number_format($grandTotal, $decimals, $decimalPoint, $thousandSeparator);
    }
}

