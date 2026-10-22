<?php

namespace App\Http\Controllers\Front;

use Throwable;

use App\Models\BankAccount;
use App\Models\WalletAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\CreateWalletFundingRequest;
use App\Models\Reservations;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepository;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

class WalletFundingController extends Controller
{
    protected $walletFundingRepo;
    protected $walletAcctRepo;
    protected $reservationsRepo;
    protected $walletFundingStatusRepo;
    protected $partnerCommissionRepo;

    public function __construct(
    WalletFundingRepositoryInterface $walletFundingRepo,
    WalletAccountRepositoryInterface $walletAcctRepo,
    ReservationsRepositoryInterface $reservationsRepo,
    WalletFundingStatusRepositoryInterface $walletFundingStatusRepo,
    PartnerCommissionRepositoryInterface $partnerCommissionRepo,
    )
    {
        $this->walletFundingRepo = $walletFundingRepo;
        $this->walletAcctRepo = $walletAcctRepo;
        $this->reservationsRepo = $reservationsRepo;
        $this->walletFundingStatusRepo = $walletFundingStatusRepo;
        $this->partnerCommissionRepo =  $partnerCommissionRepo;
    }

    public function index()
    { 
        $walletFundings = $this->walletFundingRepo->where('user_id',auth('web')->id())->latest()->paginate(10);
       // $currentBalance = $this->walletAcctRepo->getBalance(auth('web')->id());
        //$totalDeposited = $this->walletAcctRepo->getTotalDeposited(auth('web')->id());
        $totalDeposited = $this->walletFundingRepo->getWalletTotalDeposited(auth('web')->id());
        $totalSpent = $this->walletFundingRepo->getWalletTotalSpent(auth('web')->id());
        $currentBalance = $this->walletFundingRepo->getWalletBalance(auth('web')->id());
        return view('account.wallet-funding.list',compact('walletFundings','currentBalance', 'totalDeposited', 'totalSpent'));
    }

    public function create()
    {
        $bankAccounts = BankAccount::all();
        return view('account.wallet-funding.create', compact('bankAccounts'));
    }

    public function show(string $funding_id)
    {
        $funding = $this->walletFundingRepo->findOrFail($funding_id);
        //dd($funding->other_details->exchange_rate);
        return view('account.wallet-funding.reciept', compact('funding'));
    }

    public function store(CreateWalletFundingRequest $request)
    {   
        $request['user_id'] = auth()->id();
        DB::beginTransaction();
        try {
            $request['wallet_funding_status_id'] = $this->walletFundingStatusRepo->model()::SUBMITTED;
            $walletFund = $walletFunding = $this->walletFundingRepo->createWalletFunding($request->all());
            if($request->has('teller_image')){
                $walletFunding = $walletFunding
                    ->addMediaFromRequest('teller_image')
                    ->toMediaCollection('teller_image');
            }
            $bankDetail = BankAccount::find($request->bank_account_id);
            $data = [];
            $convertedAmount = currency_convert($request->amount_paid, $bankDetail->currency_code, config('currency.default'), false);
            $data['account_number'] = $bankDetail->account_number;
            $data['currency_code'] = $bankDetail->currency_code;
            $data['bank_name'] = ucwords($bankDetail->bank_name);
            $data['converted_amount'] = $convertedAmount;
            $data['converted_currency_code'] = config('currency.default');
            $data['options'] = json_encode(
                [
                    'exchange_rate' => currency()->getCurrencies()
                ]);
            $walletFund->where('id', $walletFund->id)->update($data);
            
            // $balance = $this->walletAcctRepo->generateNewBalance(auth('web')->id(), 0, 'credit');
            // $this->walletAcctRepo->create(['user_id' => auth('web')->id(),'credit' => $convertedAmount,
            //  'balance' => $balance, 'description'=> 'Account Funding']);
            DB::commit();
        }
        catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
           return back()->withError('error occured, contact admin'.$th->getMessage())->withInput();
        }
 
        return redirect()->route('front.account.wallet-funding.index')->with('success','Funding Added Successful.');
    }
    
    public function initiateRefund(string $funding_id)
    {   
        try{
            $walletFunding = $this->walletFundingRepo->findOrFail($funding_id);
            if($walletFunding->converted_amount > $this->walletAcctRepo->getBalance(auth('web')->id())){
                return redirect()->route('front.account.wallet-funding.index')->with('error', "You don't have enough balance in your account for this refund");
            }
            $walletFunding->update(['wallet_funding_status_id' => $this->walletFundingStatusRepo->model()::REFUND_REQUESTED]);
        }
        catch(Throwable $e){
            return redirect()->route('front.account.wallet-funding.index')->with('error', 'No refund record found');
        }
        return redirect()->route('front.account.wallet-funding.index')->with('success', 'Your refund requested is successful.');
    }
}


