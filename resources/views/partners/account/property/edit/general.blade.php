@extends('layouts.partner')


@section('title')
    Edit property - #09390309323223
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
                const discount_status = $("input[name='discount_toggle']:checked").val();
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
                const discount_status = $("input[name='discount_toggle']:checked").val();
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


            calculate_cleaning_fee();
            $(".cleaning-per-night").change(function(){
                calculate_cleaning_fee();
            });


            calculate_check_in_time();
            $(".check-in-time-from, .check-in-time-to").change(function(){
                calculate_check_in_time();
            })


            calculate_check_out_time();
            $(".check-out-time-from, .check-out-time-to").change(function(){
                calculate_check_out_time();
            })


            $(".languages-show-more").click(function(){
                $(".languages-all").removeClass("hidden");
                $(this).addClass("hidden");
            })


            toggle_refund_policy();
            $(".refund-status").change(function(){
                toggle_refund_policy();
            })

             $('#state-id').on('change', function () {
                var idState = this.value;
                $("#city-id").html('');
                $.ajax({
                    url: "{{url('api/fetch-cities')}}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (res) {
                        $('#city-id').html('<option value="">Select City</option>');
                        $.each(res.cities, function (key, value) {
                            $("#city-id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });


        })


        function image_render(input, type){
            if (type === "cover"){
                if (input.files[0]){
                    $(".image_cover_display_box").removeClass("hidden");
                    $('.image_cover_display')[0].src = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
                }
                else{
                    $(".image_cover_display_box").addClass("hidden");
                }
            }

            const max_files_others = 20;
            if (type === "others"){
                if (input.files.length > 0){
                    $(".image_others_display_box").removeClass("hidden");
                    $(".image_others_display_box").html("");
                    for (let i = 0; i < input.files.length; i++){
                        if (i === max_files_others){
                            break;
                        }
                        if (input.files[i]){
                            const img_others = `<div class="w-[44px] max-h-[44px] mr-2 mb-2 rounded-sm bg-black overflow-hidden">
                                <img src="" alt="cover image" class="image_others_display_` + i + ` object-contain m-auto">
                            </div>`;
                            $(".image_others_display_box").append(img_others);
                            $('.image_others_display_' + i)[0].src = (window.URL ? URL : webkitURL).createObjectURL(input.files[i]);
                        }
                    }
                }
                else{
                    $(".image_others_display_box").addClass("hidden");
                }
            }
        }


        function calculate_cleaning_fee(){
            if ($(".cleaning-per-night").val() === "yes"){
                $(".cleaning-per-night-yes").removeClass("hidden");
            }
            else{
                $(".cleaning-per-night-yes").addClass("hidden");
            }
        }


        function calculate_check_in_time(){
            const check_in_time_from = $(".check-in-time-from").val().trim();
            const check_in_time_to = $(".check-in-time-to").val().trim();
            if (check_in_time_from !== "" & check_in_time_to !== ""){
                const time_from = parseInt(check_in_time_from.replace(":", ""));
                const time_to = parseInt(check_in_time_to.replace(":", ""));
                if (time_from >= time_to){
                    alert("Check-in time (from) must be lower than Check-in time (to)");
                }
            }
        }


        function calculate_check_out_time(){
            const check_out_time_from = $(".check-out-time-from").val().trim();
            const check_out_time_to = $(".check-out-time-to").val().trim();
            if (check_out_time_from !== "" & check_out_time_to !== ""){
                const time_from = parseInt(check_out_time_from.replace(":", ""));
                const time_to = parseInt(check_out_time_to.replace(":", ""));
                if (time_from >= time_to){
                    alert("Check-out time (from) must be lower than Check-out time (to)");
                }
            }
        }


        function toggle_refund_policy(){
            $(".table-refund-policy tr").each(function(){
                if ($(this).find(".refund-status").val() === "yes"){
                    $(this).find(".refund-percent").removeClass("hidden");
                }
                else{
                    $(this).find(".refund-percent").addClass("hidden");
                }
            })
        }


    </script>
@endsection


@section('content')
    <div class="container">

        <div class="page-title-account">
            <div>
                Edit property
                <a href="/partners/property/12345" target="_blank" class="hover:underline font-montserrat text-sm text-tdhh-orange">
                    #{{$property->reference}}
                </a>
            </div>
            <div class="pull-right">
                <a href="/partners/dashboard/property" type="button" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="mt-4">

            <div class="tabs">
                <div class="grid grid-cols-4">
                    <a href="/partners/property/edit/123" class="active">
                        General Info
                    </a>
                    <a href="/partners/property/edit/123/options">
                        Property Options
                    </a>
                    <a href="/partners/property/edit/123/room">
                        Room Details
                    </a>
                    <a href="/partners/property/edit/123/preview">
                        Property Preview
                    </a>
                </div>
            </div>

            <div class="mt-1 p-8 min-h-[400px] bg-white">
                <form method="post" action="{{route('partner.property.edit.generalInfo.submit', $property->id)}}" class="max-w-xl mx-auto" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>
                            Property name
                            <span class="form-input-required">*</span>
                        </label>
                        <input type="text" name="name" value="{{$property->name}}" class="form-input" />
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <div class="form-group">
                                    <label>
                                        Replace cover image
                                    </label>
                                    <input name="cover_image" type="file" accept="image/*" onChange="image_render(this, 'cover')" class="form-input" />
                                </div>
                                <div class="w-24 max-h-[96px] rounded-sm bg-black image_cover_display_box overflow-hidden hidden flex">
                                    <img src="" alt="cover image" class="image_cover_display object-contain m-auto">
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div class="form-group">
                                        <label>
                                            Add more images
                                        </label>
                                        <input name="property_images[]" type="file" multiple="multiple" accept="image/*" onChange="image_render(this, 'others')" class="form-input" />
                                    </div>
                                    <div class="hidden flex flex-wrap image_others_display_box">
                                        {{-- <div class="w-[44px] max-h-[44px] mr-2 mb-2 rounded-sm bg-black"></div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                Images to delete
                            </div>
                            <div class="mt-2 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-8">
                                @foreach ($property->propertyImages as $image)
                                    <label class="w-max cursor-pointer ">
                                        <div class="flex space-x-3">
                                            <div class="mt-0.5 flex">
                                                <input type="checkbox" name="property_images_delete[{{$image->id}}]" value="{{$image->id}}" id="" class="form-input-checkbox my-auto">
                                            </div>
                                            <div class="my-auto">
                                                <div class="h-16 flex-shrink-0 bg-black overflow-hidden cursor-pointer group">
                                                    <img src={{asset($image->getUrl())}} alt="tdhh" class="object-cover transform animate group-hover:scale-125" />
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                                {{--
                                <label class="w-max cursor-pointer">
                                    <div class="flex space-x-3">
                                        <div class="mt-0.5 flex">
                                            <input type="checkbox" name="" id="" class="form-input-checkbox my-auto">
                                        </div>
                                        <div class="my-auto">
                                            <div class="h-16 flex-shrink-0 bg-black overflow-hidden cursor-pointer group">
                                                <img src={{asset('img/bgs/home-sample-1.jpg')}} alt="tdhh" class="object-cover transform animate group-hover:scale-125" />
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                --}}
                            </div>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <div class="form-group">
                                <label>
                                    Country
                                    <span class="form-input-required">*</span>
                                </label>
                                <select name="country" disabled id="" class="form-input">
                                     @foreach ($countries as $country)
                                        <option value="{{$country->id}}"
                                            @if($country->id == $property->country_id) selected @endif
                                        >{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="state_id" class="form-input" id="state-id" required>
                                     @foreach ($property->country->states as $state)
                                        <option value="{{$state->id}}"
                                            @if($state->id == $property->state_id) selected @endif
                                        >{{$state->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="city_id" class="form-input" id="city-id" >
                                    <option value="">Select City</option>
                                    @foreach ($property->state->cities as $city)
                                        <option value="{{$city->id}}"
                                            @if($city->id == $property->city_id) selected @endif
                                        >{{$city->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    Postal code
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="postal_code" value="{{$property->postal_code}}" class="form-input" />
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>
                                    Full Address
                                    <span class="form-input-required">*</span>
                                </label>
                                <textarea name="address" class="form-input" style="height: 212px;">{{$property->address}}</textarea>
                            </div>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


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
                                    <input type="number" name="price" value="{{$property->todayPrice}}" class="form-input w-full property-price" style="padding-left: 32px !important;">
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
                                                            <input type="radio" name="discount_toggle" value="on" class="form-input-checkbox discount-toggle">
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
                                                                <input type="radio" name="discount_toggle" value="off" class="form-input-checkbox discount-toggle">
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
                                                            Discounted price per night (inc. tax):
                                                            <span class="form-input-required">*</span>
                                                        </label>
                                                        <label class="relative block">
                                                            <div class="pointer-events-none w-8 h-full absolute flex">
                                                                <span class="m-auto">
                                                                    £
                                                                </span>
                                                            </div>
                                                            <input type="number" name="discount_price" class="form-input w-full property-price-discount" style="padding-left: 32px !important;">
                                                        </label>
                                                    </div>

                                                    <div class="flex justify-between space-x-6">
                                                        <div class="w-1/2 form-group">
                                                            <label>
                                                                Discount starting date:
                                                            </label>
                                                            <input type="date" class="form-input">
                                                        </div>
                                                        <div class="w-1/2 form-group">
                                                            <label>
                                                                Discount ending date:
                                                            </label>
                                                            <input type="date" class="form-input">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="mt-4">
                                                    <div class="mb-2">
                                                        Days when discount should be active:
                                                        <span class="form-input-required">*</span>
                                                    </div>
                                                    @foreach (["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"] as $day)
                                                        <label class="flex space-x-3 cursor-pointer mb-3">
                                                            <input type="checkbox" class="form-input-checkbox">
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
                                <input type="number" class="form-input" name="min_bookable_night" value="{{$property->min_bookable_night}}" />
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


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8">
                        <div>
                            <div class="form-group">
                                <label>
                                    Do you charge cleaning fee?
                                    <span class="form-input-required">*</span>
                                </label>
                                <select name="cleaning_available" id="" class="form-input cleaning-per-night">
                                    <option value="yes" @if($property->cleaning_fee) selected @endif>Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                        <div></div>
                        <div>
                            <div class="form-group hidden cleaning-per-night-yes">
                                <label>
                                    Cleaning fee type
                                    <span class="form-input-required">*</span>
                                </label>
                                <select name="cleaning_type" id="" class="form-input">
                                    <option value="per-stay" @if($property->cleaning_type == 'per_stay')  selected @endif>Cleaning per stay</option>
                                    <option value="per-night" @if($property->cleaning_type == 'per_night')  selected @endif>Cleaning per night</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-group hidden cleaning-per-night-yes">
                                <label>
                                    Cleaning fee
                                    <span class="form-input-required">*</span>
                                </label>
                                <label class="relative block">
                                    <div class="pointer-events-none w-8 h-full absolute flex">
                                        <span class="m-auto">
                                            £
                                        </span>
                                    </div>
                                    <input type="number" name="cleaning_fee" value="{{$property->cleaning_fee}}" class="form-input w-full" style="padding-left: 32px !important;">
                                </label>
                            </div>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="mt-10">
                        <div class="p-3 bg-gray-200 text-center">
                            Refund / Cancellation Policy

                        </div>
                        <div class="border">
                            <table class="table table-property-form table-refund-policy">
                                <thead>
                                    <tr>
                                        <th>
                                            Days before
                                        </th>
                                        <th>
                                            Allow refund
                                        </th>
                                        <th>
                                            Percentage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->partnerRefundPolicy as $key => $refund)
                                        <tr>
                                            <td>
                                                <select name="days_before[{{$key}}]" id="" class="form-input refund-days" style="margin-bottom: 0 !important;">
                                                        <option value="{{$key}}" selected>{{$key}}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="allow_refund[{{$key}}]" id="" class="form-input refund-status" style="margin-bottom: 0 !important;">
                                                    <option value="yes" @if($refund->allow_refund == 'yes') selected @endif>Yes</option>
                                                    <option value="no" @if($refund->allow_refund == 'no') selected @endif>No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="percent_refund[{{$key}}]" id="" class="form-input refund-percent hidden" style="margin-bottom: 0 !important;">
                                                    <option value="">Select</option>
                                                    <option value="10" @if($refund->percent_refund == 10) selected @endif >10</option>
                                                    <option value="20" @if($refund->percent_refund == 20) selected @endif>20</option>
                                                    <option value="30" @if($refund->percent_refund == 30) selected @endif>30</option>
                                                    <option value="40" @if($refund->percent_refund == 40) selected @endif>40</option>
                                                    <option value="50" @if($refund->percent_refund == 50) selected @endif>50</option>
                                                    <option value="60" @if($refund->percent_refund == 60) selected @endif>60</option>
                                                    <option value="70" @if($refund->percent_refund == 70) selected @endif>70</option>
                                                    <option value="80" @if($refund->percent_refund == 80) selected @endif>80</option>
                                                    <option value="90" @if($refund->percent_refund == 90) selected @endif>90</option>
                                                    <option value="100" @if($refund->percent_refund == 100) selected @endif>100</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />

                    <div>
                        <div class="form-group">
                            <label>
                                Check-in time
                                <span class="form-input-required">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <label class="relative block">
                                    <div class="pointer-events-none w-16 h-full absolute flex">
                                        <span class="m-auto">
                                            From
                                        </span>
                                    </div>
                                    <input type="time" name="check_in_from" value="{{$property->check_in_from}}" class="form-input w-full check-in-time-from" style="padding-left: 64px !important;">
                                </label>
                                <label class="relative block">
                                    <div class="pointer-events-none w-10 h-full absolute flex">
                                        <span class="m-auto">
                                            To
                                        </span>
                                    </div>
                                    <input type="time" name="check_in_to" value="{{$property->check_in_to}}" class="form-input w-full check-in-time-to" style="padding-left: 40px !important;">
                                </label>
                            </div>
                        </div>
                    </div>



                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div class="form-group">
                            <label>
                                Check-out time
                                <span class="form-input-required">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <label class="relative block">
                                    <div class="pointer-events-none w-16 h-full absolute flex">
                                        <span class="m-auto">
                                            From
                                        </span>
                                    </div>
                                    <input type="time" name="check_out_from" value="{{$property->check_out_from}}" class="form-input w-full check-out-time-from" style="padding-left: 64px !important;">
                                </label>
                                <label class="relative block">
                                    <div class="pointer-events-none w-10 h-full absolute flex">
                                        <span class="m-auto">
                                            To
                                        </span>
                                    </div>
                                    <input type="time" name="check_out_to" value="{{$property->check_out_to}}"class="form-input w-full check-out-time-to" style="padding-left: 40px !important;">
                                </label>
                            </div>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div>
                            Language spoken
                        </div>
                        <div class="mt-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($languages as $language)
                                     @if($loop->iteration <= 6)
                                        <label class="cursor-pointer">
                                            <div class="flex space-x-3">
                                                <div class="mt-0.5 flex">
                                                    <input type="checkbox" name="languages[][language_id]" @if(in_array($language->id, $property->languages()->pluck('language_id')->toArray())) checked @endif
                                                    id="{{$language->id}}" value="{{$language->id}}" class="form-input-checkbox">
                                                </div>
                                                <div class="my-auto">
                                                    {{$language->name}}
                                                </div>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            <div class="hidden languages-all mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($languages as $language)
                                    @if($loop->iteration > 6)
                                        <label class="cursor-pointer">
                                            <div class="flex space-x-3">
                                                <div class="mt-0.5 flex">
                                                    <input type="checkbox" name="languages[][language_id]" @if(in_array($language->id, $property->languages()->pluck('language_id')->toArray())) checked @endif
                                                    id="{{$language->id}}" value="{{$language->id}}" class="form-input-checkbox">
                                                </div>
                                                <div class="my-auto">
                                                    {{$language->name}}
                                                </div>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="languages-show-more text-tdhh-orange cursor-pointer hover:underline">
                                Show more <span class="fa fa-angle-down ml-1"></span>
                            </span>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="flex space-x-4 justify-end">
                        <button class="btn btn-transparent-black">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </button>
                        <button class="btn btn-orange">
                            Edit property
                            <span class="fa fa-check ml-2"></span>
                        </button>
                    </div>



                </form>
            </div>

        </div>

    </div>
@endsection

