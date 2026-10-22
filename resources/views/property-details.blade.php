@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    # {{$property->reference}} - {{$property->name}}
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){

            function validateCheckIn(date_checkin, response){
                const today = new Date();
                today.setHours(0,0,0,0);
                let errors = [];

                if (date_checkin === ""){
                    errors.push("Please enter your check-in date");
                }
                if (new Date(date_checkin) <= today){
                    errors.push("Please select a check-in date after today's date");
                }

                if (errors.length > 0){
                    if (response !== "no-response"){
                        alert(errors[0]);
                    }
                    return false;
                }

                return true;
            }

            function validateCheckOut(date_checkin, date_checkout, response){
                let errors = [];

                if (date_checkout === ""){
                    errors.push("Please enter your check-out date");
                }
                if (date_checkin >= date_checkout){
                    errors.push("Your check-out date must be after your check-in date");
                }

                if (errors.length > 0){
                    if (response !== "no-response"){
                        alert(errors[0]);
                    }
                    return false;
                }

                return true;
            }

            function validateGuests(adults, children, pets){
                if (parseInt(adults) === 0){
                    alert("Please select the number of guests");
                    return false;
                }
                return true;
            }

            $(".home-reserve-form").submit(function(e){
                let errors = 0;

                const date_checkin = $(".home-reserve-form-checkin").val();
                const date_checkout = $(".home-reserve-form-checkout").val();
                const guests_adults = $(".home-reserve-form-adults").val();
                const guests_children = $(".home-reserve-form-children").val();
                const guests_pets = $(".home-reserve-form-pets").val();

                if (validateCheckIn(date_checkin) === false){
                    errors++;
                }
                if (validateCheckOut(date_checkin, date_checkout) === false){
                    errors++;
                }
                if (validateGuests(guests_adults, guests_children, guests_pets) === false){
                    errors++;
                }
                if (errors === 0){
                    // alert("all good");
                }
                else{
                    e.preventDefault();
                }
            })


            calculate_price();
            $(".home-reserve-form-checkin").change(function(){
                const user_val = $(this).val();
                $(".home-reserve-form-checkin").val(user_val);
                calculate_price();
            })
            $(".home-reserve-form-checkout").change(function(){
                const user_val = $(this).val();
                $(".home-reserve-form-checkout").val(user_val);
                calculate_price();
            })


            function pluralize(count, singular, plural){
                if (parseInt(count) === 1){
                    return singular;
                }
                else{
                    return plural;
                }
            }


            function calculate_price(){
                let currency = "{{ currency_symbol()}}";
                let nights = 1;
                const date_checkin = $(".home-reserve-form-checkin").val();
                const date_checkout = $(".home-reserve-form-checkout").val();
                const date_difference = (new Date(date_checkout) - new Date(date_checkin))/(24*3600*1000);
                const price = $(".home-reserve-form-price").val();
                const serviceFee = $(".home-reserve-form-service-fee").val();
                const cleaningFeeStatic = $(".home-reserve-form-cleaning-fee").val();
                const cleaningType = $(".home-reserve-form-cleaning-type").val();
                const parkingFee = $(".home-reserve-form-parking-fee").val();

                // console.log("date_checkin", date_checkin);
                // console.log("date_checkout", date_checkout);
                // console.log("date_difference", date_difference);
                if (validateCheckIn(date_checkin, "no-response") === true && validateCheckOut(date_checkin, date_checkout, "no-response") === true){
                    nights = date_difference;
                }

                const cleaningFee = (cleaningType === "per_stay") ? cleaningFeeStatic : (cleaningFeeStatic * nights);
                const cleaningFeeText = (cleaningType === "per_stay") ? "Cleaning fee per stay" : ("Cleaning fee * " + nights + " " + pluralize(nights, "night", "nights"));
                const subtotal = parseFloat(price * nights) + parseFloat(serviceFee) + parseFloat(cleaningFee) + parseFloat(parkingFee);

                $(".form-display-nights").text(currency + price.toString() + " * " + nights + " " + pluralize(nights, "night", "nights"));
                $(".form-display-cleaning").text(currency + cleaningFee.toLocaleString());
                $(".form-display-cleaning-nights").text(cleaningFeeText);
                $(".form-display-price").text(currency + (price * nights).toLocaleString());
                $(".form-display-subtotal").text(currency + subtotal.toLocaleString());
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


            // guests_qty();
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
        })
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
                                    <input name="" type="text" class="form-input adult-qty-input" value="{{$data->num_of_adult ??intval(old('num_of_adult'))}}" />
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


        <div class="top-wide">
            <div>
                <div>
                   {{$property->name}}
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/property">Properties</a>
                    <span>/</span>
                    <a>This property</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12">

            <div class="lg:flex">

                <div class="lg:w-3/4">
                    <div class="lg:mr-10">

                        <div class="flex flex-col lg:flex-row space-x-0">
                            <div class="lg:order-2 w-full h-60 sm:h-72 lg:h-96 bg-black overflow-hidden">
                                <img src={{asset($property->cover_image->getUrl())}} alt="tdhh" class="object-cover" />
                            </div>

                            <div class="lg:order-1 mt-2 lg:mt-0 lg:pr-4 lg:w-48 lg:h-96 flex flex-row lg:flex-col space-x-2 lg:space-x-0 lg:space-y-4 overflow-scroll">
                                @foreach ($property->propertyImages as $image)
                                    <div class="h-16 lg:h-24 flex-shrink-0 bg-black overflow-hidden cursor-pointer group">
                                        <img src={{asset($image->getUrl())}} alt="tdhh" class="object-cover transform animate group-hover:scale-125" />
                                    </div>
                                @endforeach

                            </div>
                        </div>


                        <div class="py-8">
                            <div class="text-tdhh-orange">
                                #{{$property->reference}}
                            </div>
                            <div class="font-bold mt-1 text-xl lg:text-2xl">
                                {{$property->name}}
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div class="my-auto">
                                    <span class="text-tdhh-orange text-xl font-bold">
                                        {{currency_convert($property->todayPrice,$property->country->code,currency()->getUserCurrency())}}
                                    </span>
                                    <span class="text-xs">
                                        / night
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="w-10 h-10 flex hover:bg-gray-200 cursor-pointer">
                                        <span class="fa fa-heart text-xl m-auto"></span>
                                    </div>
                                    <div class="w-10 h-10 flex hover:bg-gray-200 cursor-pointer">
                                        <span class="fa fa-share-alt text-xl m-auto"></span>
                                    </div>
                                    <div class="w-10 h-10 flex hover:bg-gray-200 cursor-pointer">
                                        <span class="fa fa-link text-xl m-auto"></span>
                                    </div>
                                    <div class="w-10 h-10 flex hover:bg-gray-200 cursor-pointer">
                                        <span class="fa fa-flag text-xl m-auto"></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="-mt-2 block lg:hidden">
                            @include('common.home-reserve-property')
                            @include('common.home-contact-owner')
                        </div>


                        <div class="py-8 border-t border-gray-300">
                            <div class="flex space-x-6 font-bold">
                                <div>
                                    Check-in Date: {{date('l, d F, Y', strtotime($data->check_in_date))}}
                                
                                </div>
                            
                                <div>
                                    Check-out Date: {{date('l, d F, Y', strtotime($data->check_out_date))}}
                                </div>
                            </div>
                            <div class="flex space-x-6 font-bold">
                                <div>
                                    Check-in Time : From {{date('h:i A', strtotime($property->check_in_from))}}
                                </div>
                                <div>
                                    Check-out Time : Up till {{date('h:i A', strtotime($property->check_out_to))}}
                                </div>
                            </div>
                            <div class="mt-2 text-justify leading-8">
                                {{$property->description}}
                            </div>
                        </div>


                        <div class="py-8 border-t border-gray-300">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-6">
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
                                        {{$property->bathroom_qty}}  bathroom
                                    </div>
                                </div>
                                <div class="flex property-details-options">
                                    <div>
                                        <span class="fa fa-kitchen-set"></span>
                                    </div>
                                    <div>
                                        Complete kitchen
                                    </div>
                                </div>
                                @foreach ($property->characteristics as $characteristic)
                                    <div class="flex property-details-options">
                                        <div>
                                            <span class="fa fa-check-circle text-[#00CC00]"></span>
                                        </div>
                                        <div>
                                            {{$characteristic->characteristic->name}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="px-6 py-4 mt-8 bg-gray-100">
                            House Rules
                        </div>
                        

                        <div class="mt-8 grid grid-cols-2 gap-8">
                            @foreach ($property->houseRules as $houseRule)
                
                
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0">
                                        <span class="fa fa-times-circle mt-1 text-red-500"></span>
                                    </div>
                                    <div>
                                        <div>
                                            {{$houseRule->rule->name}}
                                        </div>
                                        <div class="font-bold">
                                            Not allowed
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>

                        <div class="px-6 py-4 mt-8 bg-gray-100">
                            Parking Details
                        </div>
                        <div class="mt-8 grid grid-cols-2 gap-8">
                            <div class="flex">
                                <div class="w-8 flex-shrink-0">
                                    <span class="fa fa-car mt-1"></span>
                                </div>
                                <div>
                                    <div>
                                        Parking Availability
                                    </div>
                                    <div class="font-bold">
                                        @if($property->parking_information->parking_is_available == 'yes')
                                            <span class="text-green-500">Yes, parking is available</span>
                                        @else
                                            <span class="text-red-500">No parking available</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($property->parking_information->parking_is_available == 'yes')
                            <div class="flex">
                                <div class="w-8 flex-shrink-0">
                                    <span class="fa fa-car mt-1"></span>
                                </div>
                                <div>
                                    <div>
                                        Parking Location
                                    </div>
                                    <div class="font-bold">
                                        {{$property->parking_information->parking_location}}
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="w-8 flex-shrink-0">
                                    <span class="fa fa-car mt-1"></span>
                                </div>
                                <div>
                                    <div>
                                        Parking Type
                                    </div>
                                    <div class="font-bold">
                                        <span class="capitalize">{{$property->parking_information->parking_type}}</span>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            @if(isset($property->parking_information->parking_charge) && $property->parking_information->parking_charge == 'yes')
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0">
                                        <span class="fa fa-car mt-1"></span>
                                    </div>
                                    <div>
                                        <div>
                                            Parking Fee Charges will apply
                                        </div>
                                        <div class="font-bold">
                                            {{-- {{currency_format($reservation->parking_information->parking_fee, $reservation->price_information->country_code ?? config('currency.default'))}} --}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div id="reviews"></div>
                        <div class="py-8 mt-10 border-t border-gray-300">
                            <div class="flex justify-between">
                                <div class="font-bold">
                                    Reviews
                                </div>
                                <div class="mt-0">
                                    <span class="text-tdhh-orange mr-1">
                                        <span class="fa fa-star"></span>
                                        4.8
                                    </span>
                                    <span>
                                        (20 reviews)
                                    </span>
                                </div>
                            </div>

                            <div class="mt-6 text-sm grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <div>
                                        Communication with owner <span class="font-bold">(4.0)</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: 80%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        Accuracy <span class="font-bold">(4.9)</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: 98%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        Cleanliness <span class="font-bold">(4.7)</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: 94%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        Location <span class="font-bold">(5.0)</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: 100%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        Security <span class="font-bold">(4.5)</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: 90%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        Value for Money <span class="font-bold">(4.6)</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: 92%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-between">
                                <div class="text-sm">
                                    144 Reviews
                                </div>
                                <div class="mt-0">
                                    <a href="/property/12345678/reviews" class="btn btn-transparent-black btn-sm relative -top-1">
                                        View all
                                    </a>
                                </div>
                            </div>

                            <div class="mt-4 text-sm flex flex-col space-y-0 border-t border-gray-300">
                                <div class="p-4 border border-t-0 border-gray-300">
                                    <div class="flex justify-between">
                                        <div class="flex space-x-2">
                                            <div class="w-10 h-10 flex bg-black rounded-full overflow-hidden">
                                                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
                                            </div>
                                            <div>
                                                <div>
                                                    Firstname Lastname
                                                </div>
                                                <div class="text-fade text-xs">
                                                    Sept 20, 2022
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>
                                                <span class="fa fa-star text-tdhh-orange"></span>
                                            </span>
                                            <span class="ml-1 text-tdhh-orange">
                                                4.0
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 leading-6">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ipsum massa id egestas nibh tortor tortor nisi. Sed morbi amet, massa a nunc porta tincidunt. Ac eu molestie nisi, feugiat ultrices. Amet, a eros pretium molestie aliquam.
                                    </div>
                                </div>
                                <div class="p-4 border border-t-0 border-gray-300">
                                    <div class="flex justify-between">
                                        <div class="flex space-x-2">
                                            <div class="w-10 h-10 flex bg-black rounded-full overflow-hidden">
                                                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
                                            </div>
                                            <div>
                                                <div>
                                                    Firstname Lastname
                                                </div>
                                                <div class="text-fade text-xs">
                                                    Sept 20, 2022
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>
                                                <span class="fa fa-star text-tdhh-orange"></span>
                                            </span>
                                            <span class="ml-1 text-tdhh-orange">
                                                4.0
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 leading-6">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ipsum massa id egestas nibh tortor tortor nisi. Sed morbi amet, massa a nunc porta tincidunt. Ac eu molestie nisi, feugiat ultrices. Amet, a eros pretium molestie aliquam.
                                    </div>
                                </div>
                                <div class="p-4 border border-t-0 border-gray-300">
                                    <div class="flex justify-between">
                                        <div class="flex space-x-2">
                                            <div class="w-10 h-10 flex bg-black rounded-full overflow-hidden">
                                                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
                                            </div>
                                            <div>
                                                <div>
                                                    Firstname Lastname
                                                </div>
                                                <div class="text-fade text-xs">
                                                    Sept 20, 2022
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>
                                                <span class="fa fa-star text-tdhh-orange"></span>
                                            </span>
                                            <span class="ml-1 text-tdhh-orange">
                                                4.0
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 leading-6">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ipsum massa id egestas nibh tortor tortor nisi. Sed morbi amet, massa a nunc porta tincidunt. Ac eu molestie nisi, feugiat ultrices. Amet, a eros pretium molestie aliquam.
                                    </div>
                                </div>
                                <div class="p-4 border border-t-0 border-gray-300">
                                    <div class="flex justify-between">
                                        <div class="flex space-x-2">
                                            <div class="w-10 h-10 flex bg-black rounded-full overflow-hidden">
                                                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
                                            </div>
                                            <div>
                                                <div>
                                                    Firstname Lastname
                                                </div>
                                                <div class="text-fade text-xs">
                                                    Sept 20, 2022
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>
                                                <span class="fa fa-star text-tdhh-orange"></span>
                                            </span>
                                            <span class="ml-1 text-tdhh-orange">
                                                4.0
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 leading-6">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ipsum massa id egestas nibh tortor tortor nisi. Sed morbi amet, massa a nunc porta tincidunt. Ac eu molestie nisi, feugiat ultrices. Amet, a eros pretium molestie aliquam.
                                    </div>
                                </div>
                                <div class="p-4 border border-t-0 border-gray-300">
                                    <div class="flex justify-between">
                                        <div class="flex space-x-2">
                                            <div class="w-10 h-10 flex bg-black rounded-full overflow-hidden">
                                                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
                                            </div>
                                            <div>
                                                <div>
                                                    Firstname Lastname
                                                </div>
                                                <div class="text-fade text-xs">
                                                    Sept 20, 2022
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>
                                                <span class="fa fa-star text-tdhh-orange"></span>
                                            </span>
                                            <span class="ml-1 text-tdhh-orange">
                                                4.0
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 leading-6">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ipsum massa id egestas nibh tortor tortor nisi. Sed morbi amet, massa a nunc porta tincidunt. Ac eu molestie nisi, feugiat ultrices. Amet, a eros pretium molestie aliquam.
                                    </div>
                                </div>
                                <div class="p-4 border border-t-0 border-gray-300">
                                    <div class="flex justify-between">
                                        <div class="flex space-x-2">
                                            <div class="w-10 h-10 flex bg-black rounded-full overflow-hidden">
                                                <img src={{asset('img/users/dp.png')}} alt="user-review-dp" class="object-contain m-auto" />
                                            </div>
                                            <div>
                                                <div>
                                                    Firstname Lastname
                                                </div>
                                                <div class="text-fade text-xs">
                                                    Sept 20, 2022
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span>
                                                <span class="fa fa-star text-tdhh-orange"></span>
                                            </span>
                                            <span class="ml-1 text-tdhh-orange">
                                                4.0
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 leading-6">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ipsum massa id egestas nibh tortor tortor nisi. Sed morbi amet, massa a nunc porta tincidunt. Ac eu molestie nisi, feugiat ultrices. Amet, a eros pretium molestie aliquam.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="w-1/4 flex-shrink-0 hidden lg:block">
                    <div class="sticky sticky-top">

                        @include('common.home-reserve-property')
                        @include('common.home-contact-owner')

                    </div>
                </div>

            </div>

            <div class="mt-6">
                <div class="flex justify-between">
                    <div class="page-title">
                        Similar Properties
                    </div>
                </div>

                {{-- max of 8 --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
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

        </div>

    </div>
@endsection
