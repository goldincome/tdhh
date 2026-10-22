<?php

namespace App\Models\Property;

use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Partner;
use Carbon\Traits\Date;
use App\Traits\UsesUuid;
use App\Models\PriceRate;
use App\Models\Reservations;
use App\Models\Review;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Property extends Model implements HasMedia
{
    use HasFactory,
        UsesUuid,
        InteractsWithMedia;

        const PROPERTY_TYPE_APARTMENT = 'apartment';
        const PROPERTY_TYPE_SINGLE_ROOM = 'room';


    protected $fillable = [
        'name', 'description', 'neighborhood', 'price', 'price_meta', 'address',
        'city_name', 'postal_code', 'country_id', 'min_bookable_night',
        'state_id', 'listed_by', 'map_address', 'status_id', 'property_type',
        'price_meta', 'cleaning_fee', 'check_in', 'check_out', 'languages',
        'parking_is_available','parking_options', 'room_type_id', 'room_quantity',
        'max_guest', 'room_size', 'bathroom_type', 'check_in_from','check_in_to',
        'check_out_from', 'check_out_to', 'refund_policy', 'sofa_availability',
        'bedroom_qty', 'bathroom_qty', 'country_name', 'state_name', 'city_id'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(PropertyStatus::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(PropertyLanguage::class);
    }

    public function characteristics(): HasMany
    {
        return $this->hasMany(PropertyCharacteristic::class);
    }

    public function houseRules(): HasMany
    {
        return $this->hasMany(PropertyRule::class);
    }

    public function bathroomItems(): HasMany
    {
        return $this->hasMany(PropertyBathroomItem::class);
    }

    public function getCoverImageAttribute(){
        // dd($this->getMedia('cover_image')->first()->getUrl());
        return $this->getMedia('cover_image')->first();
    }

    public function getPropertyImagesAttribute(){
        return $this->getMedia('property_images');
    }

    public function getBedTypeImagesAttribute(){
        return $this->getMedia('bed_types');
    }

    public function getCleaningTypeAttribute(){
        $metaPrice = json_decode($this->price_meta);
        return $metaPrice->cleaning_type ?? null;
    }
    public function getParkingOptionAttribute(){
        $parkingOptions = json_decode($this->parking_options);
        return $parkingOptions ?? null;
    }

    public function getPartnerRefundPolicyAttribute(){
        return json_decode($this->refund_policy) ?? [];
    }

    public function getCleaningFeeAttribute(){
        $metaPrice = json_decode($this->price_meta);
        return $metaPrice->cleaning_fee ?? 0;
    }
    public function getIsCleaningAvailableAttribute(){
        $metaPrice = json_decode($this->price_meta);
        if(isset($metaPrice->is_cleaning_available)){
            return $metaPrice->is_cleaning_available ==='yes' ? true : false;
        }
        return false;
    }
    public function getParkingFeeAttribute(){
        $parkingOptions = json_decode($this->parking_options);
        return $parkingOptions->parking_fee ?? 0;
    }
    public function getIsParkingChargeAvailableAttribute(){
        $parkingOptions = json_decode($this->parking_options);
        if(isset($parkingOptions->parking_is_available)){
            return $parkingOptions->parking_is_available ==='yes' ? true : false;
        }
        return false;
    }

    public function getIsParkingChargedAttribute(){
        $parkingOptions = json_decode($this->parking_options);
        if(isset($parkingOptions->parking_charge)){
            return $parkingOptions->parking_charge ==='yes' ? true : false;
        }
        return false;
    }
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'listed_by');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function priceRates(): HasMany
    {
        return $this->hasMany(PriceRate::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function priceRange($start = null, $end = null, ){
        $dateAndPrice = [];
        $startDate = Carbon::parse($start) ?? Carbon::today();
        $endDate = $end ?? Carbon::parse($end);

        $dateRanges = generateDateRange($startDate, $endDate);
        foreach ($dateRanges as $key => $date) {
            $dateAndPrice[$date->format('Y-m-d')] = $this->price;
        }

        $rate = $this->priceRates()->whereBetween('date', [$startDate, $endDate]);
        $discountDateAndPrice = $rate->pluck('rate_price', 'date')->toArray();
        $priceMerged = array_merge($dateAndPrice, $discountDateAndPrice);
        $total = array_sum($priceMerged);

        return [
            'first_day' => reset($priceMerged),
            'total' => $total,
            'breakdown' =>  $priceMerged
        ];
    }

    public function getTodayPriceAttribute($start = null, $end = null){
        return $this->priceRange()['first_day'];
    }

    public function get_single_reservation(): HasOne
    {
        return $this->hasOne(Reservations::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservations::class);
    }

    public function notBookable($checkinDate, $checkoutDate)
    {
       $notBookable = $this->get_single_reservation()->where('property_id',$this->id)->whereBetween('proposed_check_in_date', [$checkinDate, $checkoutDate])
        ->whereBetween('proposed_check_out_date', [$checkinDate, $checkoutDate])->exists();
        if($notBookable) return true;
        return false;
    }

    /**
     * Get all of the reviews for the Property
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Reservations::class, 'id','reservation_id');
    }
}
