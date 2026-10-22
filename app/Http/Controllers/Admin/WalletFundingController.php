<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\WalletAccount;
use App\Models\WalletFunding;
use App\Http\Controllers\Controller;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

class WalletFundingController extends Controller
{
    protected $walletFundingRepo;
    protected $walletAcctRepo;
    protected $reservationsRepo;
    protected $walletFundingStatusRepo;

    public function __construct(
    WalletFundingRepositoryInterface $walletFundingRepo,
    WalletAccountRepositoryInterface $walletAcctRepo,
    WalletFundingStatusRepositoryInterface $walletFundingStatusRepo,
    )
    {
        $this->walletFundingRepo = $walletFundingRepo;
        $this->walletAcctRepo = $walletAcctRepo;
        $this->walletFundingStatusRepo = $walletFundingStatusRepo;
    }
    
    public function index()
    {
        $data = [];
        $fundings = $this->walletFundingRepo;
        $fundings = $fundings->getInitialQuery($fundings)->latest()->paginate(20);
        $data['totalDeposited'] = $this->walletFundingRepo->getWalletTotalDeposited();
        $data['totalSpent'] = $this->walletFundingRepo->getWalletTotalSpent();
        $data['currentBalance'] = $this->walletFundingRepo->getWalletBalance();
        return view('admin.account.wallet-funding.list', compact('fundings','data'));
    }

    public function fundingDetails(string $funding_id)
    {
        $funding = $this->walletFundingRepo->findOrFail($funding_id);
        //dd($walletFunding);
        return view('admin.account.wallet-funding.details', compact('funding'));
    }

    public function rejectWalletFunding(Request $request, string $funding_id)
    {
        $walletFunding = $this->walletFundingRepo->findOrFail($funding_id);
        $options = $walletFunding->other_details_to_array;
        $options['rejected_approval_by_name'] = auth('admin')->user()->name;
        $options['rejected_approval_by_id'] = auth('admin')->user()->id;
        $options['rejected_approval_reason'] = $request->reject_reason;
        $options['rejected_approval_date'] = now()->format('D d M Y d:i');
        $options = json_encode($options);
        $walletFunding->update(['wallet_funding_status_id' => $this->walletFundingStatusRepo->model()::REJECTED, 'options' => $options]);
        return redirect()->route('admin.wallet-funding.list')->with('success', 'Funding Rejected successfully');
    }

    public function firstApproveWalletFunding(string $funding_id)
    {
        $walletFunding = $this->walletFundingRepo->findOrFail($funding_id);
        $options = $walletFunding->other_details_to_array;
        $options['first_approval_by_name'] = auth('admin')->user()->name;
        $options['first_approval_by_id'] = auth('admin')->user()->id;
        $options['first_approval_date'] = now()->format('D d M Y d:i');
        $options = json_encode($options);

        $walletFunding->update(['first_approval' => true, 'options' => $options,
        'wallet_funding_status_id' => $this->walletFundingStatusRepo->model()::PROCESSING]);
        return redirect()->route('admin.wallet-funding.list')->with('success', 'Approved successfully');
    }

    public function finalApproveWalletFunding(string $funding_id)
    {
        $walletFunding = $this->walletFundingRepo->findOrFail($funding_id);
        $options = $walletFunding->other_details_to_array;
        $options['final_approval_by_name'] = auth('admin')->user()->name;
        $options['final_approval_by_id'] = auth('admin')->user()->id;
        $options['final_approval_date'] = now()->format('D d M Y d:i');
        $options = json_encode($options);

        $balance = $this->walletAcctRepo->generateNewBalance($walletFunding->user_id, $walletFunding->converted_amount, 'credit');
        $this->walletAcctRepo->create(['user_id' => $walletFunding->user_id,'credit' => $walletFunding->converted_amount,
          'balance' => $balance, 'description'=> 'Account Funding']);

        $walletFunding->update(['final_approval' => true, 'options' => $options, 
        'wallet_funding_status_id' => $this->walletFundingStatusRepo->model()::APPROVED]);
        return redirect()->route('admin.wallet-funding.list')->with('success', 'Deposit Approved successfully');
    }


    public function refundList()
    {
        $fundings = $this->walletFundingRepo->where('wallet_funding_status_id', $this->walletFundingStatusRepo->model()::REFUND_REQUESTED)
        ->orWhere('wallet_funding_status_id', $this->walletFundingStatusRepo->model()::REFUNDED)
        ->latest()->paginate(20);
        return view('admin.account.refunds.list', compact('fundings'));
    }

     public function refundApprove(string $funding_id)
     {
        $walletFunding = $this->walletFundingRepo->findOrFail($funding_id);
        $options = $walletFunding->other_details_to_array; 
        $options['refund_approval_by_name'] = auth('admin')->user()->name;
        $options['refund_approval_by_id'] = auth('admin')->user()->id;
        $options['refund_approval_date'] = now()->format('D d M Y d:i');
        $options = json_encode($options);
        $walletFunding->update(['options' => $options,
        'wallet_funding_status_id' => $this->walletFundingStatusRepo->model()::REFUNDED]);
        return redirect()->route('admin.wallet-funding.list')->with('success', 'Refund Approved successfully');
     }
}
