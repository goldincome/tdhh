@extends('layouts.partner')


@section('title')
    Property
@endsection


@section('css')
    <style>
        .admin-sidebar-property{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('js')
    <script>
        $(document).ready(function(){


            calculate_price();
            $(".property-price, .property-price-discount").keyup(function(){
                calculate_price();
            })


            function calculate_price(){
                let property_price = $(".property-price").val().trim();
                const discount_status = $("input[name='rate_toggle']:checked").val();
                if (discount_status === "on"){
                    property_price = $(".property-price-discount").val().trim();
                }
                if (property_price !== ""){
                    const property_price_earnings = (65 / 100) * property_price;
                    const property_price_commission = (15 / 100) * property_price;
                    const property_price_vat = (20 / 100) * property_price;
                    $(".property-price-display").text("£" + parseInt(property_price).toFixed(2));
                    $(".property-price-earnings").text("£" + property_price_earnings.toFixed(2));
                    $(".property-price-commission").text("£" + property_price_commission.toFixed(2));
                    $(".property-price-vat").text("£" + property_price_vat.toFixed(2));
                }
                else{
                    $(".property-price-display").text("£0");
                    $(".property-price-earnings").text("£0");
                    $(".property-price-commission").text("£0");
                    $(".property-price-vat").text("£0");
                }
            }


            process_discount_display();
            $(".discount-toggle").change(function(){
                process_discount_display();
                calculate_price();
            })


            function process_discount_display(){
                const discount_status = $("input[name='rate_toggle']:checked").val();
                if (discount_status === "on"){
                    $(".discount-status, .discount-status-yes").removeClass("hidden");
                    $(".discount-status-no").addClass("hidden");
                }
                else if (discount_status === "off"){
                    $(".discount-status, .discount-status-no").removeClass("hidden");
                    $(".discount-status-yes").addClass("hidden");
                }
                else{
                    $(".discount-status, .discount-status-yes, .discount-status-no").addClass("hidden");
                }
            }



        })

            function priceSetting(property){
                $('.propertyId').val(property.id);
                $('#currentPrice').val(property.price);
                $('#minBookableNight').val(property.min_bookable_night);
            }

            function blockRoom(property){
                $('.propertyId').val(property.id);
            }

    </script>
@endsection


@section('content')




    <div id="modal-price-settings" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
        <div class="modal-full max-w-2xl">
            <form class="modal-content"method="post" action="{{route('partner.property.edit.price-setting.submit')}}">
                @csrf
                <div class="modal-header">
                    <div class="modal-header-text">
                        Price settings
                    </div>
                    <button type="button" data-modal-toggle="modal-price-settings" class="modal-header-close">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <div class="form-group">
                                <label>
                                    Price per night (inc. tax)
                                    <span class="form-input-required">*</span>
                                </label>
                                <label class="relative block">
                                    <div class="pointer-events-none w-8 h-full absolute flex">
                                        <span class="m-auto">
                                            £
                                        </span>
                                    </div>
                                    <input type="number" name="price" id="currentPrice"  class="form-input w-full property-price" style="padding-left: 32px !important;">
                                </label>
                            </div>

                            <div data-modal-toggle="modal-price-discount" class="w-max py-0.5 text-tdhh-orange flex -mt-4 mb-6 cursor-pointer border-b border-transparent hover:border-black">
                                <div>
                                    Configure price rate settings
                                </div>
                                <div>
                                    <span class="fa fa-angle-right ml-2"></span>
                                </div>
                            </div>


                            <div id="modal-price-discount" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                                <div class="modal-full max-w-lg">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <div class="modal-header-text">
                                                Configure price rate settings
                                            </div>
                                            <button type="button" data-modal-toggle="modal-price-discount" class="modal-header-close">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">

                                            <div>
                                                <label class="cursor-pointer">
                                                    <div class="flex space-x-3">
                                                        <div class="mt-0.5 flex">
                                                            <input type="radio" name="rate_toggle" value="on" class="form-input-checkbox discount-toggle">
                                                        </div>
                                                        <div class="my-auto">
                                                            Activate discount
                                                        </div>
                                                    </div>
                                                </label>
                                                <div class="mt-1">
                                                    <label class="cursor-pointer">
                                                        <div class="flex space-x-3">
                                                            <div class="mt-0.5 flex">
                                                                <input type="radio" name="rate_toggle" value="off" class="form-input-checkbox discount-toggle">
                                                            </div>
                                                            <div class="my-auto">
                                                                Don't activate discount
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <hr class="mt-12 border-tdhh-brown" />
                                            <div class="text-center discount-status">
                                                <div class="px-4 py-2 bg-green-300 discount-status-yes hidden">
                                                    Discount is active
                                                </div>
                                                <div class="px-4 py-2 bg-red-200 discount-status-no hidden">
                                                    Discount is NOT active
                                                </div>
                                            </div>
                                            <div class="mb-8"></div>

                                            <div>
                                                <div>
                                                    <div class="form-group">
                                                        <label>
                                                            Rate price per night (inc. tax):
                                                            <span class="form-input-required">*</span>
                                                        </label>
                                                        <label class="relative block">
                                                            <div class="pointer-events-none w-8 h-full absolute flex">
                                                                <span class="m-auto">
                                                                    £
                                                                </span>
                                                            </div>
                                                            <input type="number" name="rate_price" class="form-input w-full property-price-discount" id="ratePrice" style="padding-left: 32px !important;">
                                                        </label>
                                                    </div>

                                                    <div class="flex justify-between space-x-6">
                                                        <div class="w-1/2 form-group">
                                                            <label>
                                                                Discount starting date:
                                                            </label>
                                                            <input type="date" value="" name="rate_start_date" class="form-input">
                                                        </div>
                                                        <div class="w-1/2 form-group">
                                                            <label>
                                                                Discount ending date:
                                                            </label>
                                                            <input type="date" value="" name="rate_end_date" class="form-input">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="mt-4">
                                                    <div class="mb-2">
                                                        Days when discount should be active:
                                                        <span class="form-input-required">*</span>
                                                    </div>
                                                    @foreach (["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"] as $key => $day)
                                                        <label class="flex space-x-3 cursor-pointer mb-3">
                                                            <input type="checkbox" name="rate_days[]" value="{{$key}}" class="form-input-checkbox">
                                                            <div class="-mt-1">
                                                                {{$day}}
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-tdhh-orange" data-modal-toggle="modal-price-discount">
                                                Continue
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label>
                                    Minimum bookable nights
                                </label>
                                <input type="hidden" value="" name="property_id" class="propertyId"/>
                                <input type="text" class="form-input" name="min_bookable_night" id="minBookableNight"/>
                            </div>
                        </div>
                        <div>

                            <div class="space-y-0.5">
                                <div class="px-3 py-3 bg-gray-100 flex justify-between discount-status">
                                    <div>
                                        Discount
                                    </div>
                                    <div class="text-right">
                                        <span class="text-green-500 discount-status-yes hidden">Active</span>
                                        <span class="text-red-500 discount-status-no hidden">Inactive</span>
                                    </div>
                                </div>
                                <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                    <div>
                                        Price to display
                                    </div>
                                    <div class="text-right property-price-display">
                                        £0
                                    </div>
                                </div>
                                <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                    <div>
                                        Your earning (inc. tax)
                                    </div>
                                    <div class="text-right property-price-earnings">
                                        £0
                                    </div>
                                </div>
                                <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                    <div>
                                        TDHH commission
                                    </div>
                                    <div class="text-right property-price-commission">
                                        £0
                                    </div>
                                </div>
                                <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                    <div>
                                        VAT
                                    </div>
                                    <div class="text-right property-price-vat">
                                        £0
                                    </div>
                                </div>
                                <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                    <div>
                                        Listing fee
                                    </div>
                                    <div class="text-right">
                                        £0
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" data-modal-toggle="modal-price-settings" class="btn btn-transparent-black">Close</button>
                    <button type="submit" class="btn btn-tdhh-orange">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>




    <div id="modal-block-room" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
        <div class="modal-full max-w-lg">
            <form class="modal-content" method="POST" action="{{route('partner.property.block-room.submit')}}">
                @csrf
                <input type="hidden"  name="property_id" class="propertyId"/>
                <div class="modal-header">
                    <div class="modal-header-text">
                        Block room
                    </div>
                    <button type="button" data-modal-toggle="modal-block-room" class="modal-header-close">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="modal-body mx-auto">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="form-group">
                            <label>
                                Date from:
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="date" name="date_from" class="form-input" />
                        </div>
                        <div class="form-group">
                            <label>
                                Date to:
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="date" name="date_to" class="form-input" />
                        </div>
                    </div>

                    <div class="mt-4 mb-2">
                        Select days:
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div class="flex flex-col space-y-2">

                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="1" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Monday
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="2" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Tuesday
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="3" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Wednesday
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="4" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Thursday
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="5" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Friday
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="6" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Saturday
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" name="block_days[]" value="0" id="" class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Sunday
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-10">
                        <div class="mb-2">
                            Action:
                        </div>
                        <label class="cursor-pointer">
                            <div class="flex space-x-3">
                                <div class="mt-0.5 flex">
                                    <input type="radio" name="status" value="on" id="" class="form-input-checkbox">
                                </div>
                                <div class="my-auto">
                                    Block the selected period
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <div class="flex space-x-3">
                                <div class="mt-0.5 flex">
                                    <input type="radio" name="status" value="off" id="" class="form-input-checkbox">
                                </div>
                                <div class="my-auto">
                                    Make the selected period available
                                </div>
                            </div>
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" data-modal-toggle="modal-block-room" class="btn btn-transparent-black">Close</button>
                    <button type="submit" class="btn btn-tdhh-orange">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>




    <div class="container">


        <div class="page-title-account">
            <div>
                My Properties
            </div>
            <div class="pull-right">
                <a href="/partners/property/create" class="btn btn-tdhh-orange">
                    Add new property
                </a>
            </div>
        </div>


        <div>

            <div class="tabs">
                <div class="grid grid-cols-2">
                    <a href="/partners/dashboard/property" class="active">
                        All Properties
                    </a>
                    <a href="/partners/dashboard/property?status=active">
                        Active Properties
                    </a>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-auto table-border table-align">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Property Info</th>
                            <th style="min-width: 150px;">Date</th>
                            <th>Status</th>
                            <th>TDHH messages</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties as $property)
                            <tr>
                                <td>
                                    {{$loop->iteration}}
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{route('front.details', $property->id)}}" target="_blank"  class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                            <img src={{asset($property->cover_image->getUrl())}} alt="tdhh" class="object-cover m-auto" />
                                        </a>
                                        <div class="md:my-auto">
                                            <div>
                                                <a href="{{route('front.details', $property->id)}}" target="_blank"  class="text-fade hover:underline">
                                                    #{{$property->reference}}
                                                </a>
                                                <a href="{{route('front.details', $property->id)}}" target="_blank"  class="font-bold hover:underline ellipsis-2-lines">
                                                    {{$property->name}}
                                                </a>
                                                <div class="mt-1 text-fade text-xs">
                                                    <span class="fa fa-map-marker mr-1"></span>
                                                    {{$property->city_name}} {{$property->state_name}} {{$property->country_name}}
                                                </div>
                                            </div>
                                            <div class="mt-1 flex space-x-4 text-sm">
                                                <div>
                                                    <span class="fa fa-bed"></span>
                                                    {{$property->bedroom_qty}}
                                                </div>
                                                <div>
                                                    <span class="fa fa-shower"></span>
                                                    {{$property->bathroom_qty}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        Created
                                        <div class="font-bold">
                                            {{$property->created_at->format('d M, Y')}}
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        Last updated
                                        <div class="font-bold">
                                            {{$property->updated_at->format('d M, Y')}}
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="label label-gray">
                                        {{$property->status->name}}
                                    </div>
                                </td>

                                <td>
                                    <div class="w-6 h-6 flex items-center justify-center rounded-full text-white bg-black">
                                        0
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        <button id="dropdownButton{{$property->id}}" data-dropdown-toggle="dropdown-{{$property->id}}" class="dropdown" type="button">
                                            Option
                                            <span class="fa fa-angle-down ml-2"></span>
                                        </button>
                                        <div id="dropdown-{{$property->id}}" class="dropdown-menu-nav hidden" >
                                            <ul class="py-1" aria-labelledby="dropdownButton-1">
                                                <li>
                                                    <a href="{{route('partner.property.edit.generalInfo', $property->id)}}" class="dropdown-menu-option">Edit</a>
                                                </li>
                                                <li>
                                                    <a href="/partners/property/sync-calendar/{{$property->id}}" class="dropdown-menu-option">Sync Calendar</a>
                                                </li>
                                                <li>
                                                    <button type="button" data-modal-toggle="modal-price-settings" class="dropdown-menu-option w-full text-left" onClick="priceSetting({{ $property }})">
                                                        Price settings
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" data-modal-toggle="modal-block-room" class="dropdown-menu-option w-full text-left" onClick="blockRoom({{ $property }})">
                                                        Block room
                                                    </button>
                                                </li>
                                                <li>

                                                    <button type="button"  class="dropdown-menu-option w-full text-left" onClick="">
                                                       <a href="{{route('partner.property.download-sync-calendar', $property->id)}}">Download Sync Calendar</a>
                                                    </button>
                                                </li>
                                                <form method="POST" action="">
                                                    @csrf
                                                    <li>
                                                        <button type="submit" class="dropdown-menu-option w-full text-left"
                                                            onclick="return confirm('Are you sure you want to disable this property? \nOnce done, customers will not be able to book this property. You can re-activate the property anytime.');">
                                                            Disable
                                                        </button>
                                                    </li>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No properties yet
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection

