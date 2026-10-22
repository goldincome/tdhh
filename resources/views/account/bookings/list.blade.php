@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    My reservations - Olawale Lawal
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
                    My reservations
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a>Reservations</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "bookings"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                <div class="flex justify-between">
                    <div>
                        <a href="/account" class="lg:hidden btn btn-transparent-black btn-md">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </a>
                    </div>
                </div>
                
                <div class="mt-8 lg:mt-0 table-responsive">
                    @include('layouts.errors-and-messages')
                    <table class="table table-auto table-border table-align">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Booked date</th>
                                <th>Payment</th>
                                <th>Booked on</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                                
                            @forelse($reservations as $key => $reservation)
                                <tr>
                                    <td>
                                        <div class="flex space-x-2">
                                            <a href="{{route('front.details', $reservation->property_id)}}" class="w-36 h-24 mr-2 hidden md:flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                <img src="{{asset($reservation->property->cover_image->getUrl())}}"  alt="tdhh" class="object-cover m-auto" />
                                            </a>
                                            <div class="md:my-auto">
                                                <div>
                                                    <a href="{{route('front.account.reservation.detail', $reservation->id)}}" class="text-fade hover:underline">
                                                        #{{$reservation->reservation_no}}
                                                    </a>
                                                    <a href="{{route('front.account.reservation.detail', $reservation->id)}}" class="font-bold hover:underline ellipsis-2-lines">
                                                        {{$reservation->room_information->name ?? $reservation->property->name}} 
                                                    </a>
                                                    <div class="mt-1 text-fade text-xs">
                                                        <span class="fa fa-map-marker mr-1"></span>
                                                        {{$reservation->property_location->city ?? ''}}, {{$reservation->property_location->country?? ''}} 
                                                    </div>
                                                </div>
                                                <div class="mt-1 flex space-x-4 text-sm">
                                                    <div>
                                                        <span class="fa fa-bed"></span>
                                                        {{$reservation->room_information->bedroom_qty?? ''}}
                                                    </div>
                                                    <div>
                                                        <span class="fa fa-shower"></span>
                                                        {{$reservation->room_information->bathroom_qty?? ''}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                
                                    <td>
                                        {{date_format(date_create($reservation->proposed_check_in_date),'M, j Y')}} 
                                        <br>
                                        <span class="text-fade">to</span>
                                        <br>
                                        {{date_format(date_create($reservation->proposed_check_out_date),'M, j Y')}}
                                    </td>
                                    <td>
                                        {{currency_format($reservation->total,config('currency.default'))}}
                                        <br>
                                        @if ($reservation->payment_status)
                                            <div class="label" style="color: white; background: {{$reservation->payment_status->color}}">
                                                {{$reservation->payment_status->name}}
                                            </div>
                                        @endif

                                        {{-- <div class="label label-{{$reservation->payment_status ? $reservation->payment_status->color : 'gray'}}"> --}}
                                            {{-- {{$reservation->payment_status ? $reservation->payment_status->name : ''}} --}}
                                            {{-- {{$reservation->payment_statuses()->orderBy('id', 'DESC')->first()->name ?? ''}} --}}
                                        {{-- </div> --}}
                                    </td>
                                    <td>
                                        {{$reservation->created_at->diffForHumans()}} 
                                    </td>
                                    <td>
                                        <a href="{{route('front.account.reservation.detail', $reservation->id)}}" class="btn btn-transparent-black btn-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="table-info">
                                            <span class="fa fa-list"></span>
                                            <div class="italic mt-3 text-lg">
                                                No reservations yet
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="9">
                                    {{$reservations->links()}}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection