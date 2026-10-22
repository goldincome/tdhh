<?php

namespace App\Http\Controllers\Front;

use Carbon\Carbon;
use App\Models\Reservations;
use Illuminate\Http\Request;
use App\Models\FrontPageCity;
use App\Models\Characteristic;
use App\Models\PropertyBlockRoom;
use App\Models\Property\HouseRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Repositories\Currencies\UserCurrency;
use App\Repositories\Properties\PropertyRepository;
use App\Repositories\States\StateRepositoryInterface;
use App\Repositories\Countries\CountryRepositoryInterface;
use App\Repositories\Reservations\ReservationsRepositoryInterface;
use App\Repositories\PropertyStatuses\PropertyStatusRepositoryInterface;
use App\Support\Seo;

class FrontPropertyController extends Controller
{

    protected $propertyRepo;
    protected $countryRepo;
    protected $stateRepo;
    protected $reservationRepo;
    protected $propertyStatusRepo;

    public function __construct(PropertyRepository $propertyRepo,
        CountryRepositoryInterface $countryRepo, StateRepositoryInterface $stateRepo,
        ReservationsRepositoryInterface $reservationRepo,
        PropertyStatusRepositoryInterface $propertyStatusRepo)
    {
        $this->propertyRepo = $propertyRepo;
        $this->countryRepo = $countryRepo;
        $this->stateRepo = $stateRepo;
        $this->reservationRepo = $reservationRepo;
        $this->propertyStatusRepo = $propertyStatusRepo;
    }

    public function home(){
        $data = [];
        $properties =  $this->propertyRepo->where('status_id',  $this->propertyStatusRepo->model()::AVAILABLE)->get();
        $fPCities = FrontPageCity::where('status', true)->limit(8)->get();
        return view('home', array_merge(compact('properties', 'fPCities', 'data'), Seo::home()));
    }

    public function propertyList(){
        $properties =  $this->propertyRepo->where('status_id',  $this->propertyStatusRepo->model()::AVAILABLE);
        if(request()->has('sort')){

            $properties = $this->sortProperty($properties, request()->sort);
        }
        $properties =  $properties->paginate();
        $characteristics = Characteristic::all();
        $houseRules = HouseRule::all();
        return view('property-list', array_merge(
            compact('properties', 'characteristics', 'houseRules'),
            Seo::listing()
        ));
    }

    public function propertyLocationList(string $location)
    {
        $data = [];
        $properties =  $this->propertyRepo->where('state_name',ucwords($location))->where('status_id',  $this->propertyStatusRepo->model()::AVAILABLE)
                        ->orWhere(['state_name'=> ucwords($location), 'city_name' => $location]);
        if(request()->has('sort')){

            $properties = $this->sortProperty($properties, request()->sort);
        }
        $properties =  $properties->paginate();
        $characteristics = Characteristic::all();
        $houseRules = HouseRule::all();
        return view('property-list', array_merge(
            compact('properties', 'data', 'characteristics', 'houseRules'),
            Seo::locationListing($location)
        ));
    }

    public function propertySearch()
    {
        $request = $data = request();
        //dd($request->all());
        $request['check_in_date'] =  Carbon::parse($request->check_in_date)->format('Y-m-d');
        $request['check_out_date'] =  Carbon::parse($request->check_out_date)->format('Y-m-d');
        //dd($data->num_of_adult);
        $properties = $this->propertyRepo->select('properties.*')->where('properties.status_id',  $this->propertyStatusRepo->model()::AVAILABLE);

        if($request->has('check_in_date') && $request->has('check_out_date')){
            $bookedPropertyIds = [];
            if($this->reservationRepo->exists()){
                $bookedPropertyIds = $this->reservationRepo->whereBetween('proposed_check_in_date', ["$request->check_in_date", "$request->check_out_date"])
                ->orWhereBetween('proposed_check_out_date', [$request->check_in_date, $request->check_out_date])
                ->pluck('property_id');

                $properties = $properties->whereNotIn('properties.id',$bookedPropertyIds);
            }

            $blockedPropertyIds = PropertyBlockRoom::where('status', true)
            ->whereBetween('date', [$request->check_in_date, $request->check_out_date])->distinct('property_id')->pluck('property_id');
            if($blockedPropertyIds){
                $properties = $properties->whereNotIn('properties.id',$blockedPropertyIds);
            }

        }

        if($request->has('parking_is_available')){
            $properties = $properties->where('properties.parking_is_available', '=', 'yes');
        }
        if($request->has('price_from') && $request->has('price_to')){
            $properties = $properties->where('properties.price', '>=', $request->price_from)
            ->where('properties.price', '<=', $request->price_to);
        }
        if($request->has('num_of_adult')){
            $numOfGuest = intval($request->num_of_adult);
            $properties = $properties->where('properties.max_guest', '>=', $numOfGuest);
        }
        if($request->has('location')){
            $location = '%'.ucwords($request->location).'%';
            $properties = $properties->where(function($query) use ($location){
                $query->where('properties.country_name','LIKE', $location)
                ->orWhere('properties.state_name', 'LIKE', $location)
                ->orWhere('properties.city_name', 'LIKE', $location)
                ->orWhere('reference', 'LIKE', $location);
            });
        }
        if($request->has('characteristics')){
            $properties = $properties->join('property_characteristics', 'property_characteristics.property_id', '=', 'properties.id')
            ->whereIn('property_characteristics.characteristic_id', $request->characteristics);
        }
        if($request->has('house_rules')){
            $properties = $properties->join('property_rules', 'property_rules.property_id', '=', 'properties.id')
            ->whereIn('property_rules.rule_id', $request->house_rules);
        }
        if($request->has('sort')){
            $properties = $this->sortProperty($properties, $request->sort);
        }
        $properties = $properties->distinct('properties.reference')
            ->paginate();
        //dd($properties, url()->full(), url()->current(), $request->sort);
        $characteristics = Characteristic::all();
        $houseRules = HouseRule::all();
        return view('property-list', array_merge(
            compact('properties', 'data', 'characteristics', 'houseRules'),
            Seo::searchResults()
        ));
    }

    public function details(Request $request, string $propertyId){
        $data = $request;
        $property =  $this->propertyRepo->findOrFail($propertyId);
        $property->parking_information = json_decode($property->parking_options);
        $currency_symbol = $this->propertyRepo->getPropertyCurrencySymbol($property);
        $request['check_in_date'] =  Carbon::parse($request->check_in_date ?? now()->addDays(3));
        $request['check_out_date'] =  Carbon::parse($request->check_out_date ?? now()->addDays(6));
        // $output = $this->getAbokiFxExchangeRates();
        //dd($property->parking_information );
        return view('property-details', array_merge(
            compact('property','currency_symbol', 'data'),
            Seo::property($property, url()->current(), $this->propertyCurrencyCode($property))
        ));
    }

    /**
     * The ISO currency code for a property, used by the structured data.
     *
     * @param  \App\Models\Property\Property  $property
     * @return string
     */
    protected function propertyCurrencyCode($property): string
    {
        return optional($property->country)->currency_code ?: config('currency.default', 'GBP');
    }

    public function sortProperty($query, string $text)
    {
        if (!empty($text)) {

            if ($text =="asc") {
                $query = $query->orderBy('properties.name', 'asc');
            }
            elseif ($text =="desc") {
                $query = $query->orderBy('properties.name', 'desc');
            }
            elseif ($text =="new") {
                $query = $query->orderBy('properties.created_at', 'desc');
            }
            elseif ($text =="old") {
                $query = $query->orderBy('properties.created_at', 'asc');
            }
            elseif ($text =="price-low") {
                $query = $query->orderBy('properties.price', 'asc');
            }
            elseif ($text =="price-high") {
                $query = $query->orderBy('properties.price', 'desc');
            }
            else{
                $query = $query->orderBy('properties.name', 'asc');
            }


            return $query;
        } else {
            return $query->orderBy('properties.name', 'asc');
        }
    }

    public function timeZoneList()
    {
        return $timezoneList =  array (
            '(UTC-11:00) Midway Island' => 'Pacific/Midway',
            '(UTC-11:00) Samoa' => 'Pacific/Samoa',
            '(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
            '(UTC-09:00) Alaska' => 'US/Alaska',
            '(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles',
            '(UTC-08:00) Tijuana' => 'America/Tijuana',
            '(UTC-07:00) Arizona' => 'US/Arizona',
            '(UTC-07:00) Chihuahua' => 'America/Chihuahua',
            '(UTC-07:00) La Paz' => 'America/Chihuahua',
            '(UTC-07:00) Mazatlan' => 'America/Mazatlan',
            '(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain',
            '(UTC-06:00) Central America' => 'America/Managua',
            '(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central',
            '(UTC-06:00) Guadalajara' => 'America/Mexico_City',
            '(UTC-06:00) Mexico City' => 'America/Mexico_City',
            '(UTC-06:00) Monterrey' => 'America/Monterrey',
            '(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
            '(UTC-05:00) Bogota' => 'America/Bogota',
            '(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern',
            '(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
            '(UTC-05:00) Lima' => 'America/Lima',
            '(UTC-05:00) Quito' => 'America/Bogota',
            '(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
            '(UTC-04:30) Caracas' => 'America/Caracas',
            '(UTC-04:00) La Paz' => 'America/La_Paz',
            '(UTC-04:00) Santiago' => 'America/Santiago',
            '(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
            '(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
            '(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
            '(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
            '(UTC-03:00) Greenland' => 'America/Godthab',
            '(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
            '(UTC-01:00) Azores' => 'Atlantic/Azores',
            '(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
            '(UTC+00:00) Casablanca' => 'Africa/Casablanca',
            '(UTC+00:00) Edinburgh' => 'Europe/London',
            '(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
            '(UTC+00:00) Lisbon' => 'Europe/Lisbon',
            '(UTC+00:00) London' => 'Europe/London',
            '(UTC+00:00) Monrovia' => 'Africa/Monrovia',
            '(UTC+00:00) UTC' => 'UTC',
            '(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
            '(UTC+01:00) Belgrade' => 'Europe/Belgrade',
            '(UTC+01:00) Berlin' => 'Europe/Berlin',
            '(UTC+01:00) Bern' => 'Europe/Berlin',
            '(UTC+01:00) Bratislava' => 'Europe/Bratislava',
            '(UTC+01:00) Brussels' => 'Europe/Brussels',
            '(UTC+01:00) Budapest' => 'Europe/Budapest',
            '(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
            '(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
            '(UTC+01:00) Madrid' => 'Europe/Madrid',
            '(UTC+01:00) Paris' => 'Europe/Paris',
            '(UTC+01:00) Prague' => 'Europe/Prague',
            '(UTC+01:00) Rome' => 'Europe/Rome',
            '(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
            '(UTC+01:00) Skopje' => 'Europe/Skopje',
            '(UTC+01:00) Stockholm' => 'Europe/Stockholm',
            '(UTC+01:00) Vienna' => 'Europe/Vienna',
            '(UTC+01:00) Warsaw' => 'Europe/Warsaw',
            '(UTC+01:00) West Central Africa' => 'Africa/Lagos',
            '(UTC+01:00) Zagreb' => 'Europe/Zagreb',
            '(UTC+02:00) Athens' => 'Europe/Athens',
            '(UTC+02:00) Bucharest' => 'Europe/Bucharest',
            '(UTC+02:00) Cairo' => 'Africa/Cairo',
            '(UTC+02:00) Harare' => 'Africa/Harare',
            '(UTC+02:00) Helsinki' => 'Europe/Helsinki',
            '(UTC+02:00) Istanbul' => 'Europe/Istanbul',
            '(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
            '(UTC+02:00) Kyiv' => 'Europe/Helsinki',
            '(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
            '(UTC+02:00) Riga' => 'Europe/Riga',
            '(UTC+02:00) Sofia' => 'Europe/Sofia',
            '(UTC+02:00) Tallinn' => 'Europe/Tallinn',
            '(UTC+02:00) Vilnius' => 'Europe/Vilnius',
            '(UTC+03:00) Baghdad' => 'Asia/Baghdad',
            '(UTC+03:00) Kuwait' => 'Asia/Kuwait',
            '(UTC+03:00) Minsk' => 'Europe/Minsk',
            '(UTC+03:00) Moscow' => 'Europe/Moscow',
            '(UTC+03:00) Nairobi' => 'Africa/Nairobi',
            '(UTC+03:00) Riyadh' => 'Asia/Riyadh',
            '(UTC+03:00) St. Petersburg' => 'Europe/Moscow',
            '(UTC+03:00) Volgograd' => 'Europe/Volgograd',
            '(UTC+03:30) Tehran' => 'Asia/Tehran',
            '(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
            '(UTC+04:00) Baku' => 'Asia/Baku',	    
            '(UTC+04:00) Muscat' => 'Asia/Muscat',	    
            '(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
            '(UTC+04:00) Yerevan' => 'Asia/Yerevan',
            '(UTC+04:30) Kabul' => 'Asia/Kabul',
            '(UTC+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
            '(UTC+05:00) Islamabad' => 'Asia/Karachi',
            '(UTC+05:00) Karachi' => 'Asia/Karachi',
            '(UTC+05:00) Tashkent' => 'Asia/Tashkent',
            '(UTC+05:30) Chennai' => 'Asia/Calcutta',
            '(UTC+05:30) Kolkata' => 'Asia/Kolkata',
            '(UTC+05:30) Mumbai' => 'Asia/Calcutta',
            '(UTC+05:30) New Delhi' => 'Asia/Calcutta',
            '(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
            '(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
            '(UTC+06:00) Almaty' => 'Asia/Almaty',
            '(UTC+06:00) Astana' => 'Asia/Dhaka',
            '(UTC+06:00) Dhaka' => 'Asia/Dhaka',	    
            '(UTC+06:00) Novosibirsk' => 'Asia/Novosibirsk',
            '(UTC+06:30) Rangoon' => 'Asia/Rangoon',
            '(UTC+07:00) Bangkok' => 'Asia/Bangkok',
            '(UTC+07:00) Hanoi' => 'Asia/Bangkok',
            '(UTC+07:00) Jakarta' => 'Asia/Jakarta',
            '(UTC+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
            '(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
            '(UTC+08:00) Chongqing' => 'Asia/Chongqing',
            '(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
            '(UTC+08:00) Irkutsk' => 'Asia/Irkutsk',	    
            '(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
            '(UTC+08:00) Manila' => 'Asia/Manila',
            '(UTC+08:00) Perth' => 'Australia/Perth',
            '(UTC+08:00) Singapore' => 'Asia/Singapore',
            '(UTC+08:00) Taipei' => 'Asia/Taipei',
            '(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
            '(UTC+08:00) Urumqi' => 'Asia/Urumqi',	    
            '(UTC+09:00) Osaka' => 'Asia/Tokyo',
            '(UTC+09:00) Sapporo' => 'Asia/Tokyo',
            '(UTC+09:00) Seoul' => 'Asia/Seoul',
            '(UTC+09:00) Tokyo' => 'Asia/Tokyo',
            '(UTC+09:00) Yakutsk' => 'Asia/Yakutsk',
            '(UTC+09:30) Adelaide' => 'Australia/Adelaide',
            '(UTC+09:30) Darwin' => 'Australia/Darwin',
            '(UTC+10:00) Brisbane' => 'Australia/Brisbane',
            '(UTC+10:00) Canberra' => 'Australia/Canberra',
            '(UTC+10:00) Guam' => 'Pacific/Guam',
            '(UTC+10:00) Hobart' => 'Australia/Hobart',
            '(UTC+10:00) Magadan' => 'Asia/Magadan',
            '(UTC+10:00) Melbourne' => 'Australia/Melbourne',
            '(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
            '(UTC+10:00) Sydney' => 'Australia/Sydney',
            '(UTC+10:00) Vladivostok' => 'Asia/Vladivostok',
            '(UTC+12:00) Auckland' => 'Pacific/Auckland',
            '(UTC+12:00) Fiji' => 'Pacific/Fiji',
            '(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
            '(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',	    
            '(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
            '(UTC+12:00) New Caledonia' => 'Asia/Magadan',
            '(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
            '(UTC+12:00) Wellington' => 'Pacific/Auckland',
            '(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
        );
    
    }

    private function getAbokiFxExchangeRates()
    {
        $token = config('abokifx.token');
        $url = config('abokifx.url');

        $resp = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get($url);

        $output = [];
        if($resp !== false){
            $content = json_decode($resp);
            //dd($content);

            foreach($content->response as $date =>  $fxOutputs){
                foreach($fxOutputs as $fxOutput){

                    $datetime1  = Carbon::parse($date);
                    $datetime2 = Carbon::now();
                    $days = $datetime1->diffInDays($datetime2);
                    if($fxOutput->currency_name === 'USD' && $days === 0){
                        $takeOutAsterix = str_replace("*","",$fxOutput->currency_rate ); //take out asterix
                        $cleanedUpResult = str_replace(" ","",$takeOutAsterix ); //take out spaces
                        $result = explode('/',$cleanedUpResult);
                        $output[$fxOutput->currency_name] = $result[1];
                    }
                    if($fxOutput->currency_name === 'GBP' && $days === 0){
                        $takeOutAsterix = str_replace("*","",$fxOutput->currency_rate ); //take out asterix
                        $cleanedUpResult = str_replace(" ","",$takeOutAsterix ); //take out spaces
                        $result = explode('/',$cleanedUpResult);
                        $output[$fxOutput->currency_name] = $result[1];
                    }
                    if($fxOutput->currency_name === 'EUR' && $days === 0){
                        $takeOutAsterix = str_replace("*","",$fxOutput->currency_rate ); //take out asterix
                        $cleanedUpResult = str_replace(" ","",$takeOutAsterix ); //take out spaces
                        $result = explode('/',$cleanedUpResult);
                        $output[$fxOutput->currency_name] = $result[1];
                    }
                }
            }

        }
        else
        {
            try {
                Mail::send('emails.admin.CurrencyUpdateFailureNotification', ['mail' =>  'mail'], function ($message)  {
                    $message->to('obiora@freeki.com');
                    $message->from('obiora@freeki.com');
                    //Add a subject
                    $message->subject("Freeki AbokiFx Currency Update Failure");
                });
            } catch (\Throwable $th) {

            }
        }
        return $output;
    }

    /**
     * Handle legacy numeric property URLs and redirect to the SEO-friendly slug-based URL.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function legacyPropertyRedirect(string $id)
    {
        $property = $this->propertyRepo->find($id);

        if (! $property) {
            abort(404);
        }

        // Build the new SEO-friendly URL with area and slug.
        // The area is derived from slug generation (city/state/postcode fallback).
        $area = $property->area_slug ?: 'london';
        $slug = $property->slug ?: $property->reference;

        $url = route('front.stays.show', ['area' => $area, 'slug' => $slug]);

        // Preserve query string (check_in_date, check_out_date, etc.)
        // so booking filters carry over to the new URL.
        if (request()->getQueryString()) {
            $url .= '?'.request()->getQueryString();
        }

        return redirect($url, 301);
    }

    /**
     * Display a property by its slug and area.
     *
     * This is the SEO-friendly property detail page. It resolves the property
     * by area_slug and slug, then renders the same details view as the legacy
     * /property/{id} route, but with proper canonical and metadata.
     *
     * @param  string  $area
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function showBySlug(Request $request, string $area, string $slug)
    {
        $property = $this->propertyRepo
            ->where('area_slug', $area)
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request;
        $property->parking_information = json_decode($property->parking_options);
        $currency_symbol = $this->propertyRepo->getPropertyCurrencySymbol($property);
        $request['check_in_date'] = Carbon::parse($request->check_in_date ?? now()->addDays(3));
        $request['check_out_date'] = Carbon::parse($request->check_out_date ?? now()->addDays(6));

        // The canonical URL is the stays.show route with the current area and slug.
        $canonical = route('front.stays.show', ['area' => $area, 'slug' => $slug]);

        return view('property-details', array_merge(
            compact('property', 'currency_symbol', 'data'),
            \App\Support\Seo::property($property, $canonical, $this->propertyCurrencyCode($property))
        ));
    }

}
