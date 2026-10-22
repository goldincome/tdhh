<?php

namespace App\Models;

use App\Traits\UsesUuid;
use App\Models\BookingRefund;
use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservations extends Model implements Transformable
{
    use HasFactory,  UsesUuid;
    use TransformableTrait;

    protected $fillable = [
        'property_id', 'user_id', 'price', 'num_of_night', 'cleaning_fee', 'parking_fee', 'subtotal', 'total', 'payment_method',
        'proposed_check_in_date', 'proposed_check_in_time', 'proposed_check_out_date', 'proposed_check_out_time', 'actual_check_in_date',
        'actual_check_in_time', 'actual_check_out_date', 'actual_check_out_time', 'adult',
        'children', 'pet', 'message', 'booking_status_id','payment_status_id', 'payment_date', 'max_num_of_guest', 'num_of_guest',
        'room_details', 'parking_details', 'location_details', 'property_rules', 'guest_info', 'property_characteristics',
        'cleaning_options', 'price_details', 'refund_policy', 'partner_id', 'property_name'
    ];

    protected $casts = [
        'proposed_check_in_date' => 'date',
        'proposed_check_out_date' => 'date'
    ];


    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getGuestInformationAttribute()
    {
        $guestInfo = json_decode($this->guest_info);
        return $guestInfo  ?? json_decode(json_encode(['guest_name' => auth()->user()->first_name." ".auth()->user()->last_name,
        'guest_email' => auth()->user()->email, 'guest_phone' => auth()->user()->phone]));
    }

    public function getRoomInformationAttribute()
    {
        $roomDetails = json_decode($this->room_details);
        return $roomDetails;
    }

    public function getPriceInformationAttribute()
    {
        $priceDetails = json_decode($this->price_details);
        return $priceDetails;
    }
    public function getPropertyCharacteristicsInfoAttribute()
    {
        $characteristicsDetails = json_decode($this->property_characteristics);
        return $characteristicsDetails;
    }
    public function getPropertyRulesInfoAttribute()
    {
        $ruleDetails = json_decode($this->property_rules);
        return $ruleDetails;
    }

    public function getPropertyLocationAttribute()
    {
        $locationDetails = json_decode($this->location_details);
        return $locationDetails;
    }
    public function getPartnerRefundPolicyAttribute(){
        return json_decode($this->refund_policy) ?? [];
    }
    public function getParkingInformationAttribute()
    {
        $parkingDetails = json_decode($this->parking_details);
        return $parkingDetails;
    }

    public function getCleaningInformationAttribute()
    {
        $cleaningDetails = json_decode($this->cleaning_options);
        return $cleaningDetails;
    }

    public function getCanCancelBookingAttribute()
    {
        if(now() >= $this->proposed_check_in_date){
            return false;
        }

        if($this->payment_status_id == PaymentStatus::CANCELLED_BY_CUSTOMER
        || $this->payment_status_id == PaymentStatus::CANCELLED_BY_PARTNER
        || $this->payment_status_id == PaymentStatus::CANCELLED_BY_ADMIN){
            return false;
        }
        return true;
    }

    public function getCanRequestRefundAttribute()
    {
        if(isset($this->booking_refund)){
            return false;
        }
        if(now() >= $this->proposed_check_in_date){
            return false;
        }
        if($this->payment_status_id == PaymentStatus::CANCELLED_BY_CUSTOMER
        || $this->payment_status_id == PaymentStatus::CANCELLED_BY_PARTNER
        || $this->payment_status_id == PaymentStatus::CANCELLED_BY_ADMIN
        ){
            return true;
        }

        return false;
    }

    public function payment_statuses(){
        return $this->belongsToMany(PaymentStatus::class , 'payment_status_reservation' ,
        'reservation_id', 'payment_status_id')
         ->withPivot('description')
         ->withTimestamps();
    }

    public function payment_status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function booking_refund(): HasOne
    {
        return $this->hasOne(BookingRefund::class, 'reservation_id');
    }

    public function partnerCommission(): HasOne
    {
        return $this->hasOne(PartnerCommission::class, 'reservation_id');
    }

    public function reservationHasBeenCancelled()
    {
        if(isset($this->payment_statuses)){
            $cancelled = $this->payment_statuses()->whereIn('payment_status_id',
            [PaymentStatus::CANCELLED_BY_CUSTOMER, PaymentStatus::CANCELLED_BY_PARTNER,
            PaymentStatus::CANCELLED_BY_ADMIN])->exists();
            if($cancelled) return true;
        }
        return false;
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'reservation_id');
    }
}
