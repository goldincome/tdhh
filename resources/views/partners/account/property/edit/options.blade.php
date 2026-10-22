@extends('layouts.partner')


@section('title')
    Edit property - #{{$property->reference}}
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


            calculate_parking();
            $(".parking-available, .parking-charged").change(function(){
                calculate_parking();
            });


            function calculate_parking(){
                if ($(".parking-available").val() === "yes"){
                    $(".parking-available-yes").removeClass("hidden");

                    if ($(".parking-charged").val() === "yes"){
                        $(".parking-charged-yes").removeClass("hidden");
                    }
                    else{
                        $(".parking-charged-yes").addClass("hidden");
                    }
                }
                else{
                    $(".parking-available-yes, .parking-charged-yes").addClass("hidden");
                }
            }


        })
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
                    <a href="/partners/property/edit/123">
                        General Info
                    </a>
                    <a href="/partners/property/edit/123/options" class="active">
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
                <form method="post" action="{{route('partner.property.edit.options.submit', $property->id)}}" class="max-w-xl mx-auto">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <div class="form-group">
                                <label>
                                    Description
                                    <span class="form-input-required">*</span>
                                </label>
                                <textarea class="form-input" style="height: 100px;" name="description">{{$property->description}}</textarea>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>
                                    Neighborhood
                                    <span class="form-input-required">*</span>
                                </label>
                                <textarea name="neighborhood" class="form-input" style="height: 100px;">{{$property->neighborhood}}</textarea>
                            </div>
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div>
                            Characteristics available in the property
                        </div>
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach ($characteristics as $characteristic)
                                <label class="cursor-pointer">
                                    <div class="flex space-x-3">
                                        <div class="mt-0.5 flex">
                                            <input type="checkbox" name="characteristics[][characteristic_id]" @if(in_array($characteristic->id, $property->characteristics()->pluck('characteristic_id')->toArray())) checked @endif
                                             id="{{$characteristic->id}}" value="{{$characteristic->id}}" class="form-input-checkbox">
                                        </div>
                                        <div class="my-auto">
                                            {{$characteristic->name}}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8">
                        <div class="form-group">
                            <label>
                                Parking available for guest
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="parking_is_available" id="" class="form-input parking-available">
                                <option value="" disabled>Select</option>
                                <option value="yes" @if($property->parking_is_available == 'yes') selected @endif>Yes</option>
                                <option value="no" @if($property->parking_is_available == 'no') selected @endif>No</option>
                            </select>
                        </div>
                        <div class="form-group hidden parking-available-yes">
                            <label>
                                Parking location
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="parking_location" id="" class="form-input">
                                <option value="" selected disabled>Select</option>
                                <option value="on-site" @if($property->parkingOption->parking_location == 'on-site') selected @endif >On-site</option>
                                <option value="off-site" @if($property->parkingOption->parking_location == 'off-site') selected @endif >Off-site</option>
                            </select>
                        </div>
                        <div class="form-group hidden parking-available-yes">
                            <label>
                                Parking type
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="parking_type" id="" class="form-input">
                                <option value="" selected disabled>Select</option>
                                <option value="private" @if($property->parkingOption->parking_type == 'private') selected @endif >Private</option>
                                <option value="public" @if($property->parkingOption->parking_type == 'public') selected @endif >Public</option>
                            </select>
                        </div>
                        <div class="form-group hidden parking-available-yes">
                            <label>
                                Parking reservation
                                <span class="form-input-required">*</span>
                            </label>

                            <select name="parking_reservation" id="" class="form-input">
                                <option value="" selected disabled>Select</option>
                                <option value="allowed" @if($property->parkingOption->parking_reservation == 'allowed') selected @endif>Allow reservations</option>
                                <option value="disallowed" @if($property->parkingOption->parking_reservation == 'disallowed') selected @endif>Don't allow reservations</option>
                            </select>
                        </div>
                        <div class="form-group hidden parking-available-yes">
                            <label>
                                Do you charge parking fee?
                                <span class="form-input-required">*</span>
                            </label>
                            {{-- put "selected" attribute in the <option> that was selected, and the JS will auto hide/show the necessary things --}}
                            <select name="parking_charge" id="" class="form-input parking-charged">
                                <option value="" disabled>Select</option>
                                <option value="yes" @if($property->parkingOption->parking_charge == 'yes') selected @endif>Yes, parking is charged</option>
                                <option value="no" @if($property->parkingOption->parking_charge == 'no') selected @endif>No, parking is free</option>
                            </select>
                        </div>
                        <div class="form-group hidden parking-charged-yes">
                            <label>
                                Parking fee
                                <span class="form-input-required">*</span>
                            </label>
                            <label class="relative block">
                                <div class="pointer-events-none w-8 h-full absolute flex">
                                    <span class="m-auto">
                                        £
                                    </span>
                                </div>
                                <input type="number" name="parking_fee" value="{{$property->parkingOption->parking_charge}}" class="form-input w-full" style="padding-left: 32px !important;">
                            </label>
                        </div>
                    </div>



                    <hr class="my-12 border-tdhh-brown" />


                    <div>
                        <div>
                            House rules
                        </div>
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($rules as $rule)
                                <label class="cursor-pointer">
                                    <div class="flex space-x-3">
                                        <div class="mt-0.5 flex">
                                            <input type="checkbox" name="house_rules[][rule_id]" id="{{$rule->id}}" value="{{$rule->id}}"
                                                @if(in_array($rule->id, $property->houseRules()->pluck('rule_id')->toArray())) checked @endif
                                                class="form-input-checkbox">
                                        </div>
                                        <div class="my-auto">
                                           {{$rule->name}}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <hr class="my-12 border-tdhh-brown" />


                    <div class="flex space-x-4 justify-end">
                        <a href="/partners/property/edit/123" class="btn btn-transparent-black">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </a>
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

