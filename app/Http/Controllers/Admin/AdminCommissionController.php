<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;
use Throwable;

class AdminCommissionController extends Controller
{
    protected $partnerCommissionRepo;
    protected $partnerCommissionStatusRepo;

    public function __construct(
        PartnerCommissionRepositoryInterface $partnerCommissionRepo,
        PartnerCommissionStatusRepositoryInterface $partnerCommissionStatusRepo,
    ) {
        $this->partnerCommissionRepo = $partnerCommissionRepo;
        $this->partnerCommissionStatusRepo = $partnerCommissionStatusRepo;
    }

    public function index()
    {
        $data = [];
        $data['date_from'] =  now()->addDays(-5);
        $data['date_to'] = now();  
       
        $data['totalRevenue'] = $this->partnerCommissionRepo->totalRevenue($this->partnerCommissionRepo->getCommissionListQuery($data));
        $data['totalPayout'] = $this->partnerCommissionRepo->totalPayout($this->partnerCommissionRepo->getCommissionListQuery($data));
        $data['totalProcessing'] = $this->partnerCommissionRepo->totalProcessing($this->partnerCommissionRepo->getCommissionListQuery($data));

        $partnerCommissions = $this->partnerCommissionRepo->getCommissionListQuery($data);
        $partnerCommissions = $this->partnerCommissionRepo->latest()->paginate(10);
        $partnerCommissionStatuses = $this->partnerCommissionStatusRepo->all();
        return view('admin.account.finance.list', compact('partnerCommissions', 'data', 'partnerCommissionStatuses'));
    }

    public function commissionSearch(Request $request)
    {
        $query = $this->partnerCommissionRepo->getCommissionSearchQuery($request);
        if($request->has('commission_status')  && isset($request->commission_status)){
            $query = $query->where('partner_commissions.commission_status_id', $request->commission_status);
        }
        $partnerCommissions = $query->latest()->paginate(2);
        $partnerCommissionStatuses = $this->partnerCommissionStatusRepo->all();

        $data = $request;
        $data['totalRevenue'] = $this->partnerCommissionRepo->totalRevenue($this->partnerCommissionRepo->getCommissionSearchQuery($request));
        $data['totalPayout'] = $this->partnerCommissionRepo->totalPayout($this->partnerCommissionRepo->getCommissionSearchQuery($request));
        $data['totalProcessing'] = $this->partnerCommissionRepo->totalProcessing($this->partnerCommissionRepo->getCommissionSearchQuery($request));
    
        return view('admin.account.finance.list', compact('partnerCommissions', 'data', 'partnerCommissionStatuses'));
    }

    public function markCommissionAsPaid(Request $request)
    {
        try{
            $commission = $this->partnerCommissionRepo->findOrFail($request->commission_id);
            $updated = $commission->update(['commission_status_id' => $this->partnerCommissionStatusRepo->model()::PAID]);
            if($updated){
                return redirect()->route('admin.commission.list')->with('success', 'Partner Commission Marked As Paid successful');
            }
        }
        catch(Throwable $e){
            return redirect()->route('admin.commission.list')->with('error', 'No commission record found'. $e->getMessage());
        }
    }
}

