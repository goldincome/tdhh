<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PartnerCommission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PartnerCommissionStatus;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\BookingRefunds\BookingRefundRepositoryInterface;
use App\Repositories\PaymentStatuses\PaymentStatusRepositoryInterface;
use App\Repositories\PartnerCommissionStatuses\PartnerCommissionStatusRepositoryInterface;

class AdminReservationController extends Controller
{
    protected $reservationRepo;
    protected $paymentStatusRepo;
    protected $partnerCommissionStatusRepo;
    protected $bookingRefundRepo;

    public function __construct(
        ReservationsRepositoryInterface $reservationRepo,
        PaymentStatusRepositoryInterface $paymentStatusRepo,
        PartnerCommissionStatusRepositoryInterface $partnerCommissionStatusRepo,
        BookingRefundRepositoryInterface $bookingRefundRepo,
    ) {
        $this->reservationRepo = $reservationRepo;
        $this->paymentStatusRepo = $paymentStatusRepo;
        $this->partnerCommissionStatusRepo = $partnerCommissionStatusRepo;
        $this->bookingRefundRepo = $bookingRefundRepo;
    }

    public function index()
    {  
        $data = [];
        $query = $this->reservationRepo->latest();

        if(request()->customer){
            $query->where('user_id', request()->customer);
        }
        if(request()->partner){
            $query->where('partner_id', request()->partner);
        }

        $reservations = $query->paginate(10);
        $paymentStatusModel = $this->paymentStatusRepo->model();

        return view('admin.account.reservations.list', compact('reservations', 'data', 'paymentStatusModel'));
    }

    public function reservationSearch(Request $request)
    {
        $data = $request;
        if ($request->has('search_date')) {
            $request['search_date'] =  Carbon::parse($request->search_date);
        }
        //dd($data->characteristics);
        $reservations = $this->reservationRepo->where('partner_id', auth('partner')->id());

        if ($request->has('search_type') && $request->search_type == 'check-in') {
            $reservations = $reservations->whereDate('proposed_check_in_date', '=', $request->search_date);
        }
        if ($request->has('search_type') && $request->search_type == 'check-out') {
            $reservations = $reservations->whereDate('proposed_check_out_date', '=', $request->search_date);
        }
        if ($request->has('search_type') && $request->search_type == 'reservation-date') {
            $reservations = $reservations->whereDate('created_at', '=', $request->search_date);
        }

        if ($request->has('search_text')) {
            $searchText = "%" . ucwords($request->search_text) . "%";
            $reservations = $reservations->where('property_name', 'LIKE', $searchText)
                ->orWhere('reservation_no', 'LIKE', $searchText);
        }

        if ($request->has('payment_status')) {
            $paymentStatus = explode('|', $request->payment_status);
            $reservations = $reservations->whereIn('payment_status_id', $paymentStatus);
        }

        $reservations = $reservations->paginate();
        $paymentStatusModel = $this->paymentStatusRepo->model();
        //dd($reservations, $paymentStatus, $request->all());

        return view('admin.account.reservations.list', compact('reservations', 'data', 'paymentStatusModel'));
    }


    public function downloadInvoice(string $reservation_id)
    {
        $reservation  = $this->reservationRepo->where('id', $reservation_id)->first();
        $data = [];
        $data['reservation'] = $reservation;
        return $this->reservationRepo->createInvoice($data);
    }

    public function show(string $reservation_id)
    {
        $reservation  = $this->reservationRepo->where('id', $reservation_id)->first();
        return view('admin.account.reservations.details', compact('reservation'));
    }



    public function cancelReservation(string $reservation_id)
    {
        try {
            $reservation  = $this->reservationRepo->where('id', $reservation_id)->first();
            if (!isset($reservation)) {
                return redirect()->back()->with('error', 'No reservation record found');
            }
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'No reservation record found');
        }

        DB::beginTransaction();
        try {
            $reservation->payment_statuses()->attach($this->paymentStatusRepo->model()::CANCELLED_BY_ADMIN);
            $reservation->update(['payment_status_id' => $this->paymentStatusRepo->model()::CANCELLED_BY_ADMIN]);
            $reservation->partnerCommission()->update(['commission_status_id' => $this->partnerCommissionStatusRepo->model()::CANCELLED]);
            $this->bookingRefundRepo->kickStartReservationRefundProcess($reservation, $reservation->customer);
            DB::commit();

            //dd($reservation->created_at,   $reservation->total, $reservation->payment_statuses);
            //free the reservation date for others to book after cancellation
            //send email to customer and partner about the cancellation
            return redirect()->route('admin.reservation.list')->with('message', 'You have cancelled your booking for ' . $reservation->name);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
