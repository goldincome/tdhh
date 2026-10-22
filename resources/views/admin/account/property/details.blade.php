@extends('layouts.admin')


@section('title')
    Property details
@endsection


@section('css')
    <style>
        .admin-sidebar-property{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">


       <form method="POST" action="{{route('admin.disable-enable-property', $property->id)}}">
            @csrf
            <div id="modal-enable" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                <div class="modal-full max-w-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-header-text">
                                Enable property
                            </div>
                            <button type="button" data-modal-toggle="modal-enable" class="modal-header-close">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <input type="hidden" id="bedroom-index" class="text-black" />
                        </div>

                        <div class="modal-body">

                            <span class="font-bold">Are you sure you want to enable this customer?</span>
                            <ul class="list-disc mt-4 ml-4">
                                <li>
                                    Once completed, customers will be able to book this property.
                                </li>
                                <li>
                                    You can deactivate the property anytime.
                                </li>
                            </ul>

                        </div>

                        <div class="modal-footer">
                            <button type="button" data-modal-toggle="modal-enable" class="btn btn-transparent-black">Cancel</button>
                            <button type="submit" class="btn text-white bg-green-500">
                                Enable property
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <form method="POST" action="{{route('admin.disable-enable-property', $property->id)}}">
            @csrf
            <div id="modal-disable" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                <div class="modal-full max-w-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-header-text">
                                Disable property
                            </div>
                            <button type="button" data-modal-toggle="modal-disable" class="modal-header-close">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <input type="hidden" id="bedroom-index" class="text-black" />
                        </div>

                        <div class="modal-body">

                            <span class="font-bold">Are you sure you want to disable this property?</span>
                            <ul class="list-disc mt-4 ml-4">
                                <li>
                                    Once completed, customers will not be able to book this property.
                                </li>
                                <li>
                                    You can re-activate the property anytime.
                                </li>
                            </ul>

                            <div class="mt-10 form-group">
                                <label>
                                    Reason
                                    <span class="form-input-required">*</span>
                                </label>
                                <textarea name="reason" class="form-input resize-none" required style="height: 120px;"></textarea>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" data-modal-toggle="modal-disable" class="btn btn-transparent-black">Cancel</button>
                            <button type="submit" class="btn text-white bg-red-500">
                                Disable property
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <div class="page-title-account">
            <div>
                Property details
                <a href="{{route('front.details', $property->id)}}" target="_blank" class="hover:underline font-montserrat text-sm text-tdhh-orange">
                    #{{$property->reference}}
                </a>
            </div>
            <div class="pull-right flex justify-end space-x-2">
                <a href="/admin/reservations?property={{$property->id}}" target="_blank" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-calendar-check mr-2"></span>
                    View reservations
                </a>



                @if($property->status_id == App\Models\Property\PropertyStatus::SUSPENDED_BY_ADMIN)
                    <button type="button" data-modal-toggle="modal-enable" class="btn btn-transparent-black btn-sm relative top-2">
                        <span class="fa fa-check mr-2"></span>
                        Enable
                    </button>
                @else
                    <button type="button" data-modal-toggle="modal-disable" class="btn btn-transparent-black btn-sm relative top-2">
                        <span class="fa fa-times mr-2"></span>
                        Disable
                    </button>
                @endif

                <a href="{{url()->previous()}}" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="box">


            <div class="mt-6">
                <div class="flex space-x-2">
                    <a href="{{route('front.details', $property->id)}}" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                        <img src="{{asset('img/bgs/manchester.jpg')}}" alt="" class="object-cover m-auto" />
                    </a>
                    <div class="md:my-auto">
                        <div class="md:h-16">
                            <a href="{{route('front.details', $property->id)}}" class="font-bold hover:underline ellipsis-2-lines">
                               {{$property->name}}
                            </a>
                            <div class="mt-1 text-fade text-xs">
                                <span class="fa fa-map-marker mr-1"></span>
                                {{$property->address}}
                            </div>
                        </div>
                        <div class="mt-1 flex items-center space-x-4 text-sm">
                            <div>
                                <span class="fa fa-bed"></span>
                               {{$property->bedroom_qty}}
                            </div>
                            <div>
                                <span class="fa fa-shower"></span>
                             {{$property->bathroom_qty}}
                            </div>
                            <div class="flex flex-wrap">
                                {{-- <a href="/admin/property/sync-calendar/1234567890" class="btn btn-tdhh-orange btn-sm mr-3">Property Calendar</a> --}}
                                <a href="{{route('front.details', $property->id)}}" target="_blank" class="btn btn-transparent-black btn-sm mr-3">
                                    <span class="fa fa-external-link mr-2"></span>
                                    View property
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t overflow-auto md:flex md:space-x-8">
                <div class="md:w-2/3">


                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-hashtag mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Property ID
                                </div>
                                <div class="font-bold">
                                    #{{$property->reference}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Booking fee
                                </div>
                                <div class="font-bold">
                                     {{currency_convert($property->todayPrice,$property->country->code,currency()->getUserCurrency())}} / night
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
                                    From {{$property->check_in_from}}
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
                                    Up till  From {{$property->check_in_to}}
                                </div>
                            </div>
                        </div>
                        <div class="flex col-span-2">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-list mt-1"></span>
                            </div>
                            <div>
                                <div class="font-bold">
                                    Description
                                </div>
                                <div class="text-justify">
                                   {{$property->description}}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        Property Owner
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
                                    <a href="/partners/12345" class="hover:underline text-tdhh-orange">{{$property->partner->fullName}}</a>
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
                                    <a href="mailto:{{$property->partner->email}}" class="hover:underline text-tdhh-orange">{{$property->partner->email}}</a>

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
                                    <a href="tel:{{$property->partner->phone}}" class="hover:underline text-tdhh-orange">{{$property->partner->phone}}</a>

                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-calendar mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Member since
                                </div>
                                <div class="font-bold">
                                   {{$property->partner->created_at->format('M d, Y')}}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        House properties
                    </div>


                    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-6">
                        <div class="flex property-details-options">
                            <div>
                                <span class="fa fa-bed"></span>
                            </div>
                            <div>
                                 {{$property->bedroom_qty}} bedroom
                            </div>
                        </div>
                        <div class="flex property-details-options">
                            <div>
                                <span class="fa fa-shower"></span>
                            </div>
                            <div>
                                 {{$property->bathroom_qty}} bathroom
                            </div>
                        </div>
                        @foreach ($property->characteristics as $characteristic)
                            <div class="flex property-details-options">
                                <div>
                                    <span class="fa  fa-check-circle text-[#00CC00]"></span>
                                </div>
                                <div>
                                   {{$characteristic->characteristic->name}}
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        House rules for guests
                    </div>


                    <div class="mt-8 grid grid-cols-2 gap-8">
                        @foreach ($property->houseRules as $houseRule)

                            <div class="flex">
                                <div class="w-8 flex-shrink-0">
                                    <span class="fa fa-times-circle mt-1 text-red-500"></span>
                                </div>
                                <div>
                                    <div class="font-bold">
                                      {{$houseRule->rule->name}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>

                <div class="md:w-1/3 md:pl-8 md:border-l mt-4 pt-4 border-t md:mt-0 md:pt-0 md:border-t-0">
                    <div class="px-6 py-4 bg-gray-100">
                        Payment statistics
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-8">
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total revenue
                                </div>
                                <div class="font-bold">
                                    £3,500.00
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total payouts
                                </div>
                                <div class="font-bold">
                                    £2,000.00
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total TDHH commission
                                </div>
                                <div class="font-bold">
                                    £1,500.00
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        Booking statistics
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-8">
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-building mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total bookings received
                                </div>
                                <div class="font-bold">
                                    24
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-building mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total bookings completed
                                </div>
                                <div class="font-bold">
                                    18
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-building mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total bookings cancelled
                                </div>
                                <div class="font-bold">
                                    6
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        Refund statistics
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-8">
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total refund occurence
                                </div>
                                <div class="font-bold">
                                    4 time(s)
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total refund payout to partner
                                </div>
                                <div class="font-bold">
                                    £60
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-money-bill mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Total refund payout to customer
                                </div>
                                <div class="font-bold">
                                    £550
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

