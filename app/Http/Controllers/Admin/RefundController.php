<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Refunds\RefundRepositoryInterface;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

class RefundController extends Controller
{
    protected $walletFundingRepo;
    protected $walletAcctRepo;
    protected $reservationsRepo;
    protected $walletFundingStatusRepo;
    protected $refundRepo;

    public function __construct(
    WalletFundingRepositoryInterface $walletFundingRepo,
    WalletAccountRepositoryInterface $walletAcctRepo,
    WalletFundingStatusRepositoryInterface $walletFundingStatusRepo,
    RefundRepositoryInterface $refundRepo,
    )
    {
        $this->walletFundingRepo = $walletFundingRepo;
        $this->walletAcctRepo = $walletAcctRepo;
        $this->walletFundingStatusRepo = $walletFundingStatusRepo;
        $this->refundRepo = $refundRepo;
    }
    
    public function index()
    {
        $data = [];
        $fundings = $this->walletFundingRepo
        ->where('wallet_funding_status_id', $this->walletFundingStatusRepo->model()::REFUNDED)
        ->orWhere('wallet_funding_status_id', $this->walletFundingStatusRepo->model()::REFUND_REQUESTED)
        ->latest()->paginate(20);
        $data['totalRefunded'] = $this->refundRepo->getTotalRefunded();
        $data['totalRefundPending'] = $this->refundRepo->getTotalRefundPending();
        $data['totalRefundRejected'] = $this->refundRepo->getTotalRefundRejected();
        return view('admin.account.refunds.list', compact('fundings','data'));
    }

    public function refundDetails(string $funding_id)
    {
        $funding = $this->walletFundingRepo->findOrFail($funding_id);
        //dd($walletFunding);
        return view('admin.account.wallet-funding.details', compact('funding'));
    }

    public function rejectRefund(Request $request, string $funding_id)
    {
        $walletFunding = $this->walletFundingRepo->findOrFail($funding_id);
        $options = $walletFunding->other_details_to_array;
        $options['refund_rejected_by_name'] = auth('admin')->user()->name;
        $options['refund_rejected_by_id'] = auth('admin')->user()->id;
        $options['refund_rejected_reason'] = $request->reject_reason;
        $options['refund_rejected_date'] = now()->format('D d M Y d:i');
        $options = json_encode($options);
        $walletFunding->update(['wallet_funding_status_id' => $this->walletFundingStatusRepo->model()::REFUND_REJECTED, 'options' => $options]);
        return redirect()->route('admin.refund.list')->with('success', 'Refund Rejected successfully');
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
        return redirect()->route('admin.refund.list')->with('success', 'Refund Approved successfully');
     }
}
