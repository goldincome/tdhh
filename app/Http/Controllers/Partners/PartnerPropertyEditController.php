<?php

namespace App\Http\Controllers\Partners;

use DateTime;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\PriceRate;
use Illuminate\Http\Request;
use App\Models\Characteristic;
use App\Models\Property\BedType;
use App\Models\Property\Language;
use App\Models\Property\Property;
use App\Models\Property\RoomType;
use App\Models\PropertyBlockRoom;
use App\Models\Property\HouseRule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CalendarSync;
use App\Models\Property\BathroomItem;
use App\Repositories\Countries\CountryRepository;
use App\Repositories\Properties\PropertyRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PartnerPropertyEditController extends Controller
{
    protected $propertyRepo;
    private $countryRepo;

    public function __construct(
        CountryRepository $countryRepo,
        PropertyRepository $propertyRepo,
    ) {
        $this->countryRepo = $countryRepo;
        $this->propertyRepo = $propertyRepo;
    }

    public function generalInfo(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        $countries = $this->countryRepo->all();
        $languages = Language::all();
        return view('partners.account.property.edit.general', compact('countries', 'languages', 'property'));
    }

    public function generalInfoSubmit( Request $request, string $propertyId,){
        $property =  $this->propertyRepo->findOrFail($propertyId);

        DB::beginTransaction();
        try{
            $priceMeta = [
                'price_earning' => $request->price * 65,
                'price_commission ' => $request->price * 15,
                'price_vat ' => $request->price * 20,
                'listing_fee ' => 0,
                'is_cleaning_available' => $request->cleaning_available,

                'cleaning_fee' => $request->cleaning_available == 'yes' ? $request->cleaning_fee : null,
                'cleaning_type' => $request->cleaning_available == 'yes' ? $request->cleaning_type : null,
            ];

            foreach ($request->days_before as $key => $refund) {
                $policy[$key] = [
                    'days_before' => $request->days_before[$key],
                    'allow_refund' => $request->allow_refund[$key],
                    'percent_refund' => $request->allow_refund[$key] == 'yes' ? $request->percent_refund[$key] : 0,
                ];
            }

            // $country = Country::find($request->country_id);
            $state = State::find($request->state_id);
            $city = City::find($request->city_id);


            $data = [
                'name' => $request->name,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                // 'country_name' => $country->name,
                'state_name' => $state->name,
                'city_name' => $city->name,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'min_bookable_night' => $request->min_bookable_night,
                'check_in_from' => $request->check_in_from,
                'check_in_to' => $request->check_in_to,
                'check_out_from' => $request->check_out_from,
                'check_out_to' => $request->check_out_to ?? null,
                'price' => $request->price,
                'price_meta' => json_encode($priceMeta),
                'refund_policy' => json_encode($policy),
            ];

            $property->update($data);

            $property->languages()->delete();
            $property->languages()->createMany($request->languages);

            if ($request->has('cover_image')) {
                $property
                    ->addMediaFromRequest('cover_image')
                    ->toMediaCollection('cover_image');
            }

            if ($request->has('property_images_delete')) {
                Media::whereIn('id', $request->property_images_delete)->delete();
            }

            if ($request->has('property_images')) {
                $property->addMultipleMediaFromRequest(['property_images'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection('property_images');
                });
            }

            DB::commit();

            return redirect()->route('partner.property.edit.options', $property->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function options(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        $characteristics = Characteristic::where('status', true)->get();
        $rules = HouseRule::where('status', true)->get();
        return view('partners.account.property.edit.options', compact('property', 'characteristics', 'rules'));
    }

    public function optionsSubmit(string $propertyId, Request $request)
    {

        DB::beginTransaction();
        try {
            $parkingOptions = [];
            $property =  $this->propertyRepo->findOrFail($propertyId);
            // if($request->parking_is_available == 'yes'){
            $parkingOptions = [
                'parking_is_available' => $request->parking_is_available,
                'parking_location' => $request->parking_location,
                'parking_type' => $request->parking_type,
                'parking_reservation' => $request->parking_reservation,
                'parking_fee' => $request->parking_fee,
                'parking_charge' => $request->parking_charge,
            ];
            // }
            $data = [
                'description' => $request->description,
                'neighborhood' => $request->neighborhood,
                'parking_is_available' => $request->parking_is_available,
                'parking_options' => json_encode($parkingOptions),
            ];
            $property->update($data);
            $property->characteristics()->delete();
            $property->houseRules()->delete();
            $property->characteristics()->createMany($request->characteristics);
            $property->houseRules()->createMany($request->house_rules);
            DB::commit();
            return redirect()->route('partner.property.edit.preview', $property->id);
            // return redirect()->route('partner.property.edit.room', $property);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function room(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        $roomTypes = RoomType::where('status', true)->get();
        $bedTypes = BedType::where('status', true)->get();
        $bathroomItems = BathroomItem::where('status', true)->get();
        return view('partners.account.property.edit.room', compact('property', 'roomTypes', 'bedTypes', 'bathroomItems'));
    }

    public function priceSetting(Request $request){
        $property =  $this->propertyRepo->findOrFail($request->property_id);
        $dateRange = generateDateRange($request->rate_start_date, $request->rate_end_date, $request->affected_days);

        foreach($dateRange as $date){
            PriceRate::updateOrCreate([
                'property_id' => $property->id,
                'date' => $date
            ], [
                'status' => $request->rate_toggle == "on" ? true : false,
                'rate_price' => (float) $request->rate_price,
            ]);
        }

        $this->flashSuccessMessage('price setting updated');
        return back();
    }

    public function blockRoom(Request $request){
        $property =  $this->propertyRepo->findOrFail($request->property_id);
        $dateRange = generateDateRange($request->date_from, $request->date_to, $request->affected_days);
        foreach ($dateRange as $date) {
            PropertyBlockRoom::updateOrCreate([
                'property_id' => $property->id,
                'date' => $date
            ], [
                'status' => $request->status == "on" ? true : false,
            ]);
        }

        $this->flashSuccessMessage('update successful');
        return back();
    }

    public function syncCalendar(Request $request){

        $property =  $this->propertyRepo->findOrFail($request->propertyId);

        return view('partners.account.property.sync-calendar.index', compact('property'));
    }

    public function preview(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        $currency_symbol = $this->propertyRepo->getPropertyCurrencySymbol($property);
        return view('partners.account.property.edit.preview', compact('property', 'currency_symbol'));
    }

    public function previewSubmit(string $propertyId, Request $request)
    {
        // DB::beginTransaction();
        // try {
        //     $property =  $this->propertyRepo->findOrFail($propertyId);
        //     $data = [
        //         'status_id' => PropertyStatus::AVAILABLE
        //     ];
        //     $property->update($data);
        //     DB::commit();

        //     return redirect()->route('partner.property.create.success', $property);
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     throw $th;
        // }
    }

    public function success(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        return view('partners.account.property.edit.success', compact('property'));
    }

    public function integrateSyncCalendar(Request $request){

        CalendarSync::updateOrCreate([
            'property_id' => $request->propertyId,
            'calendar_name' => $request->calendar_name,
        ],[
            'calendar_url' => $request->calendar_url,
        ]);

        $this->flashSuccessMessage('Calendar sync detail created successfully');
        return back();
    }

}
