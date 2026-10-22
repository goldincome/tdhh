@extends('layouts.admin')


@section('title')
    Partner details
@endsection


@section('css')
    <style>
        .admin-sidebar-partners{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">

        <form method="POST" action="">
            @csrf
            <div id="modal-enable" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                <div class="modal-full max-w-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-header-text">
                                Enable partner
                            </div>
                            <button type="button" data-modal-toggle="modal-enable" class="modal-header-close">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <input type="hidden" id="bedroom-index" class="text-black" />
                        </div>

                        <div class="modal-body">

                            <span class="font-bold">Are you sure you want to enable this partner?</span>
                            <ul class="list-disc mt-4 ml-4">
                                <li>
                                    Once completed, the partner will be able to have access to his/her account.
                                </li>
                                <li>
                                    You can deactivate the partner anytime.
                                </li>
                            </ul>

                        </div>

                        <div class="modal-footer">
                            <button type="button" data-modal-toggle="modal-enable" class="btn btn-transparent-black">Cancel</button>
                            <button type="submit" class="btn text-white bg-green-500">
                                Enable partner
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        <form method="POST" action="">
            @csrf
            <div id="modal-disable" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                <div class="modal-full max-w-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-header-text">
                                Disable partner
                            </div>
                            <button type="button" data-modal-toggle="modal-disable" class="modal-header-close">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <input type="hidden" id="bedroom-index" class="text-black" />
                        </div>

                        <div class="modal-body">

                            <span class="font-bold">Are you sure you want to disable this partner?</span>
                            <ul class="list-disc mt-4 ml-4">
                                <li>
                                    Once completed, the partner will not have access to his/her account anymore.
                                </li>
                                <li>
                                    You can re-activate the partner anytime.
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
                                Disable partner
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        <div class="page-title-account">
            <div>
                Partner details
            </div>
            <div class="pull-right flex justify-end space-x-2">
                <a href="/admin/reservations?partner={{$partner->id}}" target="_blank" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-calendar-check mr-2"></span>
                    View reservations
                </a>

                <a href="/admin/property?partner={{$partner->id}}" target="_blank" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-home mr-2"></span>
                    View properties
                </a>

                {{--
                <button type="button" data-modal-toggle="modal-enable" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-check mr-2"></span>
                    Enable
                </button>
                --}}
                <button type="button" data-modal-toggle="modal-disable" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-times mr-2"></span>
                    Disable
                </button>

                <a href="{{url()->previous()}}" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="box">


            <div class="overflow-auto">
                <div>

                    <div class="px-6 py-4 bg-gray-100">
                        User Information
                    </div>


                    <div class="mt-8 grid grid-cols-2 gap-8">
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-hashtag mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Partner ID
                                </div>
                                <div class="font-bold">
                                    #{{$partner->reference}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fa fa-user mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Name
                                </div>
                                <div class="font-bold">
                                  {{$partner->fullName}}
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
                                    {{$partner->email}}

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
                                   {{$partner->phone}}
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-calendar mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Status
                                </div>
                                <div class="font-bold">
                                     @if($partner->status)
                                        <span class="text-green-500">Active</span>
                                    @else
                                       <span class="text-red-500">Inctive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-8 flex-shrink-0">
                                <span class="fas fa-calendar mt-1"></span>
                            </div>
                            <div>
                                <div>
                                    Partner since
                                </div>
                                <div class="font-bold">
                                   {{$partner->created_at->format('M d, Y')}}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        Properties
                    </div>


                    <div class="mt-2">
                        <div class="table-container">
                            <table class="table table-auto table-border table-align">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Property Info</th>
                                        <th style="min-width: 150px;">Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            <div class="flex space-x-2">
                                                <a href="/admin/property/12345" target="_blank" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                    <img src="{{asset('img/bgs/manchester.jpg')}}" alt="tdhh" class="object-cover m-auto" />
                                                </a>
                                                <div class="md:my-auto">
                                                    <div>
                                                        <a href="/admin/property/12345" target="_blank"  class="text-fade hover:underline">
                                                            #1234567890
                                                        </a>
                                                        <a href="/admin/property/12345" target="_blank"  class="font-bold hover:underline ellipsis-2-lines">
                                                            3 bedroom full suite
                                                        </a>
                                                        <div class="mt-1 text-fade text-xs">
                                                            <span class="fa fa-map-marker mr-1"></span>
                                                            address, postal_code, city, state, country
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 flex space-x-4 text-sm">
                                                        <div>
                                                            <span class="fa fa-bed"></span>
                                                            2
                                                        </div>
                                                        <div>
                                                            <span class="fa fa-shower"></span>
                                                            3
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                Created
                                                <div class="font-bold">
                                                    06 Feb, 2023
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                Last updated
                                                <div class="font-bold">
                                                    06 Feb, 2023
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="label label-green">
                                                Available
                                            </div>
                                        </td>

                                        <td>
                                            <a href="/admin/property/12345" class="btn btn-transparent-black btn-sm">View</a>
                                        </td>

                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            <a href="/admin/property?partner={{$partner->id}}" target="_blank" class="btn bg-gray-400 btn-block">
                                <span class="fa fa-calendar-check mr-2"></span>
                                View all properties
                            </a>
                        </div>
                    </div>



                    <div class="px-6 py-4 mt-8 bg-gray-100">
                        Recent reservations
                    </div>


                    <div class="mt-2">
                        <div class="table-container">
                            <table class="table table-auto table-border table-align">
                                <thead>
                                    <tr>
                                        <th>Number of nights</th>
                                        {{-- <th>Avg Price per night</th> --}}
                                        <th>Parking Fee</th>
                                        <th>Cleaning Fee</th>
                                        <th>Total price</th>
                                        <th>Amount paid</th>
                                        <th>Status</th>
                                        <th>Date of payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($partner->recentReservations as $reservation)
                                        <tr>
                                            <td>
                                                {{$reservation->num_of_night}} nights
                                            </td>
                                            <td>
                                                {{currency_format($reservation->cleaning_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                            </td>
                                            <td>
                                                {{currency_format($reservation->parking_fee, $reservation->price_information->country_code ?? config('currency.default'))}}
                                            </td>
                                            <td>
                                                {{currency_format($reservation->total, $reservation->price_information->country_code ?? config('currency.default'))}}
                                            </td>
                                            <td>
                                                {{currency_format($reservation->total, $reservation->price_information->country_code ?? config('currency.default'))}}
                                            </td>
                                            <td>

                                                <div class="label label-{{$reservation->payment_status->color}}">
                                                    {{$reservation->payment_status->name}}
                                                </div>
                                            </td>
                                            <td>
                                               {{$reservation->created_at->format('D d M Y')}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                no reservation
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            <a href="/admin/reservations?partner={{$partner->id}}" target="_blank" class="btn bg-gray-400 btn-block">
                                <span class="fa fa-calendar-check mr-2"></span>
                                View all reservations
                            </a>
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>
@endsection

