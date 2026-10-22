<?php

namespace App\Http\Controllers\Front;

use Carbon\Carbon;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Repositories\Payments\CheckoutService;
use App\Repositories\Payments\PayPalRepository;
use App\Repositories\Payments\PaystackRepository;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Http\Requests\Front\CheckoutReserveRequest;
use App\Http\Requests\Front\PropertyReserveRequest;
use App\Http\Services\Reservations\ReservationService;
use App\Repositories\Payments\WalletPaymentRepository;
use App\Repositories\Properties\PropertyRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\WalletAccounts\WalletAccountRepositoryInterface;

class FrontCartController extends Controller
{
    protected $reservationRepo;
    protected $cartRepo;
    protected $propertyRepo;
    protected $walletAcctRepo;

    public function __construct(
        ReservationsRepositoryInterface $reservationRepo, 
        CartRepositoryInterface $cartRepo, 
        PropertyRepositoryInterface $propertyRepo,
        WalletAccountRepositoryInterface $walletAcctRepo,
        )
    {
        $this->reservationRepo = $reservationRepo;
        $this->cartRepo = $cartRepo;
        $this->propertyRepo = $propertyRepo;
        $this->walletAcctRepo = $walletAcctRepo;
        $this->middleware('auth', ['except' => ['index', 'initiatePropertyReserve']]);   
    }

    public function index()
    {
        $cartItems =  $this->cartRepo;
        foreach($cartItems->getCartItems() as $item){
            
        }
        // Get payment gateways
        $paymentGateways = collect(explode(',', config('payment_gateways.name')))->transform(function ($name) {
            return config($name);
        })->all();
        //$this->cartRepo->clearCart();
        if(is_null($cartItems)){
            $type = 'error';
            $msg ='No property reservation selected';
        }
        else{
            $type = 'success';
            $msg ='complete your reservation by making payments'; 
        }
        $walletBalance = $this->walletAcctRepo->getBalance();
       
        return view('property-reserve',compact('cartItems','item','paymentGateways', 'walletBalance'))->with($type, $msg);
    }

    public function initiatePropertyReserve(PropertyReserveRequest $request){
        $property =  $this->propertyRepo->findOrFail($request->property_id);

        $options = [
            'checkinDate' => $request->checkin_date, 
            'checkoutDate' => $request->checkout_date,
            'num_of_adult' => intval($request->num_of_adult),
            'num_of_children' => intval($request->num_of_children),
            'num_of_pet' => intval($request->num_of_pet),
        ];
        //dd($property->priceRange($request->checkin_date, $request->checkout_date ));
        $numOfGuest = intval($request->num_of_adult); // + intval($request->num_of_children);
        $numOfPet = intval($request->num_of_pet);
        $checkinDate = Carbon::parse($request->checkin_date);
        $checkoutDate = Carbon::parse($request->checkout_date);
        $numOfDaysToBookFor = $checkoutDate->diffInDays($checkinDate);
       
        if($numOfGuest > intval($property->max_guest)){
            return back()->with('error', 'Total number of guest must not be greater than '. $property->max_guest .' on this property.')->withInput();
        }
        if($numOfDaysToBookFor < $property->min_bookable_night)
        {
            return back()->with('error', 'Minimum bookable night for this property must be '. $property->min_bookable_night .' night and above.')->withInput();
        }    
       if($property->notBookable($checkinDate, $checkoutDate)){
        return back()->with('error', 'This date has been booked: '. $checkinDate->format('Y-m-d ') .'to '.$checkoutDate->format('Y-m-d ').'. Choose another date.')->withInput();
       }
       
        $cannotBookPastDate =  now()->diff(Carbon::parse($checkinDate->format('Y-m-d ') . $property->check_in_from))->h;
        $this->cartRepo->clearCart();
        $this->cartRepo->addToCart($property, $numOfDaysToBookFor,  $options);
        return redirect(route('front.cart.index'));
    }

    public function walletStore(WalletPaymentRepository $walletPayService,)
    {   
        $checkoutService = new CheckoutService($this->cartRepo);
        return $checkoutService->handlePayment($walletPayService);
    }

    public function walletCheckout()
    {
        if($this->cartRepo->isCartEmpty())
        {
            return redirect()->route('front.home');
        }
        $cartItems =  $this->cartRepo;
        // Get payment gateways
        $paymentGateways = collect(explode(',', config('payment_gateways.name')))->transform(function ($name) {
            return config($name);
        })->all();
        $walletBalance = $this->walletAcctRepo->getBalance();
        return view('checkout-wallet',compact('cartItems','paymentGateways', 'walletBalance'));
        //$property =  $this->propertyRepo->findOrFail($request->property_id);
    }

    public function store(
        CheckoutReserveRequest $request, 
        PayPalRepository $paypalService, 
        PaystackRepository $paystackService,
        )
    {   
        $checkoutService = new CheckoutService($this->cartRepo);
        $checkoutService->addGuestInfo($request);
        $data = ['date1' => 23, 'date2' => 34, 'date3' => 56];
        //dd($this->cartRepo->getCartItems(), $data);
        if($request->payment === 'paypal')
        {
           return $checkoutService->handlePayment($paypalService);
        }
        elseif($request->payment === 'paystack')
        { 
            return $checkoutService->handlePayment($paystackService);
        }
        elseif($request->payment === 'wallet-payment')
        { 
            return redirect()->route('front.reservation.wallet.checkout');
            // return $checkoutService->handlePayment($walletPayService);
        }
        else{
            return redirect()->route('front.cart.index')->with('error', 'No payment method selected. Select any of the payment method below to make your payment.');
        }
        
    }

    public function paymentSuccess(
        PaystackRepository $paystackService,
        WalletPaymentRepository $walletPayService,
        PayPalRepository $paypalService,
        )
    {
        $checkoutService = new CheckoutService($this->cartRepo);
        $paymentMethod = $checkoutService->getCartPaymentMethodName();
        //dd( $paymentMethod, $this->cartRepo->getCartItems());
        $success  = false;
        if($paymentMethod === 'paystack'){
            $paymentServiceProvider = $paystackService;
        }
        elseif($paymentMethod === 'wallet-payment'){
            $paymentServiceProvider = $walletPayService;
        }
        elseif($paymentMethod === 'paypal'){
            $paymentServiceProvider = $paypalService;
        }
        $success = $checkoutService->handlePaymentSuccess($paymentServiceProvider);
        //dd($this->cartRepo->getCartItems());
        if(!$this->cartRepo->isCartEmpty() && $success){
         $reservation = $checkoutService->processReservation($this->reservationRepo);
        
            if(!isset($reservation)){
                return redirect()->route('front.cart.index')->with('error', 'payment did not go through, try again');
            } 
            //send email to customer and partner
            //update calendar
            //-------------------------------------
            $this->cartRepo->clearCart();
            return redirect()->route('front.checkout.success', ['reservation' =>$reservation->id])->with('successs', 'Paypal payment did not go through, try again');
        }
        return redirect()->route('front.cart.index')->with('error', 'Select any of the payment method below to complete your payment.');
        
    }

    public function checkoutSuccess(Request $request)
    {
        $reservation_id = $request->reservation;
        $reservation = $this->reservationRepo->find($reservation_id);
        $currency_symbol = $this->propertyRepo->getPropertyCurrencySymbol($reservation->property);
        //dd($reservation);
        return view('checkout-status',compact('reservation','currency_symbol'))->with('success', 'Reservation Successful');
    }

    public function sendMailWithPDF(Request $request)
    {
        $data["email"] = "test@gmail.com";
        $data["title"] = "Laravel 8 send email with attachment";
        $data["body"] = "Laravel 8 send email with attachment";

        $pdf = PDF::loadView('booking_pdf', $data);

        Mail::send('booking_email', $data, function ($message) use ($data, $pdf) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"])
                ->attachData($pdf->output(), "test.pdf");
        });

        echo "email send successfully !!";
    }
}


