@extends('layouts.admin')


@section('title')
    Property
@endsection


@section('css')
    <style>
        .admin-sidebar-finance{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">


        <div class="page-title-account">
            <div>
                Finance
            </div>
            <div class="pull-right">
            </div>
        </div>

        <div>
            <div class="p-6 pb-2 mb-6 bg-white rounded-lg shadow">
                <form action="{{route('admin.commission.search')}}" id="searchForm" method="get">
                    @csrf
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 pb-4">

                        <div>
                            <label>
                                Date From
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="date" name="date_from" class="form-input">
                        </div>

                        <div>
                            <label>
                                Date To
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="date" name="date_to" class="form-input">
                        </div>

                        <div>
                            <label>
                                Search
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="search" name="search_text" placeholder="Search invoice" class="form-input" value="{{request('search')}}">
                        </div>

                        <div>
                            <label>
                                Status
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="commission_status" class="form-input">
                                <option value="">All</option>
                                @foreach($partnerCommissionStatuses as $partnerCommissionStatus)
                                    <option value="{{$partnerCommissionStatus->id}}">{{$partnerCommissionStatus->name}}</option>
                                @endforeach

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

            <div class="mt-0 pt-4 border-t grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-2">
                <div class="p-4 box" style="box-shadow: none;">
                    <div>
                        Total received
                    </div>
                    <div class="text-2xl font-bold">
                        {{ currency_format($data ? $data['totalRevenue'] : 0, config('currency.default')) }}
                    </div>
                    <div class="text-gray-400 text-sm">
                        {{$data ? date_format(date_create($data['date_from']),'M, j Y') : '' }} - {{$data ? date_format(date_create($data['date_to']),'M, j Y') : '' }}
                    </div>
                </div>
                <div class="p-4 box" style="box-shadow: none;">
                    <div>
                        Total payouts
                    </div>
                    <div class="text-2xl font-bold">
                        {{ currency_format($data ? $data['totalPayout'] : 0, config('currency.default')) }}
                    </div>
                    <div class="text-gray-400 text-sm">
                        {{$data ? date_format(date_create($data['date_from']),'M, j Y') : '' }} - {{$data ? date_format(date_create($data['date_to']),'M, j Y') : '' }}
                    </div>
                </div>
                <div class="p-4 box" style="box-shadow: none;">
                    <div>
                        Total commission
                    </div>
                    <div class="text-2xl font-bold">
                        {{ currency_format($data ? $data['totalProcessing'] : 0, config('currency.default')) }}
                    </div>
                    <div class="text-gray-400 text-sm">
                        {{$data ? date_format(date_create($data['date_from']),'M, j Y') : '' }} - {{$data ? date_format(date_create($data['date_to']),'M, j Y') : '' }}
                    </div>
                </div>
            </div>

            <div class="mt-4 table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Booking No</th>
                            <th>Property</th>
                            <th>Amount</th>
                            <th>TDHH Commission</th>
                            <th>Payout</th>
                            <th>Due date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($partnerCommissions  as $partnerCommission)

                            <tr>
                                <td>
                                    {{ $loop->index + 1}}
                                </td>
                                <td>
                                    <a href="{{route('admin.reservation.show', $partnerCommission->reservation_id)}}" class="text-fade hover:underline">
                                        {{$partnerCommission->reservation->reservation_no?? ''}}
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <a href="{{route('front.details', $partnerCommission->reservation->property_id?? '')}}" target="_blank" class="font-bold hover:underline ellipsis-2-lines">
                                            {{$partnerCommission->reservation->property_name??''}}
                                        </a>
                                        <div class="mt-1 text-fade text-xs">
                                            <span class="fa fa-map-marker mr-1"></span>
                                            {{$partnerCommission->reservation->property_location->city ?? ''}}, {{$partnerCommission->reservation->property_location->country?? ''}}
                                        </div>
                                        @if($partnerCommission->commission_type == $partnerCommission::CANCELLED_BOOKING)
                                            <div class="label" style="color: white; background: blue">
                                                Cancelled Commission 
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    {{currency_format($partnerCommission->payout_amt + $partnerCommission->commission_amt ?? 0,config('currency.default'))}}
                                </td>
                                <td>
                                    {{currency_format($partnerCommission->commission_amt,config('currency.default'))}}
                                </td>
                                <td>
                                    {{currency_format( $partnerCommission->payout_amt,config('currency.default'))}}
                                </td>
                                <td>
                                    {{date_format(date_create($partnerCommission->reservation->created_at??''),'M, j Y')}}
                                </td>
                                <td>
                                    <div class="label" style="color: white; background: {{$partnerCommission->partner_commission_status->color}}">
                                       {{$partnerCommission->partner_commission_status->name}}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if(isset($partnerCommission->commission_status_id) && $partnerCommission->commission_status_id == $partnerCommission->partner_commission_status::IN_PROGRESS)
                                            <button id="dropdownButton{{$partnerCommission->id}}" data-dropdown-toggle="dropdown-{{$partnerCommission->id}}" class="dropdown" type="button">
                                                Option
                                                <span class="fa fa-angle-down ml-2"></span>
                                            </button>
                                            <div id="dropdown-{{$partnerCommission->id}}" class="dropdown-menu-nav hidden" >
                                                <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                    {{-- <li>
                                                        <a href="/property/{{$partnerCommission->id}}" target="_blank" class="dropdown-menu-option">View property</a>
                                                    </li> --}}
                                                    <form method="POST" action="{{route('admin.commission.mark-as-paid', $partnerCommission->id)}}">
                                                        @csrf
                                                        <input type="hidden" name="commission_id" value="{{$partnerCommission->id}}" />
                                                        <li>
                                                            <button type="submit" class="dropdown-menu-option w-full text-left"
                                                                onclick="return confirm('Are you sure you want to mark this commission as paid? \nOnce done, you cannot undo it. Are you sure you want to continue?');">
                                                                Mark As Paid
                                                            </button>
                                                        </li>
                                                    </form>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No data yet
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="9">
                                {{$partnerCommissions->appends(request()->capture()->except('page'))->links()}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection

