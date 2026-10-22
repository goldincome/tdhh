@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Reserve - #123456 - Beautiful Pinnacle Port condo w/ pools and basketball court right next to beach - Property
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
    <style>
        body{
            background: #eee;
        }
    </style>
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".paypal-click").click(function(){
                // alert(".. paypal ..");
                $('#gn').val($('#guest_name').val());
                $('#ge').val($('#guest_email').val());
                $('#gp').val($('#guest_phone').val());
                $('#gcp').val($('#guest_car_parking').val());
                processGuestCheckout();
            })
            $(".paystack-click").click(function(){
                // alert(".. paystack ..");
                $('#gn1').val($('#guest_name').val());
                $('#ge1').val($('#guest_email').val());
                $('#gp1').val($('#guest_phone').val());
                $('#gcp1').val($('#guest_car_parking').val());
                processGuestCheckout();
            })
            $(".wallet-click").click(function(){
                // alert(".. wallet ..");
                $('#gn2').val($('#guest_name').val());
                $('#ge2').val($('#guest_email').val());
                $('#gp2').val($('#guest_phone').val());
                $('#gcp2').val($('#guest_car_parking').val());
                processGuestCheckout();
            })
            function processGuestCheckout(){
                // alert("guest");
               
                $(".guest-checkout").submit();
            }



            process_guestname_display();
            $('#guest_name').keyup(function(){
                process_guestname_display();
            })

            function process_guestname_display(){
                const name = $('#guest_name').val();
                $('#guest_name_display').text(name);
            }



            parking_fee_toggle();
            $(".parking-fee-selection").change(function(){
                parking_fee_toggle();
            });

            function parking_fee_toggle(){
                const parking_selection = $(".parking-fee-selection").val();
                if (parking_selection === "yes"){
                    $(".parking-fee-render").removeClass("hidden");
                }
                else{
                    $(".parking-fee-render").addClass("hidden");
                }
                calculate_pricing();
            }

            function calculate_pricing(){
                let total = 0;
                let currency = $("#currency").val().substr(0,1);
                total += parseFloat($("#booking-fee").val());
                total += parseFloat($("#cleaning-fee").val());
                if ($(".parking-fee-selection").val() === "yes"){
                    total += parseFloat($("#parking-fee").val());
                }
                $(".checkout-total").text(currency + "" + total);
            }

            $(".checkout-continue").click(function(){
                const parking_selection = $(".parking-fee-selection").val();
                if (parking_selection === ""){
                    alert("Please select a parking option");
                }
                else{
                    if ($("#agree_checkbox").prop('checked') === true){
                        $("#modal-payment-options").toggle();
                    }
                    else{
                        alert("Please read and accept the house rules of this property");
                    }
                }
            });

            $(".modal-checkout-close").click(function(){
                $("#modal-payment-options").toggle();
            });
        })
    </script>
@endsection


@section('content')
    <div>
        @include('layouts.errors-and-messages')

        
        <form action="{{ route('front.cart.store') }}" method="post" name="pay">
            @csrf
            <div id="modal-payment-options" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
                <div class="modal-full max-w-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-header-text">
                                Payment options
                            </div>
                            {{-- <button type="button" data-modal-toggle="modal-payment-options" class="modal-header-close">  --}}
                            <button type="button" class="modal-checkout-close modal-header-close">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div>
                                Select your preferred payment option:
                            </div>

                            <div class="mt-8 grid grid-cols-2 gap-x-4">
                                @foreach($paymentGateways as $payment)
                                    @include('payment-options', ['payment', 'item','total','customer','addressIds'])
                                @endforeach
                                {{-- @if($walletBalance >= $cartItems->getCartTotal()) --}}
                                
                                        
                                        
                                    
                                {{-- @endif --}}
                            </div>
                                
                        </div>
                    </div>
                </div>
            </div>




            @foreach($cartItems->getCartItems() as $item)
            
                <div class="top-wide" style="height: 14rem;">
                    <div style="margin-top: 36px;">
                        <div>
                            Confirm Booking
                        </div>
                        <div>
                            <a href="/">Home</a>
                            <span>/</span>
                            <a href="{{route('front.list')}}">Properties</a>
                            <span>/</span>
                            <a href="{{route('front.details', $item->id)}}">This property</a>
                            <span>/</span>
                            <a>Reserve</a>
                        </div>
                    </div>
                    <div></div>
                </div>

                <div class="-mt-24 mb-8 sm:mb-12 container bg-white py-8 sm:py-12 relative z-20">
                    <div class="lg:px-4">

                        <div class="lg:flex">
                            <div class="lg:w-2/3">
                                <div class="lg:mr-10 lg:pr-10 lg:border-r border-gray-300">
                                    
                                    <div class="font-bold lg:text-xl">
                                        <a href="{{route('front.details', $item->id)}}" class="inline-block mr-4">
                                            <span class="fa fa-angle-left"></span>
                                        </a>
                                        {{-- Reservation Information --}}
                                        Booking confirmation
                                    </div>

                                    <div class="mt-6">
                                        <div class="flex space-x-2">
                                            <a href="{{route('front.details', $item->id)}}" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                <img src={{asset($item->associatedModel->cover_image->getUrl())}} alt="{{$item->associatedModel->name}}" class="object-cover m-auto" />
                                            </a>
                                            <div class="md:my-auto">
                                                <div class="md:h-16">
                                                    <a href="{{route('front.details', $item->id)}}" class="font-bold hover:underline ellipsis-2-lines">
                                                        {{$item->associatedModel->name}}  {{-- Beautiful Pinnacle Port condo w/ pools and basketball court right next to beach --}}
                                                    </a>
                                                    <div class="mt-1 text-fade text-xs">
                                                        <span class="fa fa-map-marker mr-1"></span>
                                                        {{$item->associatedModel->address}} {{$item->associatedModel->neighborhood}}
                                                    </div>
                                                </div>
                                                <div class="mt-1 flex space-x-4 text-sm">
                                                    <div>
                                                        <span class="fa fa-bed"></span>
                                                        {{$item->associatedModel->bedroom_qty}}
                                                    </div>
                                                    <div>
                                                        <span class="fa fa-shower"></span>
                                                        {{$item->associatedModel->bathroom_qty}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-8 mt-8 border-t overflow-auto">


                                        <div class="grid grid-cols-2 gap-6">
                                            <div class="flex">
                                                <div class="w-8 flex-shrink-0">
                                                    <span class="fas fa-calendar mt-1"></span>
                                                </div>
                                                <div>
                                                    <div>
                                                        Check-in date
                                                    </div>
                                                    <div class="font-bold">
                                                        {{date('l, d F, Y', strtotime($item->attributes->checkinDate))}}
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-8 flex-shrink-0">
                                                    <span class="fas fa-calendar mt-1"></span>
                                                </div>
                                                <div>
                                                    <div>
                                                        Check-out date
                                                    </div>
                                                    <div class="font-bold">
                                                        {{date('l, d F, Y', strtotime($item->attributes->checkoutDate))}}
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
                                                        From {{date('h:i A', strtotime($item->associatedModel->check_in_from))}}
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
                                                        Up till {{date('h:i A', strtotime($item->associatedModel->check_out_to))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="px-6 py-4 mt-8 bg-gray-100">
                                            Expected Guests
                                        </div>
                                        

                                        <div class="mt-8 grid grid-cols-2 gap-8">
                                            <div class="flex">
                                                <div class="w-8 flex-shrink-0">
                                                    <span class="fa fa-users mt-1"></span>
                                                </div>
                                                <div>
                                                    <div>
                                                        Number of Adults
                                                    </div>
                                                    <div class="font-bold">
                                                        {{$item->attributes->num_of_adult}} adults
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-8 flex-shrink-0">
                                                    <span class="fas fa-children mt-1"></span>
                                                </div>
                                                <div>
                                                    <div>
                                                        Number of Children
                                                    </div>
                                                    <div class="font-bold">
                                                        {{$item->attributes->num_of_children}} children
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-8 flex-shrink-0">
                                                    <span class="fas fa-dog mt-1"></span>
                                                </div>
                                                <div>
                                                    <div>
                                                        Number of Pets
                                                    </div>
                                                    <div class="font-bold">
                                                        {{$item->attributes->num_of_pet}} pets
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="px-6 py-4 mt-8 bg-gray-100">
                                            House Rules
                                        </div>
                                        

                                        <div class="mt-8 grid grid-cols-2 gap-8">
                                            @foreach ($item->associatedModel->houseRules as $houseRule)
                                
                                
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


                                    </div>

                                </div>
                            </div>


                            
                            <div class="mt-10 lg:mt-0 pt-10 lg:pt-0 lg:w-1/3 lg:flex-shrink-0 border-t lg:border-transparent">
                                <div class="font-bold lg:text-xl">
                                    Checkout
                                </div>

                                <div class="p-4 mt-6 border rounded-lg">
                                    <div class="font-bold mb-4">
                                        Guest Information
                                    </div>

                                    @include('layouts.errors-and-messages')
                                    @if(auth()->user())
                                        <div>
                                            <div class="form-group">
                                                <label>
                                                    Name
                                                    <span class="form-input-required">*</span>
                                                </label>
                                                <input type="text" id="guest_name" name="guest_name" placeholder="" class="form-input" value="{{auth()->user()->first_name ? auth()->user()->first_name." ".auth()->user()->last_name: old('name')}}" required/>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6">
                                                <div class="form-group">
                                                    <label>
                                                        Email Address
                                                        <span class="form-input-required">*</span>
                                                    </label>
                                                    <input type="email" id="guest_email" name="guest_email" placeholder="" class="form-input" required value="{{auth()->user()->email ?: old('email')}}"/>
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        Phone Number
                                                        <span class="form-input-required">*</span>
                                                    </label>
                                                    <input type="tel" id="guest_phone" name="guest_phone" placeholder="" class="form-input"value="{{auth()->user()->phone ?: old('phone')}}" required/>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    
                                        @if($item->associatedModel->is_parking_charge_available  && $item->associatedModel->is_parking_charged )
                                            <div class="form-group">
                                                <label>
                                                    Will you be parking a vehicle?
                                                    <span class="form-input-required">*</span>
                                                </label>
                                                <select id="guest_car_parking" name="guest_car_parking" class="form-input parking-fee-selection" required>
                                                    <option value="">Select</option>
                                                    <option value="yes">Yes (parking fee will apply)</option>
                                                    <option value="no">No (parking fee not applied)</option>
                                                </select>
                                            </div>
                                        @else
                                            <input type="hidden" class="parking-fee-selection" value="none">
                                        @endif
                                    @endif

                                    <div class="mt-2 space-y-0.5">
                                        <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                            <div>
                                                {{currency_convert($cartItems->getCartSubtotal()/$item->attributes->quantity,$item->associatedModel->country->code,currency()->getUserCurrency())}} * {{$item->attributes->quantity}} nights
                                            </div>
                                            <div class="text-right">
                                                {{currency_convert($cartItems->getCartSubtotal(),$item->associatedModel->country->code,currency()->getUserCurrency())}}
                                            </div>
                                        </div>
                                        @if($item->associatedModel->is_cleaning_available)
                                            <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                                <div>
                                                    Cleaning Fee ({{ $item->associatedModel->cleaning_type === 'per_stay' ? currency_convert($item->associatedModel->cleaning_fee,$item->associatedModel->country->code,currency()->getUserCurrency()) . '/stay' : $item->attributes->quantity.' nights'}})
                                                </div>
                                                <div class="text-right">
                                                    {{currency_convert($item->attributes->cleaning_fee,$item->associatedModel->country->code,currency()->getUserCurrency())}}
                                                
                                                </div>
                                            </div>
                                        @endif
                                        @if($item->associatedModel->is_parking_charge_available == 'yes' && $item->associatedModel->is_parking_charged == 'yes')
                                            <div class="px-3 py-3 bg-gray-100 flex justify-between parking-fee-render">
                                                <div>
                                                    Parking Fee will apply when parking
                                                </div>
                                                <div class="text-right">
                                                    {{-- {{currency_convert($item->associatedModel->parking_fee,$item->associatedModel->country->code,currency()->getUserCurrency())}} --}}
                                                </div>
                                            </div>
                                        @endif
                                        {{-- <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                            <div>
                                                VAT
                                            </div>
                                            <div class="text-right">
                                                £0
                                            </div>
                                        </div> --}}
                                        <div>
                                            <div class="pt-3 mt-5 border-t flex justify-between text-lg font-bold">
                                                <div>
                                                    Total
                                                </div>
                                                <div class="text-right checkout-total">
                                                    {{currency_convert($cartItems->getCartTotal(),$item->associatedModel->country->code,currency()->getUserCurrency())}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-3 mt-5">

                                            <label class="cursor-pointer">
                                                <div class="flex space-x-3">
                                                    <div class="mt-0.5 flex">
                                                        <input type="checkbox" id="agree_checkbox" class="form-input-checkbox">
                                                    </div>
                                                    <div class="my-auto">
                                                        I, <span class="font-bold" id="guest_name_display"></span>, have read and agree to the house rules of this property
                                                    </div>
                                                </div>
                                            </label>

                                            <div class="mt-2">
                                                <button type="button" class="checkout-continue btn btn-orange btn-block">
                                                    Pay Now
                                                </button>
                                            </div>
                                            {{-- <div class="mt-2">
                                                <a href="" class="btn btn-transparent-black btn-block">
                                                    Pay On Arrival
                                                </a>
                                            </div> --}}
                                            <div class="mt-4 text-center text-sm text-fade">
                                                Reservation is done after order confirmation
                                            </div>
                                        </div>

                                        <input type="hidden" id="booking-fee" value={{currency_convert($cartItems->getCartSubtotal(),$item->associatedModel->country->code,currency()->getUserCurrency(), false)}}>
                                        <input type="hidden" id="cleaning-fee" value={{currency_convert($item->attributes->cleaning_fee,$item->associatedModel->country->code,currency()->getUserCurrency(), false)}}>
                                        {{-- <input type="hidden" id="parking-fee" value={{currency_convert($item->associatedModel->parking_fee,$item->associatedModel->country->code,currency()->getUserCurrency(), false)}}> --}}
                                        <input type="hidden" id="parking-fee" value={{currency_convert(0,$item->associatedModel->country->code,currency()->getUserCurrency(), false)}}>
                                        <input type="hidden" id="currency" value={{currency_convert(0,$item->associatedModel->country->code,currency()->getUserCurrency())}}>


                                        
                                    </div>
                                </div>


    



                            </div>
                            
                        </div>
                    </div>
                </div>
                
            @endforeach
        </form>
    </div>
       
@endsection