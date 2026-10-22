@extends('layouts.partner')


@section('title')
    Create new property
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


            $(".cleaning-per-night").change(function(){
                if ($(this).val() === "yes"){
                    $(".cleaning-per-night-yes").removeClass("hidden");
                }
                else{
                    $(".cleaning-per-night-yes").addClass("hidden");
                }
            })
            $("#country-id").change(function(){
                var idCountry = this.value;
                console.log(idCountry);
                $("#state-id").html('');
                $.ajax({
                    url: "{{url('api/fetch-states')}}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#state-id').html('<option value="">Select State</option>');
                        $.each(result.states, function (key, value) {
                            $("#state-id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        $('#city-id').html('<option value="">Select City</option>');
                    }
                });
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


            function formatTime24to12(timeString) {
                const [hourString, minute] = timeString.split(":");
                const hour = +hourString % 24;
                return (hour % 12 || 12) + ":" + minute + (hour < 12 ? " AM" : " PM");
            }


            $(".check-in-time-from, .check-in-time-to").change(function(){
                const check_in_time_from = $(".check-in-time-from").val().trim();
                const check_in_time_to = $(".check-in-time-to").val().trim();
                let check_in_time_text = "";

                if (check_in_time_from !== ""){
                    check_in_time_text = "Check-in from " + formatTime24to12(check_in_time_from);
                    if (check_in_time_to !== ""){
                        check_in_time_text = "Check-in between " + formatTime24to12(check_in_time_from) + " to " + formatTime24to12(check_in_time_to);

                        const time_from = parseInt(check_in_time_from.replace(":", ""));
                        const time_to = parseInt(check_in_time_to.replace(":", ""));
                        if (time_from >= time_to){
                            check_in_time_text = "Invalid time range";
                            alert("Check-in time (from) must be lower than Check-in time (to)");
                        }
                    }
                }

                $(".check-in-time-text").text(check_in_time_text);
            })


            $(".check-out-time-from, .check-out-time-to").change(function(){
                const check_out_time_from = $(".check-out-time-from").val().trim();
                const check_out_time_to = $(".check-out-time-to").val().trim();
                let check_out_time_text = "";

                if (check_out_time_to !== ""){
                    check_out_time_text = "Check-out by " + formatTime24to12(check_out_time_from);
                    if (check_out_time_from !== ""){
                        check_out_time_text = "Check-out between " + formatTime24to12(check_out_time_from) + " to " + formatTime24to12(check_out_time_to);

                        const time_from = parseInt(check_out_time_from.replace(":", ""));
                        const time_to = parseInt(check_out_time_to.replace(":", ""));
                        if (time_from >= time_to){
                            alert("Check-out time (from) must be lower than Check-out time (to)");
                        }
                    }
                }

                $(".check-out-time-text").text(check_out_time_text);
            })


            $(".languages-show-more").click(function(){
                $(".languages-all").removeClass("hidden");
                $(this).addClass("hidden");
            })


            toggle_refund_policy();
            $(".refund-status").change(function(){
                toggle_refund_policy();
            })


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
                Create new property
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
                    <div class="active">
                        General Info
                    </div>
                    <div>
                        Property Options
                    </div>
                    <div>
                        Room Details
                    </div>
                    <div>
                        Property Preview
                    </div>
                </div>
            </div>

            <div class="mt-1 p-8 min-h-[400px] bg-white">
                <form method="post" action="{{route('partner.property.create.generalInfo.submit')}}" class="max-w-xl mx-auto" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>
                            Property name
                            <span class="form-input-required">*</span>
                        </label>
                        <input type="text" name="name" class="form-input" />
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <div class="form-group">
                                <label>
                                    Cover image
                                    <span class="form-input-required">*</span>
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
                                        Other images
                                        <span class="form-input-required">(min: 5, max: 20)</span>
                                    </label>
                                    <input type="file"  name="property_images[]" multiple="multiple" accept="image/*" onChange="image_render(this, 'others')" class="form-input" />
                                </div>
                                <div class="hidden flex flex-wrap image_others_display_box">
                                    {{-- <div class="w-[44px] max-h-[44px] mr-2 mb-2 rounded-sm bg-black"></div> --}}
                                </div>
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
                                <select name="country_id" id="country-id" class="form-input"  required>
                                    <option value="">Select country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="state_id" class="form-input" id="state-id" required>
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="city_id" class="form-input" id="city-id" required>
                                    <option value="">Select City</option>

                                </select>
                            </div>
                            {{-- <div class="form-group">
                                <label>
                                    City
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="city" class="form-input" />
                            </div> --}}
                            <div class="form-group">
                                <label>
                                    Postal code
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="postal_code" class="form-input" />
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>
                                    Full Address
                                    <span class="form-input-required">*</span>
                                </label>
                                <textarea name="address" class="form-input" style="height: 212px;"></textarea>
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
                                    <input type="number" name="price" class="form-input w-full property-price" style="padding-left: 32px !important;">
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
                                <input type="number" class="form-input" name="min_bookable_night" />
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
                                    <option value="" selected disabled>Select</option>
                                    <option value="yes">Yes</option>
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
                                    <option value="" selected disabled>Select</option>
                                    <option value="per_night">Cleaning per night</option>
                                    <option value="per_stay">Cleaning per stay</option>
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
                                    <input type="number" name="cleaning_fee" class="form-input w-full" style="padding-left: 32px !important;">
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
                                    <tr>
                                        <td>
                                            <select name="days_before[1]" id="" class="form-input refund-days" style="margin-bottom: 0 !important;">
                                                <option value="1" selected>1</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="allow_refund[1]" id="" class="form-input refund-status" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="percent_refund[1]" id="" class="form-input refund-percent hidden" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="60">60</option>
                                                <option value="70">70</option>
                                                <option value="80">80</option>
                                                <option value="90">90</option>
                                                <option value="100">100</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="days_before[7]" id="" class="form-input refund-days" style="margin-bottom: 0 !important;">
                                                <option value="7" selected>7</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="allow_refund[7]" id="" class="form-input refund-status" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="percent_refund[7]" id="" class="form-input refund-percent hidden" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="60">60</option>
                                                <option value="70">70</option>
                                                <option value="80">80</option>
                                                <option value="90">90</option>
                                                <option value="100">100</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="days_before[14]" id="" class="form-input refund-days" style="margin-bottom: 0 !important;">
                                                <option value="14" selected>14</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="allow_refund[14]" id="" class="form-input refund-status" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="percent_refund[14]" id="" class="form-input refund-percent hidden" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="60">60</option>
                                                <option value="70">70</option>
                                                <option value="80">80</option>
                                                <option value="90">90</option>
                                                <option value="100">100</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="days_before[30]" id="" class="form-input refund-days" style="margin-bottom: 0 !important;">
                                                <option value="30" selected>30</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="allow_refund[30]" id="" class="form-input refund-status" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="percent_refund[30]" id="" class="form-input refund-percent hidden" style="margin-bottom: 0 !important;">
                                                <option value="" selected disabled>Select</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                                <option value="60">60</option>
                                                <option value="70">70</option>
                                                <option value="80">80</option>
                                                <option value="90">90</option>
                                                <option value="100">100</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div class="form-group">
                            <label>
                                Guest check-in time
                                <span class="form-input-required">*</span>
                            </label>
                            <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <div>
                                    <label>
                                        From <span class="text-gray-400">(required)</span>
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <label class="relative block">
                                        <div class="pointer-events-none w-16 h-full absolute flex">
                                            <span class="m-auto">
                                                Time
                                            </span>
                                        </div>
                                        <input type="time" name="check_in_from" class="form-input w-full check-in-time-from" style="padding-left: 64px !important;">
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        To <span class="text-gray-400">(optional)</span>
                                    </label>
                                    <label class="relative block">
                                        <div class="pointer-events-none w-16 h-full absolute flex">
                                            <span class="m-auto">
                                                Time
                                            </span>
                                        </div>
                                        <input type="time" name="check_in_to" class="form-input w-full check-in-time-to" style="padding-left: 64px !important;">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="check-in-time-text text-red-500"></div>
                    </div>



                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div class="form-group">
                            <label>
                                Guest check-out time
                                <span class="form-input-required">*</span>
                            </label>
                            <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <div>
                                    <label>
                                        From <span class="text-gray-400">(optional)</span>
                                    </label>
                                    <label class="relative block">
                                        <div class="pointer-events-none w-16 h-full absolute flex">
                                            <span class="m-auto">
                                                Time
                                            </span>
                                        </div>
                                        <input type="time" name="check_out_from" class="form-input w-full check-out-time-from" style="padding-left: 64px !important;">
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        To <span class="text-gray-400">(required)</span>
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <label class="relative block">
                                        <div class="pointer-events-none w-16 h-full absolute flex">
                                            <span class="m-auto">
                                                Time
                                            </span>
                                        </div>
                                        <input type="time" name="check_out_to" class="form-input w-full check-out-time-to" style="padding-left: 64px !important;">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="check-out-time-text text-red-500"></div>
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
                                                    <input type="checkbox" name="languages[][language_id]"  id="{{$language->id}}" value="{{$language->id}}" class="form-input-checkbox">
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
                                                    <input type="checkbox" name="languages[][language_id]" id="{{$language->id}}" value="{{$language->id}}" class="form-input-checkbox">
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
                            Save & continue
                            <span class="fa fa-angle-right ml-2"></span>
                        </button>
                    </div>



                </form>
            </div>

        </div>

    </div>
@endsection

