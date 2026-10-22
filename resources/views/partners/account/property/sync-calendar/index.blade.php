@extends('layouts.partner')


@section('title')
    Sync Calendar
@endsection


@section('css')
    <style>
        .admin-sidebar-property{
            /* color: #000 !important; */
            background: #555 !important;
        }
        #calendar td.fc-day{
            cursor: pointer !important;
        }
        #calendar td.fc-day:hover{
            background: #ffa200 !important;
        }
        #calendar td.fc-day-past{
            background: #f3f3f3 !important;
        }
        #calendar .fc-daygrid-day-top{
            font-weight: bold !important;
        }
        #calendar .fc-event{
            background: #3c8ad5 !important;

        }
        #calendar .fc-event-past{
            /* background: #6fbdf8 !important; */
        }
    </style>
@endsection


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
    {{-- <script src="{{ asset('js/fullcalendar/fullcalendar@6.1.4.js') }}"></script> --}}

    {{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.4/index.global.min.js'></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.4/index.global.min.js"></script> --}}
    <script>
        var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var months = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
        var months_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];


        function padNumberWithZero(number){
            return number < 10 ? "0" + number : number;
        }


        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'UTC',
                initialView: 'dayGridMonth',
                selectable: true,

                events: function(fetchInfo, successCallback, failureCallback) {

                    let startDate = fetchInfo.startStr;
                    let endDate = fetchInfo.endStr;
                    let url = '/list-booking-calender-view/';

                    $.ajax({
                        url,
                        data: {
                            start: startDate,
                            end: endDate,
                            propertyId: '{{$property->id}}'
                        },
                        success: function(res) {
                            successCallback(
                                res.map(function(eventEl) {
                                    return {
                                        id: eventEl.id,
                                        title: eventEl.title,
                                        start: eventEl.start,
                                        end: eventEl.end,
                                    }
                                })
                            )
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },


                dateClick: function(info) {
                    // console.log("single info", info);

                    const dateSelected = info.dateStr.split("-");
                    const year = dateSelected[0];
                    const month = dateSelected[1];
                    const day = dateSelected[2];
                    var date = new Date(year, month-1, day);

                    const display_date_selected = days[date.getDay()] + ", " + months[date.getMonth()] + " " + day + ", " + year;
                    const display_date_selected_short = months_short[date.getMonth()] + " " + day + ", " + year;

                    $('#modal-day-details').modal('show');
                    $('.calendar-date-selected').text(display_date_selected);
                    $('.calendar-date-selected-short').text(display_date_selected_short);

                    $(".block-room-start-date, .discount-start-date").val(info.dateStr);
                    $(".block-room-end-date, .discount-end-date").val(info.dateStr);
                },


                select: function(info) {
                    // console.log("multi info", info);

                    const dateSelected1 = info.startStr.split("-");
                    const year1 = dateSelected1[0];
                    const month1 = dateSelected1[1];
                    const day1 = dateSelected1[2];
                    var date1 = new Date(year1, month1-1, day1);

                    const dateSelected2 = info.endStr.split("-");
                    const year2 = dateSelected2[0];
                    const month2 = dateSelected2[1];
                    const day2 = dateSelected2[2];
                    var date2 = new Date(year2, month2-1, day2);
                    date2.setDate(date2.getDate()-1);
                    const endDateStr = date2.getFullYear() + "-" + padNumberWithZero(date2.getMonth() + 1) + "-" + padNumberWithZero(date2.getDate());

                    const display_date_selected = days[date1.getDay()] + ", " + months[date1.getMonth()] + " " + day1 + ", " + year1 + "   ---   " + days[date2.getDay()] + ", " + months[date2.getMonth()] + " " + padNumberWithZero(date2.getDate()) + ", " + date2.getFullYear();
                    const display_date_selected_short = months_short[date1.getMonth()] + " " + day1 + ", " + year1 + "   ---   " + months_short[date2.getMonth()] + " " + padNumberWithZero(date2.getDate()) + ", " + date2.getFullYear();

                    $('#modal-day-details').modal('show');
                    $('.calendar-date-selected').text(display_date_selected);
                    $('.calendar-date-selected-short').text(display_date_selected_short);

                    $(".block-room-start-date, .discount-start-date").val(info.startStr);
                    $(".block-room-end-date, .discount-end-date").val(endDateStr);
                },
            });
            calendar.render();
        });



        $(document).ready(function(){
            $(".open-modal-price-settings").click(function(){
                $('#modal-price-settings').modal('show');
            });
            $(".open-modal-price-discount").click(function(){
                $('#modal-price-discount').modal('show');
            });
            $(".open-modal-block-room").click(function(){
                $('#modal-block-room').modal('show');
            });

            $(".close-modal-day-details").click(function(){
                $('#modal-day-details').modal('hide');
            });

            $(".close-modal-price-settings").click(function(){
                $('#modal-price-settings').modal('hide');
            });

            $(".close-modal-price-discount").click(function(){
                $('#modal-price-discount').modal('hide');
            });

            $(".close-modal-block-room").click(function(){
                $('#modal-block-room').modal('hide');
            });



            calculate_price("#modal-price-discount");
            $(".property-price-calendar, .property-price-discount-calendar").keyup(function(){
                calculate_price("#modal-price-discount");
            })


            function calculate_price(ele){
                // console.log("aa");
                // console.log($(element + ".property-price").val());
                // console.log($(element + ".property-price").val().trim());

                const element = ele + " ";
                let property_price = $(element + ".property-price").val().trim();
                const discount_status = $(element + "input[name='rate_toggle']:checked").val();
                if (discount_status === "on"){
                    property_price = $(element + ".property-price-discount").val().trim();
                }
                if (property_price !== ""){
                    const property_price_earnings = (65 / 100) * property_price;
                    const property_price_commission = (15 / 100) * property_price;
                    const property_price_vat = (20 / 100) * property_price;
                    $(element + ".property-price-display").text("£" + parseInt(property_price).toFixed(2));
                    $(element + ".property-price-earnings").text("£" + property_price_earnings.toFixed(2));
                    $(element + ".property-price-commission").text("£" + property_price_commission.toFixed(2));
                    $(element + ".property-price-vat").text("£" + property_price_vat.toFixed(2));
                }
                else{
                    $(element + ".property-price-display").text("£0");
                    $(element + ".property-price-earnings").text("£0");
                    $(element + ".property-price-commission").text("£0");
                    $(element + ".property-price-vat").text("£0");
                }
            }


            process_discount_display();
            $(".discount-toggle").change(function(){
                process_discount_display();
                calculate_price();
            })


            // process_price_days_selection_all();
            // $(".days-checkbox-all").change(function(){
            //     process_price_days_selection_all();
            // })
            // $(".days-checkbox-each").change(function(){
            //     process_price_days_selection_each();
            // })


            // function process_price_days_selection_all(){
            //     const select_all = $(".days-checkbox-all").is(":checked");
            //     if (select_all === true){
            //         $(".days-checkbox-each").each(function(){
            //             $(this).prop("checked", true);
            //         })
            //     }
            //     else{
            //         $(".days-checkbox-each").each(function(){
            //             $(this).prop("checked", false);
            //         })
            //     }
            // }


            // function process_price_days_selection_each(){
            //     let activate_select_all = true;
            //     $(".days-checkbox-each").each(function(){
            //         if (!$(this).is(":checked")){
            //             activate_select_all = false;
            //         }
            //     })
            //     if (activate_select_all === true){
            //         $(".days-checkbox-all").prop("checked", true);
            //     }
            //     else{
            //         $(".days-checkbox-all").prop("checked", false);
            //     }
            // }


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

        });


        function copySyncUrl() {
            // Get the text field
            var copyText = document.getElementById("copy-input");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            try {
                // Copy the text inside the text field
                navigator.clipboard.writeText(copyText.value);
            }
            catch (err) {
               console.error('Failed to copy: ', err);
            }

            // Alert the copied text
            alert("Copied: " + copyText.value);
        }


        process_price_days_selection_all('#modal-block-room');
        process_price_days_selection_all('#modal-block-room-general');


        function process_price_days_selection_all(element){
            const select_all = $(element + " .days-checkbox-all").is(":checked");
            if (select_all === true){
                $(element + " .days-checkbox-each").each(function(){
                    $(this).prop("checked", true);
                })
            }
            else{
                $(element + " .days-checkbox-each").each(function(){
                    $(this).prop("checked", false);
                })
            }
        }


        function process_price_days_selection_each(element){
            let activate_select_all = true;
            $(element + " .days-checkbox-each").each(function(){
                if (!$(this).is(":checked")){
                    activate_select_all = false;
                }
            })
            if (activate_select_all === true){
                $(element + " .days-checkbox-all").prop("checked", true);
            }
            else{
                $(element + " .days-checkbox-all").prop("checked", false);
            }
        }


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
    <div class="container">



        {{-- days selection modal --}}
        <div id="modal-day-details" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-text">
                            Configure day
                        </div>
                        <button type="button" class="close-modal-day-details modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        You selected: <span class="font-bold calendar-date-selected">DateSelected</span>

                        <div class="mt-4 grid grid-cols-2 gap-6 text-center">
                            <div class="p-4 border border-b-4 border-b-[#ffa200] rounded overflow-hidden">
                                <div class="text-gray-400">
                                    Price per night
                                </div>
                                <div class="mt-1 font-bold text-3xl">
                                    <span>$ 20</span>
                                    {{-- <span>Mixed</span> --}}
                                </div>
                                <div class="hidden text-gray-400">
                                    Discount is not active
                                </div>
                            </div>
                            <div class="p-4 border border-b-4 border-b-[#ffa200] rounded overflow-hidden">
                                <div class="text-gray-400">
                                    Availability
                                </div>
                                <div class="mt-1 font-bold text-3xl">
                                    <span class="text-[#0C0]">Open</span>
                                    {{-- <span class="text-[#F00]">Blocked</span> --}}
                                    {{-- <span>Mixed</span> --}}
                                </div>
                                <div class="hidden text-gray-400">
                                    This property is open to bookings
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 space-y-4">
                            <div class="open-modal-price-discount px-4 py-2 flex items-center justify-between border hover:bg-gray-200 rounded cursor-pointer">
                                <div>
                                    Configure price settings for <span class="font-bold calendar-date-selected-short">DateSelected</span>
                                </div>
                                <div>
                                    <span class="fa fa-angle-right text-2xl"></span>
                                </div>
                            </div>
                            <div class="open-modal-block-room px-4 py-2 flex items-center justify-between border hover:bg-gray-200 rounded cursor-pointer">
                                <div>
                                    Configure availability for <span class="font-bold calendar-date-selected-short">DateSelected</span>
                                </div>
                                <div>
                                    <span class="fa fa-angle-right text-2xl"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        {{-- discount modal --}}
        <div id="modal-price-discount" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <form method="post" action="{{route('partner.property.edit.price-setting.submit')}}" class="modal-full max-w-3xl">
                <input type="hidden" name="property_id" value="{{$property->id}}">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-header-text">
                            Configure price rate settings
                        </div>
                        <button type="button" class="close-modal-price-discount modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="text-center p-3 mb-8 border-t shadow-xl rounded-sm">
                            Date: <span class="font-bold calendar-date-selected-short">DateSelected</span>
                        </div>

                        <div>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="radio" name="rate_toggle" value="on" required class="form-input-checkbox discount-toggle">
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
                                            <input type="radio" name="rate_toggle" value="off" required class="form-input-checkbox discount-toggle">
                                        </div>
                                        <div class="my-auto">
                                            Don't activate discount
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>


                        <hr class="mt-8 border-tdhh-brown" />
                        <div class="text-center discount-status">
                            <div class="px-4 py-2 bg-green-300 discount-status-yes hidden">
                                Discount is active
                            </div>
                            <div class="px-4 py-2 bg-red-200 discount-status-no hidden">
                                Discount is NOT active
                            </div>
                        </div>
                        <div class="mb-8"></div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <div class="font-bold text-tdhh-orange">
                                    General price: {{$property->price}}
                                </div>
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
                                        {{-- this is just for frontend calculation purposes only, the "old_price" will not be submitted to BE --}}
                                        <input type="hidden" name="old_price" value="{{$property->price}}" class="property-price property-price-calendar">
                                        <input type="number" name="rate_price" class="form-input w-full property-price-discount-calendar" id="ratePrice" style="padding-left: 32px !important;">
                                    </label>
                                </div>

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
                            <div>
                                <div class="flex justify-between space-x-6">
                                    <div class="w-1/2 form-group">
                                        <label>
                                            Starting date:
                                        </label>
                                        <input type="date" value="" name="rate_start_date" class="discount-start-date form-input">
                                    </div>
                                    <div class="w-1/2 form-group">
                                        <label>
                                            Ending date:
                                        </label>
                                        <input type="date" value="" name="rate_end_date" class="discount-end-date form-input">
                                    </div>
                                </div>

                                <div>
                                    Days when discount should be active:
                                    <span class="form-input-required">*</span>
                                </div>
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div class="flex flex-col space-y-2">
                                        <label class="flex space-x-3 cursor-pointer">
                                            <input type="checkbox" class="days-checkbox-all form-input-checkbox">
                                            <div class="-mt-1">
                                                Select All
                                            </div>
                                        </label>
                                        @foreach (["Sunday","Monday","Tuesday"] as $key => $day)
                                            <label class="flex space-x-3 cursor-pointer">
                                                <input type="checkbox" name="affected_days[]" value="{{$key}}" class="days-checkbox-each form-input-checkbox">
                                                <div class="-mt-1">
                                                    {{$day}}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        @foreach (["Wednesday","Thursday","Friday","Saturday"] as $key => $day)
                                            <label class="flex space-x-3 cursor-pointer">
                                                <input type="checkbox" name="affected_days[]" value="{{$key + 3}}" class="days-checkbox-each form-input-checkbox">
                                                <div class="-mt-1">
                                                    {{$day}}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="close-modal-price-discount btn btn-transparent-black">Close</button>
                        <button type="submit" class="btn btn-tdhh-orange">
                            Continue
                        </button>
                    </div>
                </div>
            </form>
        </div>



        {{-- quick price settings => the one from list/table page --}}
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
                                        Price per night (inc. tax):
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <label class="relative block">
                                        <div class="pointer-events-none w-8 h-full absolute flex">
                                            <span class="m-auto">
                                                £
                                            </span>
                                        </div>
                                        <input type="number" name="price" id="currentPrice" class="form-input w-full property-price property-price-general" style="padding-left: 32px !important;">
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
                                                                <input type="number" name="rate_price" class="form-input w-full property-price-discount property-price-discount-general" id="ratePrice" style="padding-left: 32px !important;">
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



        {{-- room blocking => the one for this calendar date selections --}}
        <div id="modal-block-room" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-lg">
                <form class="modal-content" method="POST" action="{{route('partner.property.block-room.submit')}}">
                    @csrf
                    <input type="hidden" name="property_id" value="{{$property->id}}">
                    <div class="modal-header">
                        <div class="modal-header-text">
                            Block room
                        </div>
                        <button type="button" class="close-modal-block-room modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="modal-body mx-auto">

                        <div class="text-center p-3 mb-8 border-t shadow-xl rounded-sm">
                            Date: <span class="font-bold calendar-date-selected-short">DateSelected</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div class="form-group">
                                <label>
                                    Date from:
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="date" name="date_from" class="block-room-start-date form-input" />
                            </div>
                            <div class="form-group">
                                <label>
                                    Date to:
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="date" name="date_to" class="block-room-end-date form-input" />
                            </div>
                        </div>


                        <div class="mt-6">
                            <div>
                                Days when discount should be active:
                                <span class="form-input-required">*</span>
                            </div>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div class="flex flex-col space-y-2">
                                    <label class="flex space-x-3 cursor-pointer">
                                        <input type="checkbox" class="days-checkbox-all form-input-checkbox" onchange="process_price_days_selection_all('#modal-block-room')">
                                        <div class="-mt-1">
                                            Select All
                                        </div>
                                    </label>
                                    @foreach (["Sunday","Monday","Tuesday"] as $key => $day)
                                        <label class="flex space-x-3 cursor-pointer">
                                            <input type="checkbox" name="affected_days[]" value="{{$key}}" onchange="process_price_days_selection_each('#modal-block-room')" class="days-checkbox-each form-input-checkbox">
                                            <div class="-mt-1">
                                                {{$day}}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex flex-col space-y-2">
                                    @foreach (["Wednesday","Thursday","Friday","Saturday"] as $key => $day)
                                        <label class="flex space-x-3 cursor-pointer">
                                            <input type="checkbox" name="affected_days[]" value="{{$key + 3}}" onchange="process_price_days_selection_each('#modal-block-room')" class="days-checkbox-each form-input-checkbox">
                                            <div class="-mt-1">
                                                {{$day}}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="mt-6">
                            <div class="mb-2">
                                Action:
                            </div>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="radio" name="status" value="on" id="" required class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Block the selected period
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="radio" name="status" value="off" id="" required class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        Make the selected period available
                                    </div>
                                </div>
                            </label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="close-modal-block-room btn btn-transparent-black">Close</button>
                        <button type="submit" class="btn btn-tdhh-orange">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>



        {{-- block room => the one from list/table page --}}
        <div id="modal-block-room-general" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-lg">
                <form class="modal-content" method="POST" action="{{route('partner.property.block-room.submit')}}">
                    @csrf
                    <input type="hidden"  name="property_id" class="propertyId"/>
                    <div class="modal-header">
                        <div class="modal-header-text">
                            Block room
                        </div>
                        <button type="button" data-modal-toggle="modal-block-room-general" class="modal-header-close">
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
                                <label class="flex space-x-3 cursor-pointer">
                                    <input type="checkbox" class="days-checkbox-all form-input-checkbox" onchange="process_price_days_selection_all('#modal-block-room-general')">
                                    <div class="-mt-1">
                                        Select All
                                    </div>
                                </label>
                                @foreach (["Sunday","Monday","Tuesday"] as $key => $day)
                                    <label class="flex space-x-3 cursor-pointer">
                                        <input type="checkbox" name="block_days[]" value="{{$key}}" onchange="process_price_days_selection_each('#modal-block-room-general')" class="days-checkbox-each form-input-checkbox">
                                        <div class="-mt-1">
                                            {{$day}}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div class="flex flex-col space-y-2">
                                @foreach (["Wednesday","Thursday","Friday","Saturday"] as $key => $day)
                                    <label class="flex space-x-3 cursor-pointer">
                                        <input type="checkbox" name="block_days[]" value="{{$key + 3}}" onchange="process_price_days_selection_each('#modal-block-room-general')" class="days-checkbox-each form-input-checkbox">
                                        <div class="-mt-1">
                                            {{$day}}
                                        </div>
                                    </label>
                                @endforeach
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
                        <button type="button" data-modal-toggle="modal-block-room-general" class="btn btn-transparent-black">Close</button>
                        <button type="submit" class="btn btn-tdhh-orange">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>



        {{-- sync calendar via url --}}
        <div id="modal-sync-calendar" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-xl">
                <form class="modal-content"method="post" action="{{route('partner.property.integrate-sync-calendar', $property->id)}}">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-header-text">
                            Sync calendar
                        </div>
                        <button type="button" data-modal-toggle="modal-sync-calendar" class="modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group">
                            <label>
                                Your property Sync URL:
                                <span class="form-input-required">*</span>
                            </label>

                            <div class="relative">
                                <input type="text" value="https://tdhh.com/calendar/sync/property/{{$property->id}}" class="form-input" id="copy-input" style="padding-right: 100px;" />
                                <button type="button" class="btn btn-tdhh-orange h-full absolute top-0 right-0" onclick="copySyncUrl()">
                                    Copy
                                </button>
                            </div>

                            <div class="text-fade text-sm">
                                Export your property reservations to other booking platforms.
                            </div>
                        </div>

                        <div class="pt-8 form-group">
                            <label>
                                Import reservations via calendar URL:
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="text" name="calendar_name" class="form-input" />
                            <div class="text-gray-500 text-sm">
                                Calender name
                            </div>
                            <input type="url" name="calendar_url" class="form-input" />
                            <div class="text-gray-500 text-sm">
                                Calender URL
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" data-modal-toggle="modal-sync-calendar" class="btn btn-transparent-black">Close</button>
                        <button type="submit" class="btn btn-tdhh-orange">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>





        <div class="page-title-account">
            <div>
                Sync Calendar
                <a href="/partners/property/1234567890" target="_blank" class="hover:underline font-montserrat text-sm text-tdhh-orange">
                    #1234567890
                </a>
            </div>
            <div class="pull-right flex space-x-4">
                <a href="/partners/dashboard/property" type="button" class="btn btn-transparent-black btn-sm relative top-2">
                    <span class="fa fa-angle-left mr-2"></span>
                    Go back
                </a>
            </div>
        </div>


        <div class="mt-4">
            <div class="min-h-[400px] box">


                <div class="flex flex-wrap">
                    <a href="/property/{{$property->id}}" target="_blank" class="btn btn-transparent-black btn-md mr-4 mb-4">
                        <span class="fa fa-external-link mr-2"></span>
                        View property
                    </a>

                    <a href="{{route('partner.property.edit.generalInfo', $property->id)}}" class="btn btn-transparent-black btn-md mr-4 mb-4">
                        <span class="fa fa-edit mr-2"></span>
                        Edit
                    </a>

                    <button type="button" data-modal-toggle="modal-price-settings" class="btn btn-transparent-black btn-md mr-4 mb-4" onClick="priceSetting({{ $property }})">
                        <span class="fa fa-wallet mr-2"></span>
                        Price settings
                    </button>

                    <button type="button" data-modal-toggle="modal-block-room-general" class="btn btn-transparent-black btn-md mr-4 mb-4" onClick="blockRoom({{ $property }})">
                        <span class="fa fa-ban mr-2"></span>
                        Block room
                    </button>

                    <a href="/partners/property/{{$property->id}}/reviews" class="btn btn-transparent-black btn-md mr-4 mb-4">
                        <span class="fa fa-star mr-2"></span>
                        Reviews
                    </a>

                    <button type="button" data-modal-toggle="modal-sync-calendar" type="button" class="btn btn-transparent-black btn-md mr-4 mb-4">
                        <span class="fa fa-calendar mr-2"></span>
                        Import / Export Calendar
                    </button>

                    <a href="{{route('partner.property.download-sync-calendar', $property->id)}}" class="btn btn-transparent-black btn-md mr-4 mb-4">
                        <span class="fa fa-download mr-2"></span>
                        Download Calendar
                    </a>

                    <form method="POST" action="">
                        @csrf
                        {{--
                        <button type="submit" class="btn btn-transparent-black btn-md mr-4 mb-4"
                            onclick="return confirm('Are you sure you want to activate this property? \nOnce done, customers will be able to book this property. You can de-activate the property anytime.');">
                            <span class="fa fa-check mr-2"></span>
                            Enable
                        </button>
                        --}}

                        <button type="submit" class="btn btn-transparent-black btn-md mr-4 mb-4"
                            onclick="return confirm('Are you sure you want to disable this property? \nOnce done, customers will not be able to book this property. You can re-activate the property anytime.');">
                            <span class="fa fa-times mr-2"></span>
                            Disable
                        </button>
                    </form>
                </div>


                <div class="mt-4 text-center bg-tdhh-orange p-2">
                    Please click a day to configure.
                    <br>
                    You can click and drag multiple days to make a selection.
                </div>

                <div class="py-2 my-6 border-t border-b flex items-center justify-center space-x-10">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-red-200"></div>
                        <div>
                            Blocked days
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-[#f3f3f3]"></div>
                        <div>
                            Past days
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-[#fffae3]"></div>
                        <div>
                            Current day
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-[#3c8ad5]"></div>
                        <div>
                            Reservations
                        </div>
                    </div>
                </div>

                <div id="calendar"></div>

            </div>
        </div>

    </div>
@endsection

