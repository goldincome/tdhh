@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Welcome
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />
    {{-- <link href="{{ asset('css/daterangepicker/daterangepicker.css') }}" rel="stylesheet" /> --}}
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    {{-- <script src="{{ asset('js/daterangepicker/daterangepicker.js') }}"></script> --}}
    <script>
        $(function(){

            // search box: dates selection

            var dateFormat = "mm/dd/yy",
            from = $(".search-checkin-date-from").datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2
                }).on( "change", function() {
                    to.datepicker( "option", "minDate", getDate( this, "check-in" ) );
                }),
            to = $( ".search-checkin-date-to" ).datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2
                }).on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this, "check-out" ) );
                });

            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;

                return [month, day, year].join('/');
            }

            function getDate( element, type ) {
                var date;
                try {
                    date = $.datepicker.parseDate( dateFormat, element.value );
                    date = formatDate(date);
                } catch( error ) {
                    date = null;
                }
                if (type === "check-in"){
                    $(".search-response-checkin").val(date);
                }
                else if (type === "check-out"){
                    $(".search-response-checkout").val(date);
                }
                return date;
            }



            // search box: guests selection

            function pluralize(count, singular, plural){
                if (parseInt(count) === 1){
                    return singular;
                }
                else{
                    return plural;
                }
            }

            $(".quantity-box > div:nth-child(1)").click(function(){
                const input_box = $(this).next("div").children("input");
                if (input_box.val() > 0)
                input_box.val(parseInt(input_box.val()) - 1);
                guests_qty();
            });


            $(".quantity-box > div:nth-child(3)").click(function(){
                const input_box = $(this).prev("div").children("input");
                input_box.val(parseInt(input_box.val()) + 1);
                guests_qty();
            });

            $(".adult-qty-input, .children-qty-input, .pets-qty-input").change(function(){
                guests_qty();
            });

            function guests_qty(){
                const adult_qty_input = $(".adult-qty-input").val();
                const children_qty_input = $(".children-qty-input").val();
                const pets_qty_input = $(".pets-qty-input").val();

                let display_count = adult_qty_input + pluralize(adult_qty_input, " adult", " adults") + ", ";
                display_count += children_qty_input + pluralize(children_qty_input, " child", " children") + ", ";
                display_count += pets_qty_input + pluralize(pets_qty_input, " pet", " pets");

                $(".home-reserve-form-adults").val(adult_qty_input);
                $(".home-reserve-form-children").val(children_qty_input);
                $(".home-reserve-form-pets").val(pets_qty_input);
                $(".home-reserve-guest-display").text(display_count);
            }
        });
    </script>
@endsection


@section('content')
    <div>


        <div id="modal-guests" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-2xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-header-text">
                            Guests
                        </div>
                        <button type="button" data-modal-toggle="modal-guests" class="modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div>
                                How many adults:
                            </div>
                            <div class="mt-2 quantity-box">
                                <div>
                                    <span class="fa fa-minus"></span>
                                </div>
                                <div>
                                    <input name="" type="text" class="form-input adult-qty-input" value="{{intval(old('num_of_adult'))}}" />
                                </div>
                                <div>
                                    <span class="fa fa-plus"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div>
                                How many children:
                            </div>
                            <div class="mt-2 quantity-box">
                                <div>
                                    <span class="fa fa-minus"></span>
                                </div>
                                <div>
                                    <input type="text" name="" class="form-input children-qty-input" value="{{intval(old('num_of_children'))}}" />
                                </div>
                                <div>
                                    <span class="fa fa-plus"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div>
                                How many pets:
                            </div>
                            <div class="mt-2 quantity-box">
                                <div>
                                    <span class="fa fa-minus"></span>
                                </div>
                                <div>
                                    <input type="text" name="" class="form-input pets-qty-input" value="{{intval(old('num_of_pet'))}}" />
                                </div>
                                <div>
                                    <span class="fa fa-plus"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" data-modal-toggle="modal-guests" class="btn btn-transparent-black">Close</button> --}}
                        <button type="button" data-modal-toggle="modal-guests" class="btn btn-tdhh-orange">Submit</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="py-4 bg-tdhh-orange relative">
            <div class="relative inset-0 z-20 h-64 md:h-80"></div>
            <div class="opacity-50 absolute inset-0 z-10 w-full h-full overflow-hidden">
                <img src={{asset('img/bgs/home-bg.jpg')}} alt="tdhh" class="object-cover" />
            </div>
        </div>



        <div class="relative z-30">
            <div class="container">
                <form action="{{route('front.property.search')}}" method="get" class="max-w-4xl -mt-24 mx-auto p-8 sm:p-10 xl:p-14 pb-4 sm:pb-6 xl:pb-8 bg-white border-b-8 border-tdhh-orange rounded-xl shadow-xl">
                    @csrf
                    <div class="flex">

                        <div class="w-[25%] pr-4">
                            <div class="form-group">
                                <label class="font-bold ellipsis">
                                    Location
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="location" placeholder="Eg: Birmingham" class="form-input" />
                            </div>
                        </div>

                        <div class="w-[30%] pr-4">
                            <div class="form-group">
                                <label class="font-bold ellipsis">
                                    Guests
                                    <span class="form-input-required">*</span>
                                </label>
                                <div>
                                    <div class="form-input cursor-pointer" data-modal-toggle="modal-guests" style="padding: 0px 12px;">
                                        <div class="flex justify-between items-center">
                                            <span class="home-reserve-guest-display mt-2 ellipsis">Select guests</span>
                                            <span class="fa fa-angle-down mt-2"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-[30%] pr-4">
                            <div class="form-group">
                                <label class="font-bold ellipsis">
                                    <span class="hidden lg:inline">Check-in / Check-out</span>
                                    <span class="inline lg:hidden">Check-in & out</span>
                                    <span class="form-input-required">*</span>
                                </label>
                                <div class="flex justify-between">
                                    <input type="text" name="check_in_date" value="---" class="search-checkin-date-from form-input" />
                                    <input type="text" name="check_out_date" value="---" class="search-checkin-date-to form-input" />
                                </div>
                            </div>
                        </div>

                        <div class="w-[15%]">
                            <div class="form-group">
                                <label class="font-bold">&nbsp;</label>
                                <button type="submit" class="btn btn-tdhh-orange btn-block">
                                    Search
                                </button>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="num_of_adult" class="home-reserve-form-adults" value="0">
                    <input type="hidden" name="num_of_children" class="home-reserve-form-children" value="0">
                    <input type="hidden" name="num_of_pet" class="home-reserve-form-pets" value="0">
                    <input type="hidden" name="check_in_date" class="search-response-checkin" />
                    <input type="hidden" name="check_out_date" class="search-response-checkout" />

                </form>
            </div>
        </div>




        {{-- 
        <div class="mt-14 container">
            @php
                $altCoverImages = ['img/bgs/manchester.jpg','img/bgs/birmingham.jpg', 'img/bgs/glasglow.jpg', 'img/bgs/readings.jpg', 'img/bgs/edinburgh.jpg', 'img/bgs/leeds.jpg', 'img/bgs/belfast.jpg'];
            @endphp
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-1">
                @foreach($fPCities as $city)
                @php
                    $random = array_rand($altCoverImages);
                    $v = $altCoverImages[$random];
                @endphp
                    <a href="{{route('front.property.location.list',$city->state->name)}}" class="home-category group">
                        <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">{{$city->state->name}}</div>
                        <div class="absolute z-0 inset-0">
                            <img src={{asset($city->coverImage ? $city->coverImage : $v)}} alt="London" class="object-cover m-auto w-full h-full">
                        </div>
                    </a>
                @endforeach
                 --}}
                {{-- <a href="/property?location=manchester" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Manchester</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/manchester.jpg')}} alt="Manchester" class="object-cover m-auto w-full h-full">
                    </div>
                </a>
                <a href="/property?location=birmingham" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Birmingham</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/birmingham.jpg')}} alt="Birmingham" class="object-cover m-auto w-full h-full">
                    </div>
                </a>
                <a href="/property?location=glasglow" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Glasglow</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/glasglow.jpg')}} alt="Glasglow" class="object-cover m-auto w-full h-full">
                    </div>
                </a>
                <a href="/property?location=readings" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Readings</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/readings.jpg')}} alt="Readings" class="object-cover m-auto w-full h-full">
                    </div>
                </a>
                <a href="/property?location=edinburgh" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Edinburgh</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/edinburgh.jpg')}} alt="Edinburgh" class="object-cover m-auto w-full h-full">
                    </div>
                </a>
                <a href="/property?location=leeds" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Leeds</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/leeds.jpg')}} alt="Leeds" class="object-cover m-auto w-full h-full">
                    </div>
                </a>
                <a href="/property?location=belfast" class="home-category group">
                    <div class="group-hover:bg-tdhh-orange" style="bottom: 0;">Belfast</div>
                    <div class="absolute z-0 inset-0">
                        <img src={{asset('img/bgs/belfast.jpg')}} alt="Belfast" class="object-cover m-auto w-full h-full">
                    </div>
                </a> --}}
            {{-- </div> --}}
        {{-- </div> --}}



        <div class="mt-14 container">
            <div class="flex justify-between">
                <div class="page-title">
                    Featured Properties
                </div>
                <div>
                    <a href="/property?type=featured" class="btn relative top-2">
                        View all
                        <span class="fa fa-external-link ml-2"></span>
                    </a>
                </div>
            </div>

            {{-- max of 8 --}}
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">

                @foreach ($properties as $property)
                    @include('common.home-grid')

                @endforeach
                {{-- @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid') --}}
            </div>
        </div>



        <div class="mt-20 container">
            <div class="md:flex">
                <div class="md:w-2/5">
                    <div class="grid grid-cols-2 gap-10">
                        <div>
                            <div class="w-20 h-20">
                                <img src={{asset('img/icons/fun.png')}} alt="tdhh" class="object-contain" />
                            </div>
                            <div class="mt-2 text-2xl text-tdhh-orange font-lobster font-bold">
                                Have Fun
                            </div>
                            <div class="mt-1">
                                Enjoy yourself in a safe relaxing environment
                            </div>
                        </div>
                        <div>
                            <div class="w-20 h-20">
                                <img src={{asset('img/icons/meditate.png')}} alt="tdhh" class="object-contain" />
                            </div>
                            <div class="mt-2 text-2xl text-tdhh-orange font-lobster font-bold">
                                Meditate
                            </div>
                            <div class="mt-1">
                                Get the perfect place that aligns with your energy
                            </div>
                        </div>
                        <div>
                            <div class="w-20 h-20">
                                <img src={{asset('img/icons/focus.png')}} alt="tdhh" class="object-contain" />
                            </div>
                            <div class="mt-2 text-2xl text-tdhh-orange font-lobster font-bold">
                                Get Focus
                            </div>
                            <div class="mt-1">
                                Have time to focus on the things that really matter
                            </div>
                        </div>
                        <div>
                            <div class="w-20 h-20">
                                <img src={{asset('img/icons/energize.png')}} alt="tdhh" class="object-contain" />
                            </div>
                            <div class="mt-2 text-2xl text-tdhh-orange font-lobster font-bold">
                                Energize
                            </div>
                            <div class="mt-1">
                                Replenish your spirit-energy to get back to life happier
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/5 hidden md:block">
                    <div class="w-4 h-full mx-auto bg-tdhh-orange"></div>
                </div>
                <div class="md:w-2/5 flex">
                    <div class="mt-14 md:mt-0 my-auto">
                        <div class="font-lobster text-5xl">
                            Need a quick place
                            <br>
                            to stay, unwind, relax
                        </div>
                        <div class="mt-7 leading-8">
                            TDHolidayHomes offers its esteemed users, the opportunity to choose awesome places where they would love to spend their vacation. Our users can browse through all places on the platform anytime, anywhere in the world, and select any vacation spot they desire.
                        </div>
                        <div class="mt-5">
                            <a href="/property" class="btn btn-tdhh-orange">
                                Explore
                                <span class="fa fa-external-link-alt ml-3"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="mt-14 sm:mt-20 lg:mt-28 relative bg-tdhh-orange">
            <div class="relative z-10 container">
                <div class="py-32 sm:py-36 px-6 md:px-12 max-w-3xl flex items-center text-center flex-col mx-auto text-black">
                    <div class="text-3xl md:text-4xl lg:text-5xl font-semibold font-lobster">
                        Have a vacant apartment?
                        <br>
                        Earn off it on TD Holiday Homes
                    </div>
                    <div class="mt-5">
                        You can earn off your vacant homes or rooms with ease. Register as a merchant within few steps, list your properties, and start receiving bookings
                    </div>
                    <div class="flex space-x-4 mt-5">
                        <a href="/account/property/add" class="btn btn-lg btn-black text-white">
                            List your property
                        </a>
                    </div>
                </div>
            </div>
            <div class="bg-img-under bg-wide opacity-10"></div>
        </div>




        <div class="mt-14 container">
            <div class="flex justify-between">
                <div class="page-title">
                    Properties under £100
                </div>
                <div>
                    <a href="/property?type=under-100" class="btn relative top-2">
                        View all
                        <span class="fa fa-external-link ml-2"></span>
                    </a>
                </div>
            </div>

            {{-- max of 8 --}}
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
                @include('common.home-grid')
            </div>
        </div>



        <div class="mt-14 sm:mt-20 relative bg-tdhh-orange">
            <div class="relative z-10 container">
                <div class="py-32 sm:py-36 px-6 md:px-12 max-w-3xl flex items-center text-center flex-col mx-auto text-black">
                    <div class="text-3xl md:text-4xl lg:text-5xl font-semibold font-lobster">
                        Have an enquiry?
                    </div>
                    <div class="mt-5 text-lg">
                        Do you have any questions or suggestions? please click the button below to contact us.
                        A response will be sent to you as soon as possible.
                    </div>
                    <div class="flex space-x-4 mt-5">
                        <a href="/contact" class="btn btn-lg btn-black text-white">
                            Send us a message
                        </a>
                    </div>
                </div>
            </div>
            <div class="bg-img-under bg-contact opacity-20"></div>
        </div>



    </div>
@endsection
