<form method="post" action="{{route('front.property.start.reserve')}}" class="home-reserve-form p-4 xl:p-5 bg-white border-t rounded-lg overflow-hidden shadow-xl">
    @csrf
    @include('layouts.errors-and-messages')
    <input type="hidden" name="property_id" value="{{$property->id}}" />
    <div class="mb-6 hidden lg:flex justify-between items-center">

        <div class="my-auto">
            
            <span class="text-tdhh-orange text-xl font-bold">
                {{currency_convert(number_format($property->todayPrice,2),$property->country->code,currency()->getUserCurrency())}}
            </span>
            <span class="text-xs">
                / night
            </span>
        </div>
        <div class="mt-2 text-xs sm:mt-1">
            <span class="mr-1">
                <span class="fa fa-star"></span>
                4.8
            </span>
            <a href="#reviews" class="text-fade underline">
                20 reviews
            </a>
        </div>
    </div>

    <div>
        <div class="border border-black rounded-md">
            <div class="grid grid-cols-2 gap-0">
                <div class="pt-3 border-r border-black">
                    <div>
                        <label class="px-3 uppercase" style="font-size: 10px;">
                            Check-in
                        </label>
                        <input type="date" name="checkin_date" min="<?php echo date("Y-m-d");?>" value="{{$data->check_in_date->format("Y-m-d") ?? old('checkin_date')}}" class="home-reserve-form-checkin form-input border-none" style="padding: 0px 12px;" />
                    </div>
                </div>
                <div class="pt-3">
                    <div>
                        <label class="px-3 uppercase" style="font-size: 10px;">
                            Check-out
                        </label>
                        <input type="date" name="checkout_date" min="<?php echo date("Y-m-d");?>" value="{{$data->check_out_date->format("Y-m-d") ?? old('checkout_date')}}" class="home-reserve-form-checkout form-input border-none" style="padding: 0px 12px;" />
                    </div>
                </div>
            </div>
            <div class="border-t border-black" data-modal-toggle="modal-guests">
                <div class="pt-3 cursor-pointer">
                    <label class="px-3 uppercase" style="font-size: 10px;">
                        Guests
                    </label>
                    <div class="form-input border-none" style="padding: 0px 12px;">
                        <div class="flex justify-between items-center">
                            <span class="home-reserve-guest-display">Select guests</span>
                            <span class="fa fa-angle-down"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div>
                {{-- <a href="/property/12345678/reserve" class="btn btn-orange btn-block">
                    Reserve
                </a> --}}
                
                <button type="submit" class="btn btn-orange btn-block">
                    Reserve
                </button>
            </div>
            <div class="mt-2 text-center text-xs text-fade">
                You won’t be charged yet
            </div>
        </div>
    </div>

    <div class="mt-8 space-y-3">
        <div class="flex justify-between">
            <div class="form-display-nights">
                  {{currency_convert(number_format($property->todayPrice,2),$property->country->code,currency()->getUserCurrency())}}
            </div>
            <div class="text-right form-display-price">
                {{ currency_symbol()}}0
            </div>
        </div>
        @if($property->is_cleaning_available)
            <div class="flex justify-between">
                <div class="form-display-cleaning-nights">
                    Cleaning fee {{$property->cleaning_type === 'per_stay' ? 'per stay' : 'per night'}}
                </div>
                <div class="text-right form-display-cleaning">
                    {{ currency_symbol()}}{{$property->cleaning_fee}}
                </div>
            </div>
        @endif
        {{-- @if($property->is_parking_charge_available == 'yes' && $property->is_parking_charged == 'yes')
            <div class="flex justify-between">
                <div>
                    Parking fee
                </div>
                <div class="text-right">
                    {{ currency_symbol()}}{{currency_convert($property->parking_fee,$property->country->code,currency()->getUserCurrency(),false)}}
                </div>
            </div>
        @endif --}}
       
        <div class="pt-3 border-t flex justify-between">
            <div>
                Subtotal
            </div>
            <div class="text-right form-display-subtotal">
                {{ currency_symbol()}}0
            </div>
        </div>
    </div>

    <input type="hidden" name="num_of_adult" class="home-reserve-form-adults" value="0">
    <input type="hidden" name="num_of_children" class="home-reserve-form-children" value="0">
    <input type="hidden" name="num_of_pet" class="home-reserve-form-pets" value="0">
    <input type="hidden" name="property_id" value="{{$property->id}}"/>
    <input type="hidden" class="home-reserve-form-price" value="{{number_format(currency_convert($property->todayPrice,$property->country->code,currency()->getUserCurrency(), false),2)}}">
    <input type="hidden" class="home-reserve-form-service-fee" value="0">

    <input type="hidden" class="home-reserve-form-cleaning-type" value="{{$property->cleaning_type}}">
    @if($property->is_cleaning_available)
        <input type="hidden" class="home-reserve-form-cleaning-fee" value="{{currency_convert($property->cleaning_fee,$property->country->code,currency()->getUserCurrency(), false)}}">
    @else
        <input type="hidden" class="home-reserve-form-cleaning-fee" value="0">
    @endif

    {{-- @if($property->is_parking_charge_available  && $property->is_parking_charged)
        <input type="hidden" class="home-reserve-form-parking-fee" value="{{currency_convert($property->parking_fee,$property->country->code,currency()->getUserCurrency(),false)}}">
    @else
        <input type="hidden" class="home-reserve-form-parking-fee" value="0">
    @endif --}}
    <input type="hidden" class="home-reserve-form-parking-fee" value="0">

</form>
