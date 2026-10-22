@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    #123456789012 - Order status
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Order Confirmation
                </div>
                <div>
                    <span>
                        Ref: #{{$reservation->reservation_no}}
                    </span>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12">
            <div class="mx-auto mt-10 max-w-sm">
                

                {{-- SUCCESS (PAY NOW) --}}
                <div class="mx-auto text-center">
                    <div class="h-32">
                        <img src={{asset('img/icons/status-success.png')}} alt="tdhh" class="object-contain m-auto" />
                    </div>
                    <div class="mt-4 font-semibold text-2xl sm:text-3xl">
                        Payment successful
                    </div>
                    <div class="mt-4 text-gray-500">
                        Your payment has been received, and your apartment is being reserved. A confirmation email will be sent once your reservation is completed.
                    </div>
                    <div class="mt-4">
                        <a href="{{route('front.account.reservation.index')}}" class="btn btn-orange btn-block">
                            Goto my reservations
                        </a>
                    </div>
                </div>
                 


                {{-- SUCCESS (PAY LATER) --}}
                {{-- 
                <div class="mx-auto text-center">
                    <div class="h-32">
                        <img src={{asset('img/icons/status-success.png')}} alt="tdhh" class="object-contain m-auto" />
                    </div>
                    <div class="mt-4 font-semibold text-2xl">
                        Reservation Request Sent
                    </div>
                    <div class="mt-4 text-gray-500">
                        Your reservation request has been received and it’s being processed. You will be contacted shortly to confirm your reservation. Thank you.
                    </div>
                    <div class="mt-4">
                        <a href="/account/bookings" class="btn btn-orange btn-block">
                            Goto my reservations
                        </a>
                    </div>
                </div>
                 --}}

    
    
                {{-- ERROR --}}
                {{-- 
                <div class="mx-auto text-center">
                    <div class="h-36">
                        <span class="fa fa-times-circle text-9xl text-red-500"></span>
                    </div>
                    <div class="mt-4 font-semibold text-2xl">
                        Payment Failed
                    </div>
                    <div class="mt-4 text-gray-500">
                        Sorry, your payment for this appointment was successful. You have not been charged.
                        You can click the button below to try again.
                    </div>
                    <div class="mt-4">
                        <a href="/account/bookings/123456" class="btn btn-orange btn-block">
                            View Details
                        </a>
                    </div>
                </div>
                 --}}

    
    
    
    
                {{-- ERROR --}}
                {{-- 
                <div class="mx-auto text-center">
                    <div class="h-40">
                        <span class="fa fa-times-circle text-9xl text-red-500"></span>
                    </div>
                    <div class="mt-0 font-semibold text-2xl sm:text-3xl">
                        Payment Failed
                    </div>
                    <div class="mt-4 text-gray-500">
                        Sorry, your payment for this appointment was successful. You have not been charged.
                        You can click the button below to try again.
                    </div>
                    <a href="/book/preview/12345" class="btn btn-lg btn-block btn-black mt-4">
                        Retry Payment
                    </a>
                </div>
                --}}
                
            </div>
            
            
            
            <div class="mx-auto mt-14 max-w-xl">
                <div class="mt-10 p-4 sm:p-10 bg-white border border-t-[10px] border-black space-y-3 rounded-md" style="min-height: 200px">

                    <div class="space-y-0.5">
                        <div class="px-3 py-3 bg-gray-100 flex justify-between">
                            <div>
                                 {{currency_convert($reservation->subtotal/$reservation->num_of_night, $reservation->property->country->code, currency()->getUserCurrency())}} * {{$reservation->num_of_night}} nights
                            </div>
                            <div class="text-right">
                                {{currency_convert($reservation->subtotal, $reservation->property->country->code, currency()->getUserCurrency())}}
                            </div>
                        </div>
                        <div class="px-3 py-3 bg-gray-100 flex justify-between">
                            <div>
                                Cleaning Fee
                            </div>
                            <div class="text-right">
                                  {{currency_convert($reservation->cleaning_fee, $reservation->property->country->code, currency()->getUserCurrency())}}
                            </div>
                        </div>
                        <div class="px-3 py-3 bg-gray-100 flex justify-between">
                            <div>
                                Parking Fee
                            </div>
                            <div class="text-right">
                                {{currency_convert($reservation->parking_fee, $reservation->property->country->code, currency()->getUserCurrency())}}
                            </div>
                        </div>
                        {{-- <div class="px-3 py-3 bg-gray-100 flex justify-between">
                            <div>
                                VAT
                            </div>
                            <div class="text-right">
                                {{$currency_symbol}}{{$reservation->tax}}
                            </div>
                        </div> --}}
                        <div>
                            <div class="pt-3 mt-5 border-t flex justify-between text-lg font-bold">
                                <div>
                                    Total
                                </div>
                                <div class="text-right">
                                  {{currency_convert($reservation->total, $reservation->property->country->code, currency()->getUserCurrency())}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <a href="{{route('front.account.reservation.invoice',  $reservation->id)}}" type="button" class="btn btn-transparent-black btn-block">
                        Download Receipt
                    </a>
                </div>
            </div>
 

        </div>

    </div>
@endsection