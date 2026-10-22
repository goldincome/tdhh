@extends('layouts.partner')


@section('title')
    Edit property - #09390309323223
@endsection


@section('css')
    <style>
        .admin-sidebar-property{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">

        <div class="page-title-account">
            <div>
                Edit property
                <a href="/partners/property/12345" target="_blank" class="hover:underline font-montserrat text-sm text-tdhh-orange">
                    #09390309323223
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
                    <a href="/partners/property/edit/123/options">
                        Property Options
                    </a>
                    <a href="/partners/property/edit/123/room">
                        Room Details
                    </a>
                    <div href="/partners/property/edit/123/preview" class="active">
                        Property Preview
                    </div>
                </div>
            </div>

            <div class="mt-1 p-8 min-h-[400px] bg-white">
                <form method="get" action="/partners/property/edit/123/success" class="max-w-xl mx-auto">


                    <div>
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
                                        ${{$property->todayPrice}}
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


                        <div class="py-8 text-justify leading-8 border-t border-gray-300">
                            {{$property->description}}
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
                                        {{$property->bathroom_qty}} bathroom
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


                        <div class="px-4 sm:px-8 py-4 bg-[#ff0000] bg-opacity-5">
                            <span class="font-bold">Not Allowed: &nbsp;</span>
                            @foreach ($property->houseRules as $houseRule)
                                {{$houseRule->rule->name}},
                            @endforeach
                        </div>

                    </div>


                    <hr class="my-12 border-tdhh-brown" />


                    <div class="flex space-x-4 justify-end">
                        <a href="/partners/property/edit/123/room" class="btn btn-transparent-black">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </a>
                        <button class="btn btn-orange">
                            <a href="{{route('partner.property.edit.success', $property->id)}}">Edit Property</a>
                            {{-- Edit property --}}
                            <span class="fa fa-check ml-2"></span>
                        </button>
                    </div>


                </form>
            </div>

        </div>

    </div>
@endsection

