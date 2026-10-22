<?php
namespace App\Repositories\PartnerCommissions;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PartnerCommission;
use App\Models\PartnerCommissionStatus;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BookingRefunds\BookingRefundRepositoryInterface;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;

/**
 * Class BookingsRefundRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PartnerCommissionRepository extends BaseRepository implements PartnerCommissionRepositoryInterface
{



    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PartnerCommission::class;
    }


    public function createPartnerCommission(array $data): PartnerCommission
    {
       return  $this->create($data);
    }

    public function processPartnerCommission($reservation, float $percent)
    {
        $commission = round(($percent/100) * $reservation->total,2);
        $data = [
            'partner_id' => $reservation->partner_id,
            'reservation_id' => $reservation->id,
            'commission_amt' => $commission,
            'payout_amt' => $reservation->total - $commission,
            'commission_percentage' => $percent,
            'commission_status_id' => app(PartnerCommissionStatusRepositoryInterface::class)->model()::IN_PROGRESS
        ];
        $partnerCommission = PartnerCommission::create($data);
       
    }

    public function processCancellationPolicyChargeForPartner($reservation, float $percent)
    {
        $charge = app(BookingRefundRepositoryInterface::class)->getRefundCharge($reservation);
        $commission = round(($percent/100) * $charge['cancelFee'], 2);
        $data = [
            'partner_id' => $reservation->partner_id,
            'reservation_id' => $reservation->id,
            'commission_amt' => $commission,
            'payout_amt' => $charge['cancelFee'] - $commission,
            'commission_percentage' => $percent,
            'commission_status_id' => app(PartnerCommissionStatusRepositoryInterface::class)->model()::IN_PROGRESS
        ];
        $partnerCommission = PartnerCommission::create($data);
       
    }
    
    public function getCommissionSearchQuery(Request $request, $partnerId = null)
    {
        if($request->has('date_from') && $request->has('date_to')  && isset($request->date_to) && isset($request->date_from)){
            $request['date_from'] =  Carbon::parse($request->date_from);
            $request['date_to'] =  Carbon::parse($request->date_to);
        }

        //$partnerCommissions = $this->partnerCommissionRepo->where('partner_id',auth('partner')->id());
        $partnerCommissions = $this->select('partner_commissions.*');
        if($partnerId){
            $partnerCommissions = $partnerCommissions->where('partner_commissions.partner_id',$partnerId);
        }
        // if($request->has('commission_status')  && isset($request->commission_status)){
        //     $partnerCommissions = $partnerCommissions->where('partner_commissions.commission_status_id', $request->commission_status);
        // }
        if($request->has('search_text') && isset($request->search_text)){ 
            $searchText = "%".ucwords($request->search_text)."%";
            $partnerCommissions = $partnerCommissions->join('reservations', 'reservations.id', '=', 'partner_commissions.reservation_id') ;
            $partnerCommissions = $partnerCommissions->where('reservations.property_name', 'LIKE', $searchText)
            ->orWhere('reservations.reservation_no', 'LIKE', $searchText);
        }
        if($request->has('date_from') && $request->has('date_to')  && isset($request->date_to) && isset($request->date_from)){
            $partnerCommissions = $partnerCommissions->whereDate('partner_commissions.created_at', '>=', $request->date_from)
            ->whereDate('partner_commissions.created_at', '<=', $request->date_to);
        }

        return  $partnerCommissions;
    }

    public function getCommissionListQuery(array $data, $partnerId = null)
    {
        $query = $this->query();
        if(isset($data) && isset($data['date_from']) && isset($data['date_to'])){
            $query =  $query->whereDate('created_at', '>=', $data['date_from'])->whereDate('created_at', '<=', $data['date_to']);
        }
        if($partnerId){
            $query = $query->where('partner_id',$partnerId );
        }
        return $query;
    }

    public function totalRevenue($query)
    {
        $sum = $query->where('partner_commissions.commission_status_id', app(PartnerCommissionStatusRepositoryInterface::class)->model()::IN_PROGRESS)
        ->orWhere('partner_commissions.commission_status_id', app(PartnerCommissionStatusRepositoryInterface::class)->model()::PAID)
        ->sum('partner_commissions.payout_amt');
        return $sum;
    }

    public function totalPayout($query)
    {   
        return  $query->where('partner_commissions.commission_status_id', app(PartnerCommissionStatusRepositoryInterface::class)->model()::PAID)
       ->sum('partner_commissions.payout_amt') ?? 0;

    }
    
    public function totalProcessing($query)
    {
        return $query->where('partner_commissions.commission_status_id', app(PartnerCommissionStatusRepositoryInterface::class)->model()::IN_PROGRESS)
       ->sum('partner_commissions.payout_amt') ?? 0;

    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
