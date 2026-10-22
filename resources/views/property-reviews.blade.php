@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Reviews - #1234567890 - Property Name
@endsection

@section('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet"> --}}
@endsection


@section('js')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                   Reviews
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/property/12345">This Property</a>
                    <span>/</span>
                    <a>Reviews</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container max-w-4xl mx-auto py-8 sm:py-12">
            <div class="flex justify-between">
                <a href="/property/12345" class="btn btn-transparent-black btn-sm">
                    <span class="fa fa-angle-left mr-2"></span>
                    Back
                </a>
            </div>

            <div class="mt-6">
                <div class="flex space-x-2">
                    <a href="/property/12345" target="_blank" class="w-36 sm:w-48 h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                        <img src="" alt="tdhh" class="object-cover m-auto" />
                    </a>
                    <div class="md:my-auto">
                        <div class="md:h-16">
                            <a href="/property/12345" target="_blank" class="font-bold hover:underline ellipsis-2-lines">
                                3 bedroom full suite
                            </a>
                            <div class="mt-1 text-fade text-xs">
                                <span class="fa fa-map-marker mr-1"></span>
                                address, property_location, postal_code, city, state, country
                            </div>
                        </div>
                        <div class="mt-1 flex items-center space-x-4 text-sm">
                            <div>
                                <span class="fa fa-bed"></span>
                                2
                            </div>
                            <div>
                                <span class="fa fa-shower"></span>
                                3
                            </div>
                            <div class="hidden sm:flex flex-wrap">
                                <a href="/property/12345" target="_blank" class="btn btn-transparent-black btn-sm mr-3">View property</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 mt-8 border-t overflow-auto">
                <div class="w-full sm:flex sm:space-x-6">
                    <div class="sm:w-2/5">

                        <div class="text-sm grid grid-cols-1 gap-6">
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

                    </div>

                    <div class="pt-6 sm:pt-0 mt-10 sm:mt-0 sm:w-3/5 border-t sm:border-t-0 sm:border-l">
                        
                        <div class="text-sm">
                            @foreach (range(1,8) as $index)
                                <div class="sm:pl-6 pb-6 mb-6 border-b border-gray-200">
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
                            @endforeach
                        </div>

                    </div>
                </div>

                <div class="mt-10 border-t border-gray-200">
                    {{-- @include('common.pagination') --}}
                </div>
            </div>

        </div>
    </div>
@endsection
