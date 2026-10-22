<?php

namespace App\Repositories\Cart;

use Carbon\Carbon;
use App\Models\User;
use Darryldecode\Cart\Cart;
use App\Models\Property\Property;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Cart\CartRepositoryInterface;

/**
 * Class CartRepositoryEloquent.
 *
 * @package namespace App\Repositories\Cart;
 */
class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    protected $cart;
    protected $session;
    protected $cleaningFee;
    protected $event;
    /**
     * CartRepository constructor.
     * @param ShoppingCart $cart
     */
    public function __construct(MainCart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        //return MainCart::class;
    }

    
    /**
     * @param Property $property
     * @param int $int
     * @param array $options
     * @return CartItem
     */
    public function addToCart(Property $property, $quantity = 1, $options = []) : Cart
    {
        $options = array_merge($this->getPropertyOptions($property, $quantity), $options);
        $checkoutDateForPricing = Carbon::parse($options['checkoutDate'])->addDay(-1);
        $currencyCode = ['currency' => $property->country->currency_code ?? config('currency.default')];
        //dd($options['checkinDate'], $checkoutDateForPricing, $property->priceRange($options['checkinDate'], $checkoutDateForPricing ));
        $options['prices'] = $prices = array_merge($property->priceRange($options['checkinDate'], $checkoutDateForPricing ), $currencyCode);
        $options['quantity'] = $quantity ;
        $options['parking_fee'] = 0; /// no packaging fee needed
        $property->parking_fee = 0;
        //dd($prices['total']);
        return $this->cart->add( [
                                    'id' => $property->id,
                                    'name' => $property->name,
                                    'price' => $prices['total'], //$property->todayPrice,
                                    'quantity' => 1, // do not change
                                    'attributes' => $options,
                                    'associatedModel' => $property
                                    ]);
    }

    public function getPropertyOptions(Property $property, $quantity)
    {
        $options = [];
        $options['is_parking_charge_available'] = $property->is_parking_charge_available ? 'yes': 'no';
        $options['is_parking_charged'] = $property->is_parking_charged ? 'yes': 'no';
        $options['is_cleaning_available'] = $property->is_cleaning_available ? 'yes': 'no';
        $parkingFee = ($property->is_parking_charge_available == 'yes' && $property->is_parking_charged == 'yes') ? $property->parking_fee : 0;
        $options['cleaning_fee'] = 0;
        if($property->is_cleaning_available)
        {
            $options['cleaning_type'] = $property->cleaning_type;
            $options['cleaning_fee'] =  $property->cleaning_type === 'per_stay' ? $property->cleaning_fee : $property->cleaning_fee * $quantity;
        }
       
        $options['parking_fee'] = 0; //$parkingFee;
        $options['payment_method_name'] = null;

        return $options;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCartItems() : Collection
    {
        return $this->cart->getContent();
    }

    /**
     * @param string $rowId
     *
     * @throws ProductInCartNotFoundException
     */
    public function removeToCart(string $rowId)
    {
        try {
            $this->cart->remove($rowId);
        } catch (InvalidRowIDException $e) {
            throw new ProductInCartNotFoundException('Property in cart not found.');
        }
    }

    public function cleaningFee()
    {
        if($this->cart->getContent()->count() > 0){
            foreach($this->cart->getContent() as $row)
            {
                $cleanFee =  $row->attributes->cleaning_fee;
                return $cleanFee;
            }
        }
        return 0;
    }

    public function parkingFee()
    {
        if($this->cart->getContent()->count() > 0){
            foreach($this->cart->getContent() as $row)
            {
                $parkingFee =  $row->attributes->parking_fee;
                return $parkingFee;
            }
        }
        return 0;
    }
    /**
     * Count the items in the cart
     *
     * @return int
     */
    public function countItems() : int
    {
        return $this->cart->getContent()->count();
    }

    /**
     * Get the sub total of all the items in the cart
     *
     * @param int $decimals
     * @return float
     */
    public function getCartSubTotal()
    {
        // dd($this->cart->subtotal($decimals, '.', ''));
        return $this->cart->getSubTotal(true);
    }

    /**
     * Get the final total of all the items in the cart minus tax
     *
     * @param int $decimals
     * @param float $shipping
     * @return float
     */
    public function getCartTotal()
    { 
        if($this->cart->getContent()->count() > 0){
            foreach($this->cart->getContent() as $row)
            {
                $total = $this->cart->getTotal() + $row->attributes->cleaning_fee; //+ $row->attributes->parking_fee;
                return round($total,2);
            }
        }
        return 0;
        //return $this->cart->getTotal() ;
    }

    public function isCartEmpty()
    {
        return $this->cart->isEmpty();
    }

    /**
     * @param string $rowId
     * @param int $quantity
     * @return CartItem
     */
    
    public function updateAll(string $rowId, array $ops) : bool
    {
        return $this->cart->update($rowId, $ops);
    }
    /**
     * Return the specific item in the cart
     *
     * @param string $rowId
     * get CartItem
     */
    public function findItem(string $rowId) : Cart
    {
        return $this->cart->get($rowId);
    }

    /**
     * Returns the tax
     *
     * @param int $decimals
     * @return float
     */
    public function getTax(int $decimals = 2)
    {
        return $this->cart->tax($decimals);
    }

    /**
     * Clear the cart content
     */
    public function clearCart()
    {
        $this->cart->clear();
    }

    /**
     * @param Customer $customer
     * @param string $instance
     */
    public function saveCart(User $user, $instance = 'default')
    {
        $this->cart->instance($instance)->store($user->email);
    }

    /**
     * @param Customer $customer
     * @param string $instance
     * @return Cart
     */
    public function openCart(User $user, $instance = 'default')
    {
        $this->cart->instance($instance)->restore($user->email);
        return $this->cart;
    }

    public function getPropertyFromCart()
    {
        try{
            if($this->cart->getContent()->count() > 0){
                foreach($this->cart->getContent() as $row)
                {
                    return $row->associatedModel;
                }
            }
        }catch(\Throwable $e){
            throw $e;
        }
        
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    
}
