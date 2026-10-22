<?php
namespace App\Repositories\BookingRefunds;


use Throwable;
use App\Models\Reservations;
use App\Models\BookingRefund;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;
use App\Repositories\PaymentStatuses\PaymentStatusRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;

/**
 * Class BookingsRefundRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */

class BookingRefundRepository extends BaseRepository implements BookingRefundRepositoryInterface
{
    protected $paymentStatusRepo;
    protected $walletAcctRepo;
    protected $walletFundingStatusRepo;
    protected $walletFundingRepo;

    public function __construct(
        PaymentStatusRepositoryInterface $paymentStatusRepo,
        WalletAccountRepositoryInterface $walletAcctRepo,
        WalletFundingStatusRepositoryInterface $walletFundingStatusRepo,
        WalletFundingRepositoryInterface $walletFundingRepo,
        )
    {
        $this->paymentStatusRepo = $paymentStatusRepo;
        $this->walletFundingRepo = $walletFundingRepo;
        $this->walletFundingStatusRepo = $walletFundingStatusRepo;
        $this->walletAcctRepo = $walletAcctRepo ;
    }
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BookingRefund::class;
    }


    public function createRefund(array $data): BookingRefund
    {
       return  $this->create($data);
    }

   
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getRefundCharge(Reservations $reservation)
    {
        $cancelledDate =  now(); //$reservation->payment_statuses()->where('payment_status_id', $reservation->payment_status_id)->first()->pivot->created_at;
        $numDays = $cancelledDate->diffInDays($reservation->proposed_check_in_date);
        $cancellationCharge = 0;
        $cancelPercentage = 0;
        foreach($reservation->partner_refund_policy as $refundPolicy){
            if(isset($refundPolicy->allow_refund) && $refundPolicy->allow_refund == 'yes'){
                if($numDays <= $refundPolicy->days_before){
                   $cancellationCharge = (floatval($refundPolicy->percent_refund)/100) * floatval($reservation->total);
                   $cancelPercentage = $refundPolicy->percent_refund;
                   break;
                }
                
            }
        }
        return ['cancelFee' => $cancellationCharge, 'cancelPercentage' => $cancelPercentage] ;
    }

    public function initiateRefundRequest(Reservations $reservation)
    {
        $cancellation = $this->getRefundCharge($reservation);
        $refundArray =  [ 
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'refund_status' => 0,
            ];

        if($reservation->payment_status_id == $this->paymentStatusRepo->model()::CANCELLED_BY_CUSTOMER)
        {
            $customerRefundArray =  [ 
            'refund_amount' => $reservation->total - $cancellation['cancelFee'],
            'cancellation_fee' => $cancellation['cancelFee'],
            'percentage_charged' => $cancellation['cancelPercentage'],
            'reason_message' => 'Customer initiated refund',
            'requested_by' => 'Customer',
            ];
            $refundArray = array_merge($refundArray, $customerRefundArray);
        }
        if($reservation->payment_status_id == $this->paymentStatusRepo->model()::CANCELLED_BY_PARTNER)
        {
            $customerRefundArray =  [ 
            'refund_amount' => $reservation->total ,
            'cancellation_fee' => 0,
            'percentage_charged' => 0,
            'reason_message' => 'Partner initiated refund',
            'requested_by' => 'Partner',
            ];
            $refundArray = array_merge($refundArray, $customerRefundArray);
        }
        if($reservation->payment_status_id == $this->paymentStatusRepo->model()::CANCELLED_BY_ADMIN)
        {
            $customerRefundArray =  [ 
            'refund_amount' => $reservation->total ,
            'cancellation_fee' => 0,
            'percentage_charged' => 0,
            'reason_message' => 'Admin initiated refund',
            'requested_by' => 'Admin-'.auth('admin')->user()->id,
            ];
            $refundArray = array_merge($refundArray, $customerRefundArray);
        }
        $refundData = BookingRefund::create($refundArray);
        return $refundData;
    }

    public function kickStartReservationRefundProcess($reservation, $user = null)
    {
        $refundData = $this->initiateRefundRequest($reservation);
        $user = $user ?? auth('web')->user();
        //dd($refundData->refund_amount);
        if(isset($refundData)){
            $data = [];
            $data['user_id'] = $user->id;
            $data['account_number'] = 00000000;
            $data['bank_account_id'] = 00000000;
            $data['currency_code'] = config('currency.default');
            $data['bank_name'] = 'Refund';
            $data['depositor_name'] = $user->full_name;
            $data['converted_amount'] = $refundData->refund_amount;
            $data['amount_paid'] = $refundData->refund_amount;
            $data['is_refund'] = true;
            $data['options'] = json_encode(['reservation_id' => $reservation->id]);
            $data['wallet_funding_status_id'] = $this->walletFundingStatusRepo->model()::APPROVED;
            $data['converted_currency_code'] = config('currency.default');
            DB::beginTransaction();
            try{
                $walletFund = $this->walletFundingRepo->createWalletFunding($data);
                $balance = $this->walletAcctRepo->generateNewBalance($user->id, $refundData->refund_amount, $this->walletAcctRepo->model()::CREDIT_TRANX);
                $this->walletAcctRepo->create(['user_id' => $user->id,'credit' => $refundData->refund_amount,
                    'balance' => $balance, 'description'=> "Refund from cancelled reservation: $reservation->property_name ($reservation->reservation_no)"]);
                DB::commit();
                return $walletFund;
            }
            catch(Throwable $e){
                DB::rollBack();
                throw $e;
            }
        }
    }

}
