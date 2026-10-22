<?php
namespace App\Repositories\Payments;
use Paystack;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Cart\CartRepositoryInterface;


class PaystackRepository extends BaseRepository implements PaymentRepositoryInterface
{

    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function model()
    {
        return Paystack::class;
    }

    public function processPayment() 
    {
        try{
            if(!$this->cartRepo->countItems() > 0){
                return redirect()->route('front.home');
            }
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return redirect()->back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }        
    }

    public function cancelPayment()
    {

    }

    public function successPayment(): bool
    {
        $paymentDetails = Paystack::getPaymentData();
        if ($paymentDetails['status'] == true && $paymentDetails['data']['status'] =='success') {
           
            return true;
        }
        return false;
    }
}