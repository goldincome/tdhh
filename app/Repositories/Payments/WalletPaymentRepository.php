<?php
namespace App\Repositories\Payments;

use App\Models\WalletAccount;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;


class WalletPaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{

    protected $cartRepo;
    protected $walletAcctRepo;


    public function __construct(CartRepositoryInterface $cartRepo, 
        WalletAccountRepositoryInterface $walletAcctRepo
    )
    {
        $this->cartRepo = $cartRepo;
        $this->walletAcctRepo = $walletAcctRepo;

    }

    public function model()
    {
        return WalletAccount::class;
    }

    public function processPayment() 
    { 
        try{
            if(!$this->cartRepo->countItems() > 0){
                return redirect()->route('front.home');
            }
            $currentBalance = $this->walletAcctRepo->getBalance();

            if($currentBalance >= $this->cartRepo->getCartTotal()){
                $newBalance = $this->walletAcctRepo->generateNewBalance(auth('web')->id(), $this->cartRepo->getCartTotal()   ,  $this->walletAcctRepo->model()::DEBIT_TRANX);
                $data = [];
                $data['user_id'] = auth()->id();
                $data['balance'] = $newBalance;
                $data['debit'] = $this->cartRepo->getCartTotal();
                $data['description'] = 'Payment for Property: '.$this->cartRepo->getPropertyFromCart()->name.'('. $this->cartRepo->getPropertyFromCart()->id .')';
                $this->walletAcctRepo->createWalletAccount($data);
            }
            return redirect()->route('front.reservation.success');
        }catch(\Exception $e) {
            return redirect()->back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }        
    }

    public function cancelPayment()
    {

    }

    public function successPayment(): bool
    {
        if(!$this->cartRepo->countItems() > 0){
            return false;
        }
        return true;
    }
}