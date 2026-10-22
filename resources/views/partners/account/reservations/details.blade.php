@extends('layouts.partner')


@section('title')
    Reservation details
@endsection


@section('css')
    <style>
        .admin-sidebar-reservations{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">


        <div class="page-title-account">
            <div>
                Reservations details
                <a href="{{route('front.details', $reservation->property_id)}}" target="_blank" class="hover:underline font-montserrat text-sm text-tdhh-orange">
                    #{{$reservation->reservation_no}}
                </a>
            </div>
            <div class="pull-right flex justify-end space-x-2">
                @if($reservation->can_cancel_booking)
                    <a href="{{route('partner.reservation.cancel',  $reservation->id)}}" class="btn btn-transparent-black btn-sm relative top-2">
                        <span class="fa fa-download mr-2"></span>
                        Cancel Reservation
                    </a> 
                @endif
                <a href="{{route('front.account.reservation.invoice',  $reservation->id)}}" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-download mr-2"></span>
                    Download Invoice
                </a>
                <a href="{{route('partner.reservation.list')}}" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="box">


            <div class="mt-6">
                <div class="flex space-x-2">
                    <a href="{{route('partner.property.sync-calendar', $reservation->property_id)}}" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                        <img src="{{asset($reservation->property->cover_image->getUrl())}}" alt="" class="object-cover m-auto" />
                    </a>
                    <div class="md:my-auto">
                        <div class="md:h-16">
                            <a href="{{route('partner.property.sync-calendar', $reservation->property_id)}}" class="font-bold hover:underline ellipsis-2-lines">
                                {{$reservation->room_information->name}}
                            </a>
                            <div class="mt-1 text-fade text-xs">
                                <span class="fa fa-map-marker mr-1"></span>
                                {{$reservation->property_location->address}} {{$reservation->property_location->postal_code}},
                                        {{$reservation->property_location->city}}, {{$reservation->property_location->state}}, {{$reservation->property_location->country}}
                            </div>
                        </div>
                        <div class="mt-1 flex items-center space-x-4 text-sm">
                            <div>
                                <span class="fa fa-bed"></span>
                                {{$reservation->room_information->bedroom_qty}}
                            </div>
                            <div>
                                <span class="fa fa-shower"></span>
                                {{$reservation->room_information->bathroom_qty}}
                            </div>
                            <div class="flex flex-wrap">
                                <a href="{{route('partner.property.sync-calendar', $reservation->property_id)}}" class="btn btn-tdhh-orange btn-sm mr-3">Property Calendar</a>
                                <a href="{{route('front.details', $reservation->property_id)}}" target="_blank" class="btn btn-transparent-black btn-sm mr-3">View property</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t overflow-auto">


                <div class="grid grid-cols-2 gap-6">
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-calendar mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Check-in date
                            </div>
                            <div class="font-bold">
                                {{date_format(date_create($reservation->proposed_check_in_date),'l, d F, Y')}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-calendar mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Check-out date
                            </div>
                            <div class="font-bold">
                                {{date_format(date_create($reservation->proposed_check_out_date),'l, d F, Y')}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-clock mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Check-in time
                            </div>
                            <div class="font-bold">
                                {{date('G:i a', strtotime($reservation->proposed_check_in_time))}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-clock mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Check-out time
                            </div>
                            <div class="font-bold">
                                Up till {{date('G:i a', strtotime($reservation->proposed_check_out_time))}}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="px-6 py-4 mt-8 bg-gray-100">
                    Expected Guests
                </div>
                

                <div class="mt-8 grid grid-cols-2 gap-8">
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fa fa-users mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Number of Adults
                            </div>
                            <div class="font-bold">
                                {{$reservation->adult}} adults
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-children mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Number of Children
                            </div>
                            <div class="font-bold">
                                {{$reservation->children}} children
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-dog mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Number of Pets
                            </div>
                            <div class="font-bold">
                                {{$reservation->pet}} pets
                            </div>
                        </div>
                    </div>
                </div>



                <div class="px-6 py-4 mt-8 bg-gray-100">
                    Guest Information
                </div>
                

                <div class="mt-8 grid grid-cols-2 gap-8">
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fa fa-user mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Name
                            </div>
                            <div class="font-bold">
                                {{$reservation->guest_information->guest_name}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-at mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Email Address
                            </div>
                            <div class="font-bold">
                                {{$reservation->guest_information->guest_email}}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="w-8 flex-shrink-0">
                            <span class="fas fa-phone mt-1"></span>
                        </div>
                        <div>
                            <div>
                                Phone Number
                            </div>
                            <div class="font-bold">
                                {{$reservation->guest_information->guest_phone}}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="px-6 py-4 mt-8 bg-gray-100">
                    House rules for these guests
                </div>
                

                <div class="mt-8 grid grid-cols-2 gap-8">
                    @foreach ($reservation->property_rules_info as $propertyRule)
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-times-circle mt-1 text-red-500"></span>
                            </div>
                            <div>
                                <div>
                                    {{$propertyRule}}
                                </div>
                                <div class="font-bold">
                                    Not allowed
                                </div>
                            </div>
                        </div>
                    @endforeach
                
                </div>


                <div class="px-6 py-4 mt-8 bg-gray-100">
                    Price Breakdown
                </div>


                <div class="mt-8">
                    <div class="table-container">
                        <table class="table table-auto table-border table-align">
                            <thead>
                                <tr>
                                    <th>Number of nights</th>
                                    <th>Avg Price per night</th>
                                    <th>Parking Fee</th>
                                    <th>Cleaning Fee</th>
                                    <th>Total price</th>
                                    <th>Amount paid</th>
                                    <th>Status</th>
                                    <th>Date of payment</th>
                                </tr>
                            </thead>
                            <tbody>
        
                                <tr>
                                    <td>
                                        {{$reservation->num_of_night}} nights
                                    </td>
                                    <td>
                                        {{currency_format(number_format($reservation->price_information->total/$reservation->num_of_night, 2), $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                    <td>
                                        {{currency_format($reservation->parking_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                    <td>
                                        @if(isset($reservation->cleaning_information) && $reservation->cleaning_information->cleaning_type == 'per_night')
                                        {{currency_format($reservation->cleaning_information->cleaning_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                            <div class="text-fade">
                                                (per night)
                                            </div>
                                        @else
                                            {{currency_format($reservation->cleaning_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                        @endif
                                    </td>
                                    <td>
                                        {{currency_format($reservation->total, $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                    <td>
                                        {{currency_format($reservation->total, $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                    <td>
                                        @if ($reservation->payment_status)
                                            <div class="label" style="color: white; background: {{$reservation->payment_status->color}}">
                                                {{$reservation->payment_status->name}}
                                            </div>
                                        @endif
                                        {{-- <div class="label label-green"> --}}
                                            {{-- {{$reservation->payment_status ? $reservation->payment_status->name : ''}} --}}
                                        {{-- </div> --}}
                                    </td>
                                    <td>
                                        {{date_format(date_create($reservation->created_at),'l, d F, Y')}}
                                    </td>
                                </tr>
        
                            </tbody>
                        </table>

                        <hr/><br/>

                        <table class="table table-auto table-border table-align">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Property</th>
                                    <th style="min-width: 150px;">Date</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservation->price_information->breakdown as $key => $priceInfo)
                                    <tr>
                                        <td>
                                            {{ $loop->index + 1}} 
                                        </td>
                                        <td>
                                            {{$reservation->room_information->name}}
                                        </td>
                                        <td>
                                            {{$key}}
                                        </td>
                                        <td>
                                            {{currency_format($priceInfo, $reservation->price_information->country_code ?? config('currency.default'))}}
                                        </td>
                                    </tr>
                                @endforeach
                               
                                <tr>
                                    <td colspan="3" style="text-align: right;">
                                        Sub Total ({{$reservation->num_of_night}} nights)
                                    </td>
                                    <td style="text-align: right;">
                                        {{currency_format($reservation->subtotal, $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;">
                                        Cleaning/Parking Fee
                                    </td>
                                    <td style="text-align: right;">
                                        {{currency_format($reservation->cleaning_fee + $reservation->parking_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="width: 100px; text-align: right; background: #ffa200;">
                                        Total
                                    </td>
                                    <td style="text-align: right; font-weight: bold; background: #ffa200;">
                                        {{currency_format($reservation->total, $reservation->price_information->country_code ?? config('currency.default'))}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>


            <div class="mt-8 flex justify-start space-x-4 flex-wrap">
                <a href="{{route('front.account.reservation.invoice',  $reservation->id)}}" target="_blank" class="btn btn-transparent-black">
                    Download Invoice
                </a>
                <a href="/contact" target="_blank" class="btn btn-transparent-black">
                    Report problem
                </a>
            </div>


        </div>

    </div>
@endsection

