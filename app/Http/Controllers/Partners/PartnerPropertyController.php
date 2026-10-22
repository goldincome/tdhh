<?php

namespace App\Http\Controllers\Partners;

use Auth;
use DateTime;

use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Reservations;
use Illuminate\Http\Request;
use App\Models\Characteristic;
use App\Models\Property\BedType;
use App\Models\Property\Language;
use App\Models\Property\RoomType;
use App\Models\PropertyBlockRoom;
use App\Models\Property\HouseRule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Property\BathroomItem;
use App\Models\Property\PropertyStatus;
use Spatie\IcalendarGenerator\Components\Event;
use App\Repositories\Countries\CountryRepository;
use Spatie\IcalendarGenerator\Components\Calendar;
use App\Repositories\Properties\PropertyRepository;
use App\Repositories\Reservations\ReservationsRepositoryInterface;

class PartnerPropertyController extends Controller
{
    protected $propertyRepo;
    private $countryRepo;
    protected $reservationRepo;

    public function __construct(
        CountryRepository $countryRepo,
        PropertyRepository $propertyRepo,
        ReservationsRepositoryInterface $reservationRepo,
        )
    {
        $this->countryRepo = $countryRepo;
        $this->propertyRepo = $propertyRepo;
    }

    public function list()
    {

        //     $property =  $this->propertyRepo->findOrFail('98773b32-59b4-4d2e-b65f-3f3fc84ae7dc');
        //         $eve =  Calendar::create($property->name)
        //         ->event(Event::create($property->name . ' bookings')
        //             ->startsAt(new DateTime('15 February 2023 15:00'))
        //             ->endsAt(new DateTime('16 February 2023 16:00'))
        //         )
        //         // ->event(Event::create('Creating calender feeds')
        //         //         ->startsAt(new DateTime('6 June 2023 15:00'))
        //         //         ->endsAt(new DateTime('6 June 2023 16:00'))
        //         //         ->organizer('ruben@spatie.be', 'Ruben')
        //         //         ->transparent()
        //         //     )
        //             ->refreshInterval(5)
        //             ->get();

        //             // download ics
        // return response($eve, 200, [
        //     'Content-Type' => 'text/calendar; charset=utf-8',
        //     'Content-Disposition' => 'attachment; filename="apartment-calendar.ics"',
        // ]);


        $properties =  $this->propertyRepo->orderBy('id', 'desc')->all();
        return view('partners.account.property.list', compact('properties'));

    }

    public function fetchState(Request $request)
    {
        $data['states'] = State::where("country_id", $request->country_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function fetchCity(Request $request)
    {
        $data['cities'] = City::where("state_id", $request->state_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function generalInfo()
    {
        $countries = $this->countryRepo->all();
        $languages = Language::all();
        return view('partners.account.property.create.general', compact('countries', 'languages'));
    }

    public function generalInfoSubmit(Request $request)
    {

        DB::beginTransaction();
        try {
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
                    'allow_refund' => $request->allow_refund[$key] ?? 'no',
                    'percent_refund' => (isset($request->allow_refund[$key]) && $request->allow_refund[$key] == 'yes') ? $request->percent_refund[$key ] : 0,
                ];
            }

            $country = Country::find($request->country_id);
            $state = State::find($request->state_id);
            $city = City::find($request->city_id);


            $data = [
                'name' => $request->name,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'country_name' => $country->name,
                'state_name' => $state->name,
                'city_name' => $city->name,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'min_bookable_night' => $request->min_bookable_night,
                'check_in_from' => $request->check_in_from,
                'check_in_to' => $request->check_in_to,
                'check_out_from' => $request->check_out_from,
                'check_out_to' => $request->check_out_to ?? null,
                'status_id' => PropertyStatus::PENDING,
                'listed_by' => Auth('partner')->user()->id,
                'price' => $request->price,
                'price_meta' => json_encode($priceMeta),
                'refund_policy' => json_encode($policy),
            ];
            $property = $this->propertyRepo->create($data);

            $property->languages()->createMany($request->languages);

            if($request->has('cover_image')){
                $property
                    ->addMediaFromRequest('cover_image')
                    ->toMediaCollection('cover_image');
            }

            if($request->has('property_images')){
                $property->addMultipleMediaFromRequest(['property_images'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection('property_images');
                    });

            }

            DB::commit();
            return redirect()->route('partner.property.create.options', $property->id);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function options(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        $characteristics = Characteristic::where('status', true)->get();
        $rules = HouseRule::where('status', true)->get();
        return view('partners.account.property.create.options', compact('property', 'characteristics', 'rules'));
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
            $property->characteristics()->createMany($request->characteristics);
            $property->houseRules()->createMany($request->house_rules);
            DB::commit();
            return redirect()->route('partner.property.create.room', $property);
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
        return view('partners.account.property.create.room', compact('property', 'roomTypes', 'bedTypes', 'bathroomItems'));
    }

    public function roomSubmit(string $propertyId, Request $request)
    {
        DB::beginTransaction();
        try {
            $property =  $this->propertyRepo->findOrFail($propertyId);

            $bathroomItems = $request->bathroom_items ?? $request->bathroom_items_apartment;
            $bedType = $request->bed_types ?? $request->bed_types_apartment;
            $data = [
                'property_type' => $request->property_type,
                'room_type' => $request->room_type,
                'room_quantity' => $request->room_quantity ?? 1,
                'max_guest' => $request->max_guest,
                'room_size' => "$request->room_size",
                // 'room_size' => "$request->room_size $request->room_size_measurement",
                'bathroom_type' => $request->bathroom_type,
                'living_room_qty' => $request->living_room_qty,
                'sofa_availability' => $request->sofa_availability ? 1 : 0,
                'sofa_qty' => $request->sofa_qty,
                'bathroom_qty' => $request->bathroom_qty ?? 1,
                'bedroom_qty' => $request->bedroom_qty ?? 1,
                'bed_types' => json_encode($bedType)

            ];
            $property->update($data);
            $property->bathroomItems()->createMany($bathroomItems);



            DB::commit();

            return redirect()->route('partner.property.create.preview', $property->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function preview(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        return view('partners.account.property.create.preview', compact('property'));
    }

    public function previewSubmit(string $propertyId, Request $request)
    {
        DB::beginTransaction();
        try {
            $property =  $this->propertyRepo->findOrFail($propertyId);
            $data = [
                'status_id' => PropertyStatus::AVAILABLE
            ];
            $property->update($data);
            DB::commit();

            return redirect()->route('partner.property.create.success', $property);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function success(string $propertyId)
    {
        $property =  $this->propertyRepo->findOrFail($propertyId);
        return view('partners.account.property.create.success', compact('property'));
    }

    public function downloadSyncLink(Request $request){

             $property =  $this->propertyRepo->findOrFail($request->propertyId);
                $eve =  Calendar::create($property->name)
                ->event(Event::create($property->name . ' bookings')
                    ->startsAt(new DateTime('21 February 2023 15:00'))
                    ->endsAt(new DateTime('22 February 2023 16:00'))
                )
                ->event(Event::create('Creating calender feeds')
                        ->startsAt(new DateTime('1 June 2023 15:00'))
                        ->endsAt(new DateTime('1 June 2023 16:00'))
                        ->organizer('ruben@spatie.be', 'Ruben')
                        ->transparent()
                    )
                    ->refreshInterval(5)
                    ->get();


        return response($eve, 200, [

            'Content-Type' => 'text/calendar; charset=utf-8',
            // 'Content-Disposition' => 'attachment; filename="apartment-calendar.ics"',
            'Content-Disposition' => 'attachment; filename="apartment-calendar.ics"',
        ]);
    }

    public function listBookingsForCalenderViewAjax(Request $request){
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);
        $property =  $this->propertyRepo->findOrFail($request->propertyId);
        $events = $reservedDates = [];

        $reservations = Reservations::where('property_id', $request->propertyId)
            ->whereBetween('proposed_check_in_date', [$startDate, $endDate])
            ->select('id', 'proposed_check_in_date', 'proposed_check_out_date')->get();

        if($reservations){
            foreach ($reservations as $key => $reservation) {
                $reservedDate = generateDateRange($reservation->proposed_check_in_date, $reservation->proposed_check_out_date);
                $reservedDates = array_merge($reservedDates, $reservedDate);
            }
        }


        $blockEvents = PropertyBlockRoom::where('status', true)-> where('property_id', $request->propertyId)
            ->whereBetween('date', [$startDate, $endDate])->select('date', 'id')->get();

        foreach($blockEvents as $blocked){
            $events[] = [
                "id" => $blocked->id,
                "title" => "Blocked: $property->name",
                "start" => $blocked->date,
                "end" => $blocked->date,
                "event_type" => 'blocked'
            ];
        }

        foreach($reservedDates as $reserved){
            $events[] = [
                "id" => $reserved,
                "title" => "reserved",
                "start" => $reserved,
                "end" => $reserved,
                'event_typed' => 'reserved'
            ];
        }
        return response()->json($events);
    }


}


