@extends('layouts.admin')


@section('title')
    Reservation
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
                Reservations
            </div>
            <div class="pull-right">
            </div>
        </div>


        <div>

            <div class="p-6 pb-2 mb-6 bg-white rounded-lg shadow">
                <form action="{{route('partner.reservation.search')}}" id="searchForm" method="get">
                    @csrf
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 pb-4">

                        <div>
                            <label>
                                Date Type
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="search_type" class="form-input" required>
                                <option value="check-in">Check-in</option>
                                <option value="check-out">Check-out</option>
                                <option value="reservation-date">Reservation date</option>
                            </select>
                        </div>

                        <div>
                            <label>
                                Date
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="date" name="search_date" value="{{$data->search_date??''}}" required class="form-input">
                        </div>

                        <div>
                            <label>
                                Search
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="search" name="search_text" required placeholder="Search" class="form-input" value="{{$data->search_text?? ''}}">
                        </div>

                        <div>
                            <label>
                                Status
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="payment_status" class="form-input" required>
                                <option value="">All</option>
                                {{-- <option value="">Pending</option> --}}
                                <option value="{{$paymentStatusModel::PAID}}">Paid</option>
                                <option value="{{$paymentStatusModel::CANCELLED_BY_CUSTOMER}}|{{$paymentStatusModel::CANCELLED_BY_PARTNER}}|{{$paymentStatusModel::CANCELLED_BY_ADMIN}}">Cancelled</option>
                                <option value="{{$paymentStatusModel::REFUND_REQUESTED}}">Requested Refund</option>
                                <option value="{{$paymentStatusModel::REFUNDED}}">Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label class="hidden md:block lg:hidden xl:block"> &nbsp;</label>
                            <div class="flex space-x-2">
                                <button class="btn h-10 btn-tdhh-orange" type="submit">Submit</button>
                                <button type="submit" name="download_report" id="downloadReport" value={{true}} class="btn h-10 btn-transparent-black">
                                    {{-- Download --}}
                                    <span class="fa fa-download"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Property Info</th>
                            <th>Guests / <br /> Check-in & out</th>
                            <th>Status</th>
                            <th>Total payment / <br /> Commision</th>
                            <th>Booked on</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @forelse ($properties as $property) --}}

                            @forelse($reservations as $key => $reservation)
                                <tr>
                                    <td>
                                        {{ $loop->index + 1}}
                                    </td>
                                    <td>
                                        <div class="flex space-x-2">
                                            <a href="{{route('front.account.reservation.detail', $reservation->id)}}" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                <img src="{{asset($reservation->property->cover_image->getUrl())}}" alt="tdhh" class="object-cover m-auto" />
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
                                        <div>
                                            <span class="whitespace-nowrap">{{$reservation->adult}} adults,</span>
                                            <span class="whitespace-nowrap">{{$reservation->children}} children,</span>
                                            <span class="whitespace-nowrap">{{$reservation->pet}} pets</span>
                                        </div>
                                        <div class="pt-2 mt-2 border-t">
                                            {{date_format(date_create($reservation->proposed_check_in_date),'M, j Y')}}
                                            <div class="text-fade">to</div>
                                            {{date_format(date_create($reservation->proposed_check_out_date),'M, j Y')}}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($reservation->payment_status)
                                            <div class="label" style="color: white; background: {{$reservation->payment_status->color}}">
                                                {{$reservation->payment_status->name}}
                                            </div>
                                        @endif

                                        {{-- <div class="label label-{{$reservation->payment_status ? $reservation->payment_status->color : 'gray'}}"> --}}
                                            {{-- {{$reservation->payment_status ? $reservation->payment_status->name : ''}} --}}
                                        {{-- </div> --}}
                                    </td>
                                    <td>
                                        {{currency_format($reservation->total,config('currency.default'))}}/£20

                                    </td>
                                    <td>
                                        {{date_format(date_create($reservation->created_at),'M, j Y')}}
                                    </td>
                                    <td>
                                        <a href="{{route('admin.reservation.show', $reservation->id)}}" class="btn btn-transparent-black btn-sm">View</a>
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
@endsection

