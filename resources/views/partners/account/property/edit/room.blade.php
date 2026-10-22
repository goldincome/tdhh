@extends('layouts.partner')


@section('title')
    Edit property
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){


            $(".quantity-box > div:nth-child(1)").click(function(){
                const input_box = $(this).next("div").children("input");
                if (input_box.val() > 1)
                input_box.val(parseInt(input_box.val()) - 1);
                apartment_livingroom_qty();
                sofa_bed_count();
                apartment_bathroom_qty();
            });


            $(".quantity-box > div:nth-child(3)").click(function(){
                const input_box = $(this).prev("div").children("input");
                input_box.val(parseInt(input_box.val()) + 1);
                apartment_livingroom_qty();
                sofa_bed_count();
                apartment_bathroom_qty();
            });


            // $(".property-type").change(function(){
            //     toggle_property_type();
            // });
            toggle_property_type();


            // $(document).on("click", '.bedroom', function(){
            // $("body").delegate(".bedroom", "click",  function(){
            $(".bedroom").click(function(){
                const index = $(this).attr("id").split("-")[1];
                $("#bedroom-index").val(index);
                $(".modal-header-text").text("Bedroom room " + index);

                const single_bed_count = $("#bedroom-"+index).find(".single-bed-count-"+index).val();
                const double_bed_count = $("#bedroom-"+index).find(".double-bed-count-"+index).val();
                const large_bed_count = $("#bedroom-"+index).find(".large-bed-count-"+index).val();
                const extra_large_bed_count = $("#bedroom-"+index).find(".extralarge-bed-count-"+index).val();

                $("#bed-type-input-1").val(single_bed_count);
                $("#bed-type-input-2").val(double_bed_count);
                $("#bed-type-input-3").val(large_bed_count);
                $("#bed-type-input-4").val(extra_large_bed_count);
            });


            getBedTypeSummary();
            $("#bed-type-input-1, #bed-type-input-2, #bed-type-input-3, #bed-type-input-4").keyup(function(){
                getBedTypeSummary();
            });


            function getBedTypeSummary(){
                let index = $("#bedroom-index").val();
                if (index === ""){
                    const all_bedroom = $(".all-bedroom").children().length;
                    if (all_bedroom > 0){
                        for (let i = 0; i < all_bedroom; i++){
                            getBedTypeSummaryDetails(i+1);
                        }
                    }
                }
                else{
                    getBedTypeSummaryDetails(index);
                }
            }


            function getBedTypeSummaryDetails(index){
                let bedroom_bed_count_text = [];

                const bed_count_single = $("#bed-type-input-1").val();
                $("#bedroom-"+index).find(".single-bed-count-"+index).val(bed_count_single);
                if (bed_count_single !== "" && parseInt(bed_count_single) !== 0){
                    bedroom_bed_count_text.push(bed_count_single + " single bed");
                }

                const bed_count_double = $("#bed-type-input-2").val();
                $("#bedroom-"+index).find(".double-bed-count-"+index).val(bed_count_double);
                if (bed_count_double !== "" && parseInt(bed_count_double) !== 0){
                    bedroom_bed_count_text.push(bed_count_double + " double bed");
                }

                const bed_count_large = $("#bed-type-input-3").val();
                $("#bedroom-"+index).find(".large-bed-count-"+index).val(bed_count_large);
                if (bed_count_large !== "" && parseInt(bed_count_large) !== 0){
                    bedroom_bed_count_text.push(bed_count_large + " large bed");
                }

                const bed_count_extralarge = $("#bed-type-input-4").val();
                $("#bedroom-"+index).find(".extralarge-bed-count-"+index).val(bed_count_extralarge);
                if (bed_count_extralarge !== "" && parseInt(bed_count_extralarge) !== 0){
                    bedroom_bed_count_text.push(bed_count_extralarge + " extra-large bed");
                }

                // console.log("bedroom_bed_count_text", bedroom_bed_count_text);
                if (bedroom_bed_count_text.length === 0){
                    $("#bedroom-"+index).find(".bedroom-bed-count-text").text("No beds selected");
                }
                else{
                    $("#bedroom-"+index).find(".bedroom-bed-count-text").text(bedroom_bed_count_text.join(", "));
                }
            }


            $(".add-bedroom").click(function(){
                // reset_bedroom_order();
                // const next_order = ($(".all-bedroom .bedroom:not(.hidden)").length + 1);

                let action = true;
                $(".all-bedroom .bedroom").each(function(){
                    if ($(this).hasClass("hidden")){
                        if (action === true){
                            $(this).removeClass("hidden").addClass("order-"+next_order);
                        }
                        action = false;
                    }
                });

                reset_bedroom_title();
                toggle_add_bedroom_btn();
            });


            $(".remove-bedroom").click(function(){
                const index = $(this).parent().parent().index() + 1;
                $(".all-bedroom .bedroom:nth-child(" + (index) + ")").addClass("hidden");
                $(".all-bedroom .bedroom:nth-child(" + (index) + ")").find(".be-count").val("");
                $(".all-bedroom .bedroom:nth-child(" + (index) + ")").find(".bedroom-bed-count-text").text("No beds selected");
                reset_bedroom_title();
                toggle_add_bedroom_btn();
            });


            function reset_bedroom_title(){
                let index = 1;
                $(".all-bedroom .bedroom:not(.hidden)").each(function(){
                    $(this).find(".bedroom-title").text("Bedroom " + index);
                    index++;
                })
            }


            function reset_bedroom_order(){
                // for (let i = 1; i <= 10; i++){
                //     $(".all-bedroom .bedroom").removeClass("order-" + i);
                // }
                // let order = 1;
                // $(".all-bedroom .bedroom:not(.hidden)").each(function(){
                //     $(this).addClass("order-" + order);
                //     order++;
                // })
            }


            function toggle_add_bedroom_btn(){
                const all_active_bedrooms = $(".all-bedroom .bedroom:not(.hidden)").length;
                if (all_active_bedrooms === 10){
                    $(".add-bedroom").addClass("hidden");
                }
                else{
                    $(".add-bedroom").removeClass("hidden");
                }
            }


            apartment_livingroom_qty();
            $(".living-qty-input").change(function(){
                apartment_livingroom_qty();
            });


            toggle_sofa_bed();
            $(".sofa-bed-toggle").change(function(){
                toggle_sofa_bed();
            });


            sofa_bed_count();
            $(".sofa-bed-input").change(function(){
                sofa_bed_count();
            });


            apartment_bathroom_qty();
            $(".bathroom-qty-input").change(function(){
                apartment_bathroom_qty();
            });


            function toggle_property_type(){
                if ($(".property-type").val() === "room"){
                    $(".property-type-room").removeClass("hidden");
                    $(".property-type-apartment").addClass("hidden");
                }
                else if ($(".property-type").val() === "apartment"){
                    $(".property-type-room").addClass("hidden");
                    $(".property-type-apartment").removeClass("hidden");
                }
                else{
                    $(".property-type-room").removeClass("hidden");
                    $(".property-type-apartment").removeClass("hidden");
                }
            }


            function sofa_bed_count(){
                const sofa_bed_qty = $(".sofa-bed-input").val();
                $(".sofa-bed-count").text(sofa_bed_qty);
            }


            function apartment_livingroom_qty(){
                const livingroom_qty = $(".livingroom-qty-input").val();
                $(".livingroom-qty-count").text(livingroom_qty);
            }


            function apartment_bathroom_qty(){
                const bathroom_qty = $(".bathroom-qty-input").val();
                $(".bathroom-qty-count").text(bathroom_qty);
            }


            function toggle_sofa_bed(){
                if ($(".sofa-bed-toggle").val() === "yes"){
                    $(".sofa-bed-yes").removeClass("hidden");
                }
                else{
                    $(".sofa-bed-yes").addClass("hidden");
                }
            }

        })


        // function openBedroom(index){
        //     $("#bedroom-index").val(index);
        //     $("#modal-bedroom").modal({show: true})

        //     const single_bed_count = $("#bedroom-"+index).find(".single-bed-count-"+index).val();
        //     const double_bed_count = $("#bedroom-"+index).find(".double-bed-count-"+index).val();
        //     const large_bed_count = $("#bedroom-"+index).find(".large-bed-count-"+index).val();
        //     const extra_large_bed_count = $("#bedroom-"+index).find(".extralarge-bed-count-"+index).val();

        //     $("#bed-type-input-1").val(single_bed_count);
        //     $("#bed-type-input-2").val(double_bed_count);
        //     $("#bed-type-input-3").val(large_bed_count);
        //     $("#bed-type-input-4").val(extra_large_bed_count);
        // }
    </script>
@endsection


@section('content')
    <form  method="post" action="{{route('partner.property.create.room.submit', $property)}}" enctype="multipart/form-data">
        @csrf
        <div id="modal-bedroom" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-2xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-header-text">
                            Bedroom room 1
                        </div>
                        <button type="button" data-modal-toggle="modal-bedroom" class="modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <input type="hidden" id="bedroom-index" class="text-black" />
                    </div>

                    <div class="modal-body">

                        <table class="table table-property-form">
                            <thead>
                                <tr>
                                    <th>
                                        Image
                                    </th>
                                    <th>
                                        Bed type
                                    </th>
                                    <th>
                                        Quantity
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bedTypes as $type)
                                    <tr>
                                        <td>
                                            {{-- these pictures will come from db --}}
                                            <a href={{asset('img/icons/single-bed.png')}} target="_blank" class="max-w-[80px] max-h-[80px] block">
                                                <img src={{asset('img/icons/single-bed.png')}} alt="single-bed" class="object-contain m-auto w-full h-full">
                                            </a>
                                        </td>
                                        <td>
                                            <div class="mt-2">
                                                {{$type->name}}
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="bed_types_apartment[][{{$type->name}}]" id="bed-type-input-{{$type->id}}" class="form-input" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="modal-footer">
                        <button type="button" data-modal-toggle="modal-bedroom" class="btn btn-transparent-black">Save</button>
                        {{--
                        <button type="submit" class="btn btn-tdhh-orange">
                            Submit
                        </button>
                        --}}
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-livingroom" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-2xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-header-text">
                            Living room
                        </div>
                        <button type="button" data-modal-toggle="modal-livingroom" class="modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div>
                            <div>
                                How many living rooms:
                            </div>
                            <div class="mt-2 quantity-box">
                                <div>
                                    <span class="fa fa-minus"></span>
                                </div>
                                <div>
                                    <input name="living_room_qty" type="text" class="form-input livingroom-qty-input" value="1" />
                                </div>
                                <div>
                                    <span class="fa fa-plus"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="form-group">
                                <label>
                                    Is sofa-bed available
                                    <span class="form-input-required">*</span>
                                </label>
                                <select name="sofa_availability" id="" class="form-input sofa-bed-toggle">
                                    <option value="" selected disabled>Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="hidden sofa-bed-yes">
                            <div>
                                How many sofa-beds:
                            </div>
                            <div class="mt-2 quantity-box">
                                <div>
                                    <span class="fa fa-minus"></span>
                                </div>
                                <div>
                                    <input type="text" name="sofa_qty" class="form-input sofa-bed-input" value="0" />
                                </div>
                                <div>
                                    <span class="fa fa-plus"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" data-modal-toggle="modal-livingroom" class="btn btn-transparent-black">Close</button>
                        {{--
                        <button type="submit" class="btn btn-tdhh-orange">
                            Submit
                        </button>
                        --}}
                    </div>
                </div>
            </div>
        </div>


        <div id="modal-bathroom" tabindex="-1" aria-hidden="true" class="hidden modal h-modal">
            <div class="modal-full max-w-2xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-header-text">
                            Bathroom
                        </div>
                        <button type="button" data-modal-toggle="modal-bathroom" class="modal-header-close">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div>
                            <div>
                                How many bathrooms:
                            </div>
                            <div class="mt-2 quantity-box">
                                <div>
                                    <span class="fa fa-minus"></span>
                                </div>
                                <div>
                                    <input type="text" name="bathroom_qty" class="form-input bathroom-qty-input" value="1" />
                                </div>
                                <div>
                                    <span class="fa fa-plus"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div>
                                Available items:
                            </div>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($bathroomItems as $item)
                                    <label class="cursor-pointer">
                                        <div class="flex space-x-3">
                                            <div class="mt-0.5 flex">
                                                <input type="checkbox" name="bathroom_items_apartment[][item_id]"  id="{{$item->id}}" value="{{$item->id}}" class="form-input-checkbox">
                                            </div>
                                            <div class="my-auto">
                                                {{$item->name}}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" data-modal-toggle="modal-bathroom" class="btn btn-transparent-black">Close</button>
                        {{--
                        <button type="submit" class="btn btn-tdhh-orange">
                            Submit
                        </button>
                        --}}
                    </div>
                </div>
            </div>
        </div>



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
                        <a href="/partners/property/create/123" class="active">
                            General Info
                        </a>
                        <a href="/partners/property/create/123/options" class="active">
                            Property Options
                        </a>
                        <div class="active">
                            Room Details
                        </div>
                        <div>
                            Property Preview
                        </div>
                    </div>
                </div>

                <div class="mt-1 p-8 min-h-[400px] bg-white">
                    <div class="max-w-xl mx-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <div class="form-group">
                                    <label>
                                        Property type
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <select name="property_type" id="" class="form-input property-type">
                                        <option value="{{App\Models\Property\Property::PROPERTY_TYPE_APARTMENT}}"
                                            @if($property->property_type == 'apartment') selected disabled @endif
                                        >Apartment</option>
                                        <option value={{App\Models\Property\Property::PROPERTY_TYPE_SINGLE_ROOM}}
                                            @if($property->property_type == 'room') selected disabled @endif
                                        >Single Room</option>

                                    </select>
                                </div>
                            </div>
                            <div class="hidden property-type-room">
                                <div class="form-group">
                                    <label>
                                        Room type
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <select name="room_type" id="" class="form-input room-type">
                                        <option value="" selected disabled>Select</option>
                                        <option value="shared">Shared - A room in a shared apartment</option>
                                        <option value="single">Single - Standalone room</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="note text-center hidden property-type-apartment">
                            One apartment where guests can book the entire place
                        </div>

                        <div class="note text-center hidden property-type-room">
                            One room where guests can book only that room
                        </div>


                        {{-- single room only --}}
                        <div class="hidden property-type-room">
                            <hr class="my-12 border-tdhh-brown" />
                            <table class="table table-property-form">
                                <thead>
                                    <tr>
                                        <th>
                                            Image
                                        </th>
                                        <th>
                                            Bed type
                                        </th>
                                        <th>
                                            Quantity
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bedTypes as $type)
                                        <tr>
                                            <td>
                                                {{-- these pictures will come from db --}}
                                                <a href={{asset('img/icons/single-bed.png')}} target="_blank" class="max-w-[80px] max-h-[80px] block">
                                                    <img src={{asset('img/icons/single-bed.png')}} alt="single-bed" class="object-contain m-auto w-full h-full">
                                                </a>
                                            </td>
                                            <td>
                                                <div class="mt-2">
                                                    {{$type->name}}
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="bed_types[{{$type->name}}]" class="form-input" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                        {{-- apartment only --}}
                        <div class="hidden property-type-apartment">
                            <hr class="my-12 border-tdhh-brown" />
                            <div>
                                <div>
                                    Available rooms
                                </div>
                                <div class="mt-3 flex flex-col space-y-2">

                                    <div class="all-bedroom flex flex-col space-y-2">
                                        @foreach (range(1,10) as $index)
                                            <div data-modal-toggle="modal-bedroom" id="bedroom-{{$index}}" class="{{$index!==1?'hidden':''}} bedroom flex order-{{$index}} justify-between shadow border rounded p-4 cursor-pointer hover:bg-gray-100">
                                                <div>
                                                    <div class="bedroom-title">
                                                        Bedroom {{$index}}
                                                    </div>
                                                    <div class="text-fade text-xs bedroom-bed-count-text"></div>
                                                    @foreach ($bedTypes as $type)
                                                        <input type="hidden"name="bed_types[{{$type->name}}]" class="be-count single-bed-count-{{$index}}" value="0" />
                                                    @endforeach
                                                    <input type="hidden" name="double-bed-count" class="be-count double-bed-count-{{$index}}" value="0" />
                                                    <input type="hidden" name="large-bed-count" class="be-count large-bed-count-{{$index}}" value="0" />
                                                    <input type="hidden" name="extralarge-bed-count" class="be-count extralarge-bed-count-{{$index}}" value="0" />
                                                </div>
                                                <div class="flex space-x-3">
                                                    @if($index !== 1)
                                                        <div title="Remove bedroom" onclick="event.stopPropagation()" class="remove-bedroom w-10 h-10 flex-shrink-0 flex justify-center items-center rounded-md bg-gray-200 hover:bg-red-500 group">
                                                            <span class="fa fa-times text-xl group-hover:text-white"></span>
                                                        </div>
                                                    @endif
                                                    @if($index !== 1)
                                                        <div class="flex-shrink-0 flex items-center">
                                                            <span class="fa fa-angle-right text-2xl"></span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div data-modal-toggle="modal-livingroom" class="flex justify-between shadow border rounded p-4 cursor-pointer hover:bg-gray-100">
                                        <div>
                                            <div>
                                                Living rooms (<span class="livingroom-qty-count"></span>)
                                            </div>
                                            <div class="text-fade text-xs">
                                                <span class="sofa-bed-count"></span> sofa-bed(s)
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="fa fa-angle-right text-2xl"></span>
                                        </div>
                                    </div>

                                    <div data-modal-toggle="modal-bathroom" class="flex justify-between shadow border rounded p-4 cursor-pointer hover:bg-gray-100">
                                        <div>
                                            <div>
                                                Bathrooms (<span class="bathroom-qty-count"></span>)
                                            </div>
                                            <div class="text-fade text-xs">
                                                Yes
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="fa fa-angle-right text-2xl"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="button" class="add-bedroom mt-2 border-b border-transparent hover:border-black">
                                            <span class="fa fa-plus-square mr-1"></span>
                                            Add bedroom
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <hr class="my-12 border-tdhh-brown" />


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <div class="form-group">
                                    <label>
                                        Max guest count
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="number" name="max_guest" class="form-input" />
                                </div>
                            </div>
                            <div class="hidden property-type-room">
                                <div class="form-group">
                                    <label>
                                        Room size
                                    </label>
                                    <div class="flex">
                                        <input type="number" name="room_size" class="form-input w-[50%]" />
                                        <select name="room_size_measurement" id="" class="form-input w-[50%] ml-0.5" style="border-left: none !important;">
                                            <option value="" selected disabled>Select</option>
                                            <option value="sqm">Sqm</option>
                                            <option value="inch">Inch</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="hidden property-type-room">
                            <hr class="my-12 border-tdhh-brown" />

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <div>
                                    <div class="form-group">
                                        <label>
                                            Bathroom type
                                            <span class="form-input-required">*</span>
                                        </label>
                                        <select name="bathroom_type" id="" class="form-input">
                                            <option value="" selected disabled>Select</option>
                                            <option value="shared">Shared bathroom</option>
                                            <option value="private">Private bathroom</option>
                                        </select>
                                    </div>
                                </div>
                                <div>

                                </div>
                            </div>
                        </div>


                        <div class="hidden property-type-room">

                            <hr class="my-12 border-tdhh-brown" />

                            <div>
                                Bathroom items
                            </div>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($bathroomItems as $item)
                                    <label class="cursor-pointer">
                                        <div class="flex space-x-3">
                                            <div class="mt-0.5 flex">
                                                <input type="checkbox" name="bathroom_items[][item_id]"  id="{{$item->id}}" value="{{$item->id}}" class="form-input-checkbox">
                                            </div>
                                            <div class="my-auto">
                                            {{$item->name}}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>


                        <hr class="my-12 border-tdhh-brown" />


                        <div class="flex space-x-4 justify-end">
                            <a href="/partners/property/create/123/options" class="btn btn-transparent-black">
                                <span class="fa fa-angle-left mr-2"></span>
                                Back
                            </a>
                            <button class="btn btn-orange">
                                Save & continue
                                <span class="fa fa-angle-right ml-2"></span>
                            </button>
                        </div>



                    </div>
                </div>

            </div>

        </div>
    </form>
@endsection

