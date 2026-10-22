<?php
namespace App\Http\Controllers\Partners;


use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;

class PartnerCommissionController extends Controller
{
    protected $partnerCommissionRepo;
    protected $partnerCommissionStatusRepo;

    public function __construct(
        PartnerCommissionRepositoryInterface $partnerCommissionRepo, 
        PartnerCommissionStatusRepositoryInterface $partnerCommissionStatusRepo,
        )
    {
        $this->partnerCommissionRepo = $partnerCommissionRepo;
        $this->partnerCommissionStatusRepo = $partnerCommissionStatusRepo;
        $this->middleware('auth:partner'); 
    }

    public function index()
    {   //dd($this->totalRevenue());
        $data = [];
        $data['date_from'] =  null; //now()->addDays(-5);
        $data['date_to'] = null; //now();  
       
        $data['totalRevenue'] = $this->partnerCommissionRepo->totalRevenue($this->partnerCommissionRepo->getCommissionListQuery($data, auth('partner')->id()));
        $data['totalPayout'] = $this->partnerCommissionRepo->totalPayout($this->partnerCommissionRepo->getCommissionListQuery($data, auth('partner')->id()));
        $data['totalProcessing'] = $this->partnerCommissionRepo->totalProcessing($this->partnerCommissionRepo->getCommissionListQuery($data, auth('partner')->id()));

        $partnerCommissions = $this->partnerCommissionRepo->getCommissionListQuery($data, auth('partner')->id());
        $partnerCommissions = $partnerCommissions->latest()->paginate(4);//paginate(10);
        $partnerCommissionStatuses = $this->partnerCommissionStatusRepo->all();
        return view('partners.account.finance.list',compact('partnerCommissions', 'data', 'partnerCommissionStatuses'));
    }

    public function commissionSearch(Request $request)
    {     
        $query = $this->partnerCommissionRepo->getCommissionSearchQuery($request, auth('partner')->id());
        if($request->has('commission_status')  && isset($request->commission_status)){
            $query = $query->where('partner_commissions.commission_status_id', $request->commission_status);
        }
        $partnerCommissions = $query->latest()->paginate(2);
        $partnerCommissionStatuses = $this->partnerCommissionStatusRepo->all();

        $data = $request;
        $data['totalRevenue'] = $this->partnerCommissionRepo->totalRevenue($this->partnerCommissionRepo->getCommissionSearchQuery($request, auth('partner')->id()));
        $data['totalPayout'] = $this->partnerCommissionRepo->totalPayout($this->partnerCommissionRepo->getCommissionSearchQuery($request, auth('partner')->id()));
        $data['totalProcessing'] = $this->partnerCommissionRepo->totalProcessing($this->partnerCommissionRepo->getCommissionSearchQuery($request, auth('partner')->id()));
        //dd($partnerCommissions,  $request->all());
        return view('partners.account.finance.list',compact('partnerCommissions', 'data', 'request', 'partnerCommissionStatuses'));
    }


    
}


