<form action="{{route('front.property.search')}}" method="get">
    

    {{-- <div class="p-4 pb-0 mb-4 bg-gray-100 rounded-md"> --}}
    <div class="">
        <div class="flex flex-col">

            <div>
                <div class="form-group">
                    <label class="font-bold ellipsis">
                        Location
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="location" value="{{$data->location ?? ''}}" placeholder="Eg: Birmingham" class="form-input" />
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label class="font-bold ellipsis">
                        Guests
                        <span class="form-input-required">*</span>
                    </label>
                    <div>
                        <div class="form-input cursor-pointer" data-modal-toggle="modal-guests" style="padding: 0px 12px;">
                            <div class="flex justify-between items-center">
                                <span class="home-reserve-guest-display mt-2">Select guests</span>
                                <span class="fa fa-angle-down mt-2"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label class="font-bold ellipsis">
                        <span class="hidden lg:inline">Check-in / Check-out</span>
                        <span class="inline lg:hidden">Check-in & out</span>
                        <span class="form-input-required">*</span>
                    </label>
                    <div class="flex justify-between">
                        <input type="text" name="check_in_date" value="{{$data->check_in_date?? ''}}" class="search-checkin-date-from form-input" />
                        <input type="text" name="check_out_date" value="{{$data->check_out_date?? ''}}" class="search-checkin-date-to form-input" />
                    </div>
                </div>
            </div>

        </div>
            
        <input type="hidden" name="num_of_adult" class="home-reserve-form-adults" value="0">
        <input type="hidden" name="num_of_children" class="home-reserve-form-children" value="0">
        <input type="hidden" name="num_of_pet" class="home-reserve-form-pets" value="0">
        <input type="hidden" name="check_in_date" class="search-response-checkin" />
        <input type="hidden" name="check_out_date" class="search-response-checkout" />
    </div>


    {{-- 
    <div class="mb-4 border-b border-gray-300 pb-6">
        <div class="font-bold">
            Select Location:
        </div>
        <div class="mt-2 flex flex-col space-y-2">
            <label class="cursor-pointer">
                <a href="?location=london" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        London
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=manchester" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Manchester
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=birmingham" class="flex space-x-3 text-tdhh-orange">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Birmingham
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=london" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        London
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=glasglow" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Glasglow
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=readings" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Readings
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=edinburgh" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Edinburgh
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=leeds" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Leeds
                    </div>
                </a>
            </label>
            <label class="cursor-pointer">
                <a href="?location=belfast" class="flex space-x-3">
                    <div class="flex">
                        <span class="fa fa-angle-right relative top-1"></span>
                    </div>
                    <div class="my-auto">
                        Belfast
                    </div>
                </a>
            </label>
        </div>
    </div>
     --}}


    <div class="mb-4 border-b border-gray-300 pb-6">
        <div class="font-bold">
            Select characteristics:
        </div>
        <div class="mt-2 flex flex-col space-y-2">
            @foreach($characteristics as $characteristic)
                <label class="cursor-pointer">
                    <div class="flex space-x-3">
                        <div class="mt-0.5 flex">
                            <input type="checkbox" name="characteristics[]" id="" value="{{$characteristic->id}}" 
                            class="form-input-checkbox"
                            @if(isset($data->characteristics) && in_array($characteristic->id, $data->characteristics))
                                checked
                            @endif
                            >
                        </div>
                        <div class="my-auto">
                            {{$characteristic->name}}
                        </div>
                    </div>
                </label>
            @endforeach
           
        </div>
    </div>

    <div class="mb-4 border-b border-gray-300 pb-6">
        <div class="font-bold">
            Parking Option:
        </div>
        <div class="mt-2 flex flex-col space-y-2">
            
            <label class="cursor-pointer">
                <div class="flex space-x-3">
                    <div class="mt-0.5 flex">
                        <input type="checkbox" name="parking_is_available" value="yes" id="" 
                        {{isset($data->parking_is_available) && $data->parking_is_available == 'yes' ? 'checked': ''}}
                        class="form-input-checkbox">
                    </div>
                    <div class="my-auto">
                        Has parking space
                    </div>
                </div>
            </label>
            
        </div>
    </div>

    <div class="mb-4 border-b border-gray-300 pb-6">
        <div class="font-bold">
            House Rules:
        </div>
        <div class="mt-2 flex flex-col space-y-2">
            @foreach($houseRules as $houseRule)
                <label class="cursor-pointer">
                    <div class="flex space-x-3">
                        <div class="mt-0.5 flex">
                            <input type="checkbox" name="house_rules[]" value="{{$houseRule->id}}" id="" 
                            @if(isset($data->house_rules) && in_array($houseRule->id, $data->house_rules))
                                checked
                            @endif
                            class="form-input-checkbox">
                        </div>
                        <div class="my-auto">
                            {{$houseRule->name}}
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    
    <div class="mb-4 border-b border-gray-300 pb-6">
        <div class="font-bold">
            Select price range:
        </div>
        <div class="mt-2">
            <div id="" class="slider-range price-filter-range" name="rangeInput" style="width: 100%;"></div>
            <div class="flex justify-between space-x-2 mt-2">
                <input name="price_from" type="number" min=0 max="9900" value="{{$data->price_from ?? 20}}" oninput="validity.valid||(value='0');" id="" class="min_price price-range-field form-input-sm" style="width: 100%;" />
                <input name="price_to" type="number" min=0 max="10000" value="{{$data->price_to ?? 900}}" oninput="validity.valid||(value='10000');" id="" class="max_price price-range-field form-input-sm" style="width: 100%;" />
            </div>
        </div>
    </div>


    <div class="pb-6">
        <button type="submit" class="btn btn-tdhh-orange">
            Search
        </button>
    </div>


</form>