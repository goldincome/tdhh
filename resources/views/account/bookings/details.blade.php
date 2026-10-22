@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    #{{$reservation->reservation_no}} - My reservations - {{$reservation->guest_information->guest_name??''}}
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection


@section('content')
    <div>

        <div id="model-cancel-booking" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">

            <div class="modal-full max-w-2xl">
                <form action="{{route('front.account.cancel.reservation', $reservation->id)}}" method="POST">
                    @csrf
                    <input type="hidden" name="reservation_id" value="{{$reservation->id}}" />
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-header-text">
                                Cancel This Booking
                            </div>
                            <button type="button" data-modal-toggle="model-cancel-booking" class="modal-header-close">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="px-4 sm:px-8 py-4 bg-red-50">
                                <div class="font-bold">Cancellation & Refund Policy for this property:</div>
                                <div class="mt-4">
                                    @foreach($reservation->partner_refund_policy as $refundPolicy)
                                        @if(isset($refundPolicy->allow_refund) && $refundPolicy->allow_refund == 'yes')
                                            Cancellation {{$refundPolicy->days_before}} day(s) before your check-in date attracts a {{$refundPolicy->percent_refund}}% deduction of the total booking fee.
                                            <br />
                                            The remaining balance will be refunded to you.
                                            <br />
                                            <span class="text-red-500">PS: This policy may change per time.</span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-10 border p-4">
                                Please reveiw the cancellation policy below:
                                <ul class="mt-4 ml-4 list-disc">
                                    <li>
                                        The cancellation & refund policy stated above, will be applied.
                                    </li>
                                    <li>
                                        Your cancellation cannot be undone.
                                    </li>
                                    <li>
                                        Your cancellation & refund will be processed immediately, however, it may take up to 30 days to receive your refund.
                                    </li>
                                    <li>
                                        Once cancelled, you will not be allowed to check-in to the property.
                                    </li>
                                    <li>
                                        You can book this property again, at any time.
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-10">
                                <label class="cursor-pointer">
                                    <div class="flex space-x-3">
                                        <div class="mt-0.5 flex">
                                            <input type="checkbox" class="form-input-checkbox w-5 h-5" required />
                                        </div>
                                        <div class="my-auto">
                                            I have read the cancellation & refund policy for this property, and I want to proceed with my cancellation.
                                        </div>
                                    </div>
                                </label>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" data-modal-toggle="model-cancel-booking" class="btn btn-transparent-black">Close</button>
                            <button type="submit"  class="btn text-white bg-red-500">Cancel my booking</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="top-wide">
            <div>
                <div>
                    My Booking Details For {{$reservation->room_information->name?? ''}}
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a href="/account/bookings">Reservations</a>
                    <span>/</span>
                    <a>#{{$reservation->reservation_no}}</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "bookings"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                <div class="sm:flex sm:justify-between">
                    <a href="/account/bookings" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                    <div class="mt-4 sm:mt-0 flex flex-wrap sm:justify-end">
                        @if($reservation->review)
                            <a href="{{route('customer.review.detail', $reservation->review->id)}}" class="btn btn-transparent-black btn-md mr-2 sm:mr-0 ml-0 sm:ml-2 mb-2">
                                <span class="fa fa-star mr-2"></span>
                                View Review
                            </a>
                        @else(!$reservation->review)
                            <a href="/account/bookings/{{$reservation->id}}/review" class="btn btn-transparent-black btn-md mr-2 sm:mr-0 ml-0 sm:ml-2 mb-2">
                                <span class="fa fa-star mr-2"></span>
                                Add review
                            </a>
                        @endif
                        {{-- @if($reservation->can_request_refund)
                            <a href="{{route('front.account.reservation.request.refund',$reservation->id)}}" class="btn btn-transparent-black btn-md mr-2 sm:mr-0 ml-0 sm:ml-2 mb-2">
                                <span class="fa fa-file mr-2"></span>
                                Request Refund
                            </a>
                        @endif --}}
                        <a href="{{route('front.account.reservation.show',$reservation->id)}}" class="btn btn-transparent-black btn-md mr-2 sm:mr-0 ml-0 sm:ml-2 mb-2">
                            <span class="fa fa-file mr-2"></span>
                            View Invoice
                        </a>
                        <a href="/contact" target="_blank" class="btn btn-transparent-black btn-md mr-2 sm:mr-0 ml-0 sm:ml-2 mb-2">
                            <span class="fa fa-desktop mr-2"></span>
                            Report problem
                        </a>
                        @if($reservation->can_cancel_booking)
                            <button type="button" data-modal-toggle="model-cancel-booking" class="btn btn-transparent-black btn-md mr-2 sm:mr-0 ml-0 sm:ml-2 mb-2">
                                <span class="fa fa-times-circle mr-2"></span>
                                Cancel Booking
                            </button>
                        @endif
                    </div>

                </div>

                <div class="mt-4 border-t">
                    @include('layouts.errors-and-messages')

                    <div class="mb-6 note text-center">
                        Booking reference number: <span class="font-bold">#{{$reservation->reservation_no}}</span>
                    </div>

                    <div>
                        <div class="flex space-x-2">
                            <a href="{{route('front.details', $reservation->property->id)}}" target="_blank" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                <img src="{{asset($reservation->property->cover_image->getUrl())}}" alt="tdhh" class="object-cover m-auto" />
                            </a>
                            <div class="md:my-auto">
                                <div class="md:h-16">
                                    <a href="{{route('front.details', $reservation->property->id)}}" target="_blank" class="font-bold hover:underline ellipsis-2-lines">
                                        {{$reservation->room_information->name?? ''}}
                                    </a>
                                    <div class="mt-1 text-fade text-xs">
                                        <span class="fa fa-map-marker mr-1"></span>
                                        {{$reservation->property_location->address??''}} {{$reservation->property_location->postal_code??''}},
                                        {{$reservation->property_location->city??''}}, {{$reservation->property_location->state??''}}, {{$reservation->property_location->country??''}}
                                        <div>
                                            {{$reservation->property_location->neighborhood??''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1 flex items-center space-x-4 text-sm">
                                    <div>
                                        <span class="fa fa-bed"></span>
                                        {{$reservation->room_information->bedroom_qty??''}}
                                    </div>
                                    <div>
                                        <span class="fa fa-shower"></span>
                                        {{$reservation->room_information->bathroom_qty??''}}
                                    </div>
                                    <div class="flex flex-wrap">
                                        <a href="{{route('front.details', $reservation->property->id)}}" target="_blank" class="btn btn-transparent-black btn-sm mr-3">View property</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 mt-8 border-t overflow-auto">


                        <div class="px-6 grid grid-cols-2 gap-6">
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
                                        From {{date('G:i a', strtotime($reservation->proposed_check_in_time))}}
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
                            Guest Information
                        </div>


                        <div class="mt-8 px-6 grid grid-cols-2 gap-8">
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

                        <div class="mt-8 px-6 grid grid-cols-2 gap-8">
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
                            <div class="flex">
                                <div class="w-8 flex-shrink-0">
                                    <span class="fas fa-users mt-1"></span>
                                </div>
                                <div class="text-red-500">
                                    <div>
                                        Maximum Allowed Number of Guests
                                    </div>
                                    <div class="font-bold">
                                        {{$reservation->max_num_of_guest}} guests
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="px-6 py-4 mt-8 bg-gray-100">
                            Characteristics
                        </div>


                        <div class="mt-8 px-6 grid grid-cols-2 gap-8">
                            @foreach ($reservation->property_characteristics_info as $characteristic)
                                <div class="flex property-details-options">
                                    <div>
                                        <span class="fa fa-check-circle text-[#00CC00]"></span>
                                    </div>
                                    <div>
                                        {{$characteristic}}
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <div class="px-6 py-4 mt-8 bg-gray-100">
                            House rules for these guests
                        </div>


                        <div class="mt-8 px-6 grid grid-cols-2 gap-8">
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
                            Parking Details
                        </div>


                        <div class="mt-8 px-6 grid grid-cols-2 gap-8">
                            <div class="flex">
                                <div class="w-8 flex-shrink-0">
                                    <span class="fa fa-car mt-1"></span>
                                </div>
                                <div>
                                    <div>
                                        Parking Availability
                                    </div>
                                    <div class="font-bold">
                                        @if($reservation->parking_information->parking_is_available == 'yes')
                                            <span class="text-green-500">Yes, available</span>
                                        @else
                                            <span class="text-red-500">No, not available</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($reservation->parking_information->parking_is_available == 'yes')
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0">
                                        <span class="fa fa-car mt-1"></span>
                                    </div>
                                    <div>
                                        <div>
                                            Parking Location
                                        </div>
                                        <div class="font-bold">
                                            {{$reservation->parking_information->parking_location}}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0">
                                        <span class="fa fa-car mt-1"></span>
                                    </div>
                                    <div>
                                        <div>
                                            Parking Type
                                        </div>
                                        <div class="font-bold">
                                            <span class="capitalize">{{$reservation->parking_information->parking_type}}</span>
                                            @if(isset($reservation->parking_information->guest_car_parking_choice ) && $reservation->parking_information->guest_car_parking_choice == 'yes')
                                                <br />You have chosen to use parking space
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                @if(isset($reservation->parking_information->parking_charge) && $reservation->parking_information->parking_charge == 'yes')
                                    <div class="flex">
                                        <div class="w-8 flex-shrink-0">
                                            <span class="fa fa-car mt-1"></span>
                                        </div>
                                        <div>
                                            <div>
                                                Parking Fee
                                            </div>
                                            <div class="font-bold">
                                                Parking Charges Apply
                                                {{-- {{currency_format($reservation->parking_information->parking_fee, $reservation->price_information->country_code ?? config('currency.default'))}} --}}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>


                        <div class="px-6 py-4 mt-8 bg-gray-100">
                            Price Breakdown
                        </div>


                        <div class="mt-8">
                            <div class="table-container">
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
                                            <td colspan="4" class="border-t-2 border-black" style="border-top-color: #000 !important;">
                                                <div style="color: #f00; font-size: 14px; margin-top: 10px;">
                                                    <div>
                                                        * Cleaning are a summation of Cleaning fee
                                                    </div>
                                                    <div style="margin-top: -5px;">
                                                        * This charge varies depending on your booking preferences
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
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
                                                Cleaning
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



                    <div class="px-4 sm:px-8 py-4 mt-8 bg-red-50">
                        <div class="font-bold">Cancellation & Refund Policy for this property:</div>
                        <div class="mt-4">
                            @foreach($reservation->partner_refund_policy as $refundPolicy)
                                @if(isset($refundPolicy->allow_refund) && $refundPolicy->allow_refund == 'yes')
                                    Cancellation {{$refundPolicy->days_before}} day(s) before your check-in date attracts a {{$refundPolicy->percent_refund}}% deduction of the total booking fee.
                                    <br />
                                    The remaining balance will be refunded to you.
                                    <br />
                                    <span class="text-red-500">PS: This policy may change per time.</span>
                                @endif
                            @endforeach
                        </div>
                    </div>


                    <div class="mt-8 flex justify-start flex-wrap">

                        <a href="/account/bookings/{{$reservation->id}}/review" class="btn btn-transparent-black mr-4 mb-4">
                            <span class="fa fa-star mr-2"></span>
                            Add review
                        </a>
                        @if($reservation->can_request_refund)
                            <a href="{{route('front.account.reservation.request.refund',$reservation->id)}}" class="btn btn-transparent-black mr-4 mb-4">
                                <span class="fa fa-file mr-2"></span>
                                Request Refund
                            </a>
                        @endif
                        <a href="{{route('front.account.reservation.show',$reservation->id)}}" class="btn btn-transparent-black mr-4 mb-4">
                            <span class="fa fa-file mr-2"></span>
                            View Invoice
                        </a>
                        <a href="/contact" target="_blank" class="btn btn-transparent-black mr-4 mb-4">
                            <span class="fa fa-desktop mr-2"></span>
                            Report problem
                        </a>
                        @if($reservation->can_cancel_booking)
                            <button type="button" data-modal-toggle="model-cancel-booking" class="btn btn-transparent-black mr-4 mb-4">
                                <span class="fa fa-times-circle mr-2"></span>
                                Cancel Booking
                            </button>
                        @endif
                    </div>





                </div>
            </div>
        </div>
    </div>
@endsection
