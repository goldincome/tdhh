@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Browse our apartments
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".slider-range").slider({
                range: true,
                orientation:"horizontal",
                min: 0,
                max: 500,
                values: [20, 900],
                step: 5,
                slide:function (event, ui) {
                    if (ui.values[0] == ui.values[1]) {
                        return false;
                    }
                    $(".min_price").val(ui.values[0]);
                    $(".max_price").val(ui.values[1]);
                }
            });




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
            
            guests_qty();
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
                                    <input name="" type="text" class="form-input adult-qty-input" value="{{isset($data->num_of_adult) ? intval($data->num_of_adult) : 0}}" />
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
                                    <input type="text" name="" class="form-input children-qty-input" value="{{isset($data->num_of_children) ? intval($data->num_of_children) : 0}}" />
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
                                    <input type="text" name="" class="form-input pets-qty-input" value="{{isset($data->num_of_pet) ? intval($data->num_of_pet) : 0}}" />
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



        
        <div id="defaultModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 bg-black bg-opacity-50 justify-center items-center h-full">
            <div class="relative inset-0 z-50 h-modal w-full md:h-full">
                <div class="relative px-4 w-full max-w-2xl h-full md:h-auto">
                    <div class="relative bg-white rounded-lg shadow">
                        <div class="flex justify-between items-start px-4 py-2 rounded-t border-b">
                            <div class="my-auto font-semibold">
                                Filter
                            </div>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-red-400 hover:text-white rounded text-sm p-1 ml-auto inline-flex items-center" data-modal-toggle="defaultModal">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                            </button>
                        </div>
                        <div class="p-6 space-y-6">
                            @include('common.filter')
                        </div>
                    </div>
                </div>
            </div>
        </div>    


        <div class="top-wide">
            <div>
                <div>
                    Browse our apartments
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a>Properties</a>
                </div>
            </div>
            <div></div>
        </div>


        <div class="container py-8 sm:py-12">

            <div class="flex">
                <div class="w-60 flex-shrink-0 hidden lg:block">

                    <div class="font-bold text-lg pb-4 mb-4 border-b border-gray-300">
                        Filter
                    </div>

                    @include('common.filter')

                </div>
                <div class="lg:ml-10 lg:pl-10 lg:border-l border-gray-300 flex-grow">

                    {{-- show only if search is true --}}
                    {{-- 
                    <div class="text-center">
                        <div class="page-title">
                            Search Results
                        </div>
                        <div class="text-fade">
                            12 results found
                        </div>
                    </div>
                     --}}

                    <div class="flex justify-between">
                        <div>
                            <div class="inline-block lg:hidden">
                                <button type="button" class="btn btn-transparent-black btn-sm" data-modal-toggle="defaultModal">
                                    Filter
                                </button>
                            </div>
                            <div class="hidden lg:inline-block relative top-2">
                                @if($properties->total() > 0)
                                    Showing {{ $properties->firstItem() }} - {{ $properties->lastItem() }} items out of {{ $properties->total() }}
                                @else
                                    No properties found
                                @endif
                            </div>
                        </div>
                        <div>
                            <div>
                                <button id="dropdownButton" data-dropdown-toggle="dropdown2" class="btn btn-transparent-black btn-sm" type="button">
                                    Sort
                                    <span class="fa fa-angle-down ml-2"></span>
                                </button>
                                <div id="dropdown2" class="dropdown-menu-nav hidden">
                                    <ul class="py-1" aria-labelledby="dropdownButton">
                                        <li>
                                            <a href="{{url()->full()}}{{str_contains(url()->full(), '?') ? '&' : '?'}}sort=" class="dropdown-menu-option">Default</a>
                                        </li>
                                        <li>
                                            <a href="{{url()->full()}}{{str_contains(url()->full(), '?') ? '&' : '?'}}sort=asc" class="dropdown-menu-option">Asc</a>
                                        </li>
                                        <li>
                                            <a href="{{url()->full()}}{{str_contains(url()->full(), '?') ? '&' : '?'}}sort=desc" class="dropdown-menu-option">Desc</a>
                                        </li>
                                        <li>
                                            <a href="{{url()->full()}}{{str_contains(url()->full(), '?') ? '&' : '?'}}sort=price-low" class="dropdown-menu-option">Lowest price first</a>
                                        </li>
                                        <li>
                                            <a href="{{url()->full()}}{{str_contains(url()->full(), '?') ? '&' : '?'}}sort=price-high" class="dropdown-menu-option">Highest price first</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- max of 12 --}}
                    <div class="mt-4 pt-6 border-t border-gray-300">

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                            @foreach($properties as $property)
                                @include('common.home-grid')
                            @endforeach
                           
                        </div>

                        <div class="mt-10 border-t border-gray-300">
                            @include('common.pagination')
                        </div>

                    </div>

                </div>
            </div>

        </div>


    </div>
@endsection