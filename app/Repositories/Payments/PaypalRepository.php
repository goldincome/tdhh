<?php
namespace App\Repositories\Payments;

use Srmklive\PayPal\Facades\PayPal;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Cart\CartRepositoryInterface;

class PayPalRepository extends BaseRepository implements PaymentRepositoryInterface
{
    protected $cartRepo;
    protected $paypalProvider;
    protected $propertyRepo;
    protected $reservationRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {   
        $this->cartRepo = $cartRepo;
        PayPal::setProvider();
        $this->paypalProvider = PayPal::getProvider();
        $this->paypalProvider->setApiCredentials(config('paypal'));
        $this->paypalProvider->setAccessToken( $this->paypalProvider->getAccessToken());
        
    }

    public function model()
    {
        return PayPal::class;
    }

    public function processPayment()
    {
        if(!$this->cartRepo->countItems() > 0){
            return redirect()->route('front.home');
        }
        foreach($this->cartRepo->getCartItems() as $item){}
        $currecyCode = currency()->getUserCurrency()==='NGN' ? $item->associatedModel->country->currency_code: currency()->getUserCurrency();
        try{
            $order = $this->paypalProvider->createOrder([
                "intent"=> "CAPTURE",
                "purchase_units"=> [[
                    "amount"=> [
                        "currency_code"=> $currecyCode ,
                        "value"=> number_format(currency_convert($this->cartRepo->getCartTotal(),$item->associatedModel->country->currency_code, currency()->getUserCurrency(), false),2)
                    ]
                ]],
                'application_context' => [
                    'cancel_url' => config('paypal.cancel_url'),
                    'return_url' => config('paypal.success_url')
            ]
            ]);

            return redirect($order['links'][1]['href'])->send();
        }
        catch(\Throwable $e){
            throw $e->getMessage();
        }
    }
    
    public function cancelPayment() 
    {

    }

    public function successPayment(): bool
    {
         // Get PaymentOrder using our transaction ID
        try{
            $response = $this->paypalProvider->capturePaymentOrder(request()->token);
        }catch(\Exception $e) {
            return redirect()->back()->with('message', 'Paypal error occured, try again.'. $e->getMessage());
        }        
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            //$this->cartRepo->clearCart();  
            //dd($response );
            return true;
        }
 
         return false;
    }

    
}