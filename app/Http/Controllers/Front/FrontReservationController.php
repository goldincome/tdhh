<?php

namespace App\Http\Controllers\Front;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Properties\PropertyRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\WalletFunding\WalletFundingRepositoryInterface;
use App\Repositories\BookingRefunds\BookingRefundRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;
use App\Repositories\PaymentStatuses\PaymentStatusRepositoryInterface;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\WalletFundingStatuses\WalletFundingStatusRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;

class FrontReservationController extends Controller
{
    protected $reservationRepo;
    protected $cartRepo;
    protected $propertyRepo;
    protected $bookingRefundRepo;
    protected $paymentStatusRepo;
    protected $partnerCommissionRepo;
    protected $partnerCommissionStatusRepo;
    protected $walletAcctRepo;
    protected $walletFundingStatusRepo;
    protected $walletFundingRepo;

    public function __construct(
        ReservationsRepositoryInterface $reservationRepo,
        CartRepository $cartRepo,
        PropertyRepositoryInterface $propertyRepo,
        BookingRefundRepositoryInterface $bookingRefundRepo,
        PaymentStatusRepositoryInterface $paymentStatusRepo,
        PartnerCommissionRepositoryInterface $partnerCommissionRepo,
        PartnerCommissionStatusRepositoryInterface $partnerCommissionStatusRepo,
        WalletAccountRepositoryInterface $walletAcctRepo,
        WalletFundingStatusRepositoryInterface $walletFundingStatusRepo,
        WalletFundingRepositoryInterface $walletFundingRepo,
        )
    {
        $this->reservationRepo = $reservationRepo;
        $this->cartRepo = $cartRepo;
        $this->propertyRepo = $propertyRepo;
        $this->bookingRefundRepo = $bookingRefundRepo;
        $this->paymentStatusRepo = $paymentStatusRepo;
        $this->partnerCommissionRepo = $partnerCommissionRepo;
        $this->partnerCommissionStatusRepo = $partnerCommissionStatusRepo;
        $this->walletAcctRepo = $walletAcctRepo;
        $this->walletFundingStatusRepo = $walletFundingStatusRepo;
        $this->walletFundingRepo = $walletFundingRepo;
        $this->middleware('auth'); 
    }

    public function index()
    {
        //$reservations = $this->reservationRepo->where('user_id',auth()->id())->latest()->paginate(10);
        $reservations = auth('web')->user()->reservations()->latest()->paginate(10);
        return view('account.bookings.list',compact('reservations'));
    }


    public function downloadInvoice(string $reservation_id)
    {   $reservation  = $this->reservationRepo->where('id', $reservation_id)->first();
        $reservation['isDownload'] = true;
        $data = [];
        $data['reservation'] = $reservation;
        return $this->reservationRepo->createInvoice($data);
    }

    public function show(string $reservation_id)
    {   $reservation  = $this->reservationRepo->where('id', $reservation_id)->first();
        $reservation['isDownload'] = false;
        
        return view('invoices.booking',compact('reservation'));
    }

    public function reservationDetails(string $reservation_id)
    {   
        $reservation  = $this->reservationRepo->where('id', $reservation_id)->first();
        return view('account.bookings.details',compact('reservation'));
    }

    public function cancelReservation(Request $request)
    {   
        try{      
            $reservation  = $this->reservationRepo->where('user_id',auth('web')->id())->where('id', $request->reservation_id)->first();
            if(!isset($reservation)){
                return redirect()->route('front.account.reservation.index')->with('error', 'No reservation record found');
            }
        }
        catch(Throwable $e){
            return redirect()->route('front.account.reservation.index')->with('error', 'No reservation record found');
        }
       
        // DB::beginTransaction();
        try{
            //dd($reservation, $this->paymentStatusRepo->model()::CANCELLED_BY_CUSTOMER);
            $reservation->payment_statuses()->attach($this->paymentStatusRepo->model()::CANCELLED_BY_CUSTOMER);
            $reservation->update(['payment_status_id' => $this->paymentStatusRepo->model()::CANCELLED_BY_CUSTOMER]);
            $reservation->partnerCommission()->update(['commission_status_id' => $this->partnerCommissionStatusRepo->model()::CANCELLED]);
            
            $walletFund = $this->bookingRefundRepo->kickStartReservationRefundProcess($reservation);
            if($walletFund){
                $this->partnerCommissionRepo->processCancellationPolicyChargeForPartner($reservation, get_settings('partner_commission_percent'));
            }
            //DB::commit();
            //free the reservation date for others to book after cancellation
            //send email to customer and partner about the cancellation
            
            return redirect()->route('front.account.reservation.index')->with('success', 'You have cancelled your booking for ' .$reservation->property_name);
        }
        catch(Throwable $e){
            //DB::rollBack();
            throw $e;
        }
       
    }


    
   
}


