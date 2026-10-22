<?php
namespace App\Repositories\Payments;

use App\Models\PaymentStatus;
use Illuminate\Support\Facades\DB;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\PartnerCommissions\PartnerCommissionRepositoryInterface;
use App\Repositories\Payments\PaymentRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use Throwable;

class CheckoutService
{
 
    protected $cartRepo;
    protected $reservationRepo;

    public function __construct(CartRepositoryInterface $cartRepo
    )
    {
        $this->cartRepo = $cartRepo;
    }

    public function handlePayment(PaymentRepositoryInterface $paymentService)
    {
       return  $paymentService->processPayment($this->cartRepo);
    }

    public function handlePaymentSuccess(PaymentRepositoryInterface $paymentService)
    {
       return  $paymentService->successPayment($this->cartRepo);
    }

    public function processReservation(ReservationsRepositoryInterface $reservationRepo,
    )
    {
        $isEmpty = $this->cartRepo->isCartEmpty();
       //dd($this->cartRepo->getCartItems(), $this->cartRepo->getCartTotal(), $isEmpty);
        if((!$isEmpty))
        {
            foreach($this->cartRepo->getCartItems() as $item){
               //dd( json_encode($this->getLocationDetailsFromCart($item)));
                $data = [
                    'property_id' => $item->associatedModel->id,
                    'property_name' => $item->associatedModel->name, 
                    'user_id' => auth()->id(),
                    'partner_id' => $item->associatedModel->listed_by,
                    'proposed_check_in_date' => $item->attributes->checkinDate,
                    'proposed_check_in_time' => $item->associatedModel->check_in_from,
                    'proposed_check_out_date' => $item->attributes->checkoutDate,
                    'proposed_check_out_time' => $item->associatedModel->check_out_to,
                    'cleaning_fee' => $item->attributes->cleaning_fee,
                    'parking_fee' => $item->attributes->parking_fee,
                    'subtotal' => $this->cartRepo->getCartSubTotal(),
                    'total' => $this->cartRepo->getCartTotal(),
                    'payment_method'=> $this->getCartPaymentMethodName(),
                    'payment_date' => now(),
                    'price' => $item->price,
                    'num_of_night' => $item->attributes->quantity,
                    'num_of_guest' => $item->attributes->num_of_adult,
                    'max_num_of_guest' => $item->associatedModel->max_guest,
                    'room_details' => json_encode($this->getRoomDetailsFromCart($item)),
                    'parking_details' => json_encode($this->getParkingDetailsFromCart($item)),
                    'property_characteristics' => json_encode($this->getRoomCharacteristicsFromCart($item)),
                    'property_rules' => json_encode($this->getRoomHouseRulesFromCart($item)),
                    'adult' => $item->attributes->num_of_adult,
                    'children' => $item->attributes->num_of_children,
                    'pet' => $item->attributes->num_of_pet,
                    'location_details' => json_encode($this->getLocationDetailsFromCart($item)),
                    'guest_info' => json_encode($this->getGuestInfoFromCart($item)),
                    'price_details' => json_encode($item->attributes->prices),
                    'refund_policy' => json_encode($item->associatedModel->partner_refund_policy),
                    'cleaning_options' => json_encode($this->getCleaningOptionsFromCart($item)),
                    'booking_status_id' => 1,
                    'payment_status_id' => PaymentStatus::PAID
                ];
                DB::beginTransaction();
                try{
                    $reservation = $reservationRepo->createReservation($data);
                    $reservation->payment_statuses()->attach(PaymentStatus::PAID);
                    //replace 10 with percent commission set by admin
                    app(PartnerCommissionRepositoryInterface::class)->processPartnerCommission($reservation, get_settings('partner_commission_percent'));
                    DB::commit();
                    return $reservation;
                }
                catch(Throwable $e){
                    DB::rollback();
                    throw $e;
                }
                
            }
        }
        else{
            return;
        }
        
    }
    
    public function getCartPaymentMethodName()
    {
        if($this->cartRepo->countItems()  > 0){
            foreach($this->cartRepo->getCartItems() as $row)
            {   
                return $row->attributes->payment_method_name;
                
            }
        }
        return;
    }

    public function getLocationDetailsFromCart($item)
    {
        return [
            'neighborhood' => $item->associatedModel->neighborhood,
            'address' => $item->associatedModel->address,
            'city' => $item->associatedModel->city_name,
            'state' => $item->associatedModel->state_name,
            'country' => $item->associatedModel->country_name,
            'postal_code' => $item->associatedModel->postal_code,
        ];
    }

    public function getCleaningOptionsFromCart($item)
    {
        return [
            'cleaning_type' => $item->associatedModel->cleaning_type,
            'is_cleaning_available' => $item->associatedModel->is_cleaning_available,
            'cleaning_fee' => $item->associatedModel->cleaning_fee,
        ];
    }

    public function getGuestInfoFromCart($item)
    {
        return [
            'guest_name' => isset($item->attributes->guest_name) ?: auth()->user()->first_name." ".auth()->user()->last_name,
            'guest_email' =>  isset($item->attributes->guest_email) ?: auth()->user()->email,
            'guest_phone' =>  isset($item->attributes->guest_phone) ?: auth()->user()->phone,
        ];
    }

    public function getParkingDetailsFromCart($item)
    {
        $parkingOptions = json_decode($item->associatedModel->parking_options, true);
        $guestParking = ['guest_car_parking_choice' => $item->attributes->guest_car_parking];
       return array_merge($parkingOptions, $guestParking);
    }

    public function getRoomDetailsFromCart($item)
    {
        $roomDetails = [
            'name' => $item->name,
            'bedroom_qty' => $item->associatedModel->bedroom_qty,
            'bathroom_qty' => $item->associatedModel->bathroom_qty,
            'property_type' => $item->associatedModel->property_type,
            'bed_types' => $item->associatedModel->bed_types,
            'room_quantity' => $item->associatedModel->room_quantity,
            'max_guest' => $item->associatedModel->max_guest,
            'room_size' => $item->associatedModel->room_size,
            'bathroom_type' => $item->associatedModel->bathroom_type,
            'living_room_qty' => $item->associatedModel->living_room_qty,
            'sofa_availability' => $item->associatedModel->sofa_availability,
            'sofa_qty' => $item->associatedModel->sofa_qty,
        ];
       return $roomDetails;
    }

    public function getRoomHouseRulesFromCart($item)
    {
        $output = [];
        foreach ($item->associatedModel->houseRules as $houseRule){
            $output[] = $houseRule->rule->name;
        }
        return $output;
    }

    public function getRoomCharacteristicsFromCart($item)
    {
        $output = [];
        foreach ($item->associatedModel->characteristics as $characteristic){
            $output[] = $characteristic->characteristic->name;
        }
        return $output;
    }

    public function addGuestInfo($request)
    {
        if($this->cartRepo->countItems() > 0){
            foreach($this->cartRepo->getCartItems() as $row)
            {   
                $options = $row->attributes;
                $options['guest_name'] = $request->guest_name;
                $options['guest_email'] = $request->guest_email;
                $options['guest_phone'] = $request->guest_phone;
                $options['payment_method_name'] = $request->payment;
                $options['guest_car_parking'] = isset($request->guest_car_parking) ? $request->guest_car_parking : 'no';
                $parkingFee = ($row->associatedModel->is_parking_charge_available  && $row->associatedModel->is_parking_charged && $request->guest_car_parking == 'yes') ? $row->associatedModel->parking_fee : 0;
                $options['parking_fee'] = $parkingFee;
                return $this->cartRepo->updateAll($row->id, ['attributes' => $options]);
                
            }
        }
        return;
        
    }

    
}

