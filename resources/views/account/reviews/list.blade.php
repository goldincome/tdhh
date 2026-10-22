@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    My Reviews - Olawale Lawal
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    My Reviews
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a>Reviews</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "reviews"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                <div class="flex justify-between">
                    <div>
                        <a href="/account" class="lg:hidden btn btn-transparent-black btn-md">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </a>
                    </div>
                </div>

                <div class="mt-8 lg:mt-0 table-responsive">
                    <table class="table table-auto table-border table-align">
                        <thead>
                            <tr>
                                <th class="w-[150px] md:w-[350px]">Property</th>
                                <th class="w-[150px]">Booked date</th>
                                <th>Your rating</th>
                                <th>Your review</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($reviews as $review)
                                <tr>
                                    <td>
                                        <div class="flex space-x-2">
                                            <a href="{{route('customer.review.detail', $review->id)}}" class="w-36 h-24 mr-2 hidden md:flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                                <img src=""  alt="tdhh" class="object-cover m-auto" />
                                            </a>
                                            <div class="md:my-auto">
                                                <div>
                                                    <a href="{{route('customer.review.detail', $review->id)}}" class="text-fade hover:underline">
                                                        221121212
                                                    </a>
                                                    <a href="{{route('customer.review.detail', $review->id)}}" class="font-bold hover:underline ellipsis-2-lines">
                                                        {{$review->reservation->property->name}}
                                                    </a>
                                                    <div class="mt-1 text-fade text-xs">
                                                        <span class="fa fa-map-marker mr-1"></span>

                                                        {{$review->reservation->property->city_name}}, {{$review->reservation->property->country_name}},
                                                    </div>
                                                </div>
                                                {{-- <div class="mt-1 flex space-x-4 text-sm">
                                                    <div>
                                                        <span class="fa fa-bed"></span>
                                                        {{$review->reservation->}},
                                                    </div>
                                                    <div>
                                                        <span class="fa fa-shower"></span>
                                                        2
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{$review->reservation->proposed_check_in_date->format('M d, Y')}}
                                        <br>
                                        <span class="text-fade">to</span>
                                        <br>
                                        {{$review->reservation->proposed_check_in_date->format('M d, Y')}}
                                    </td>
                                    <td>
                                        <div class="whitespace-nowrap">
                                            <span class="fa fa-star text-tdhh-orange"></span>
                                            <span>{{$review->rating}}</span>
                                        </div>
                                        <div class="mt-2 text-fade">
                                           {{$review->created_at->format('M d, Y')}}
                                        </div>
                                        @if($review->status)
                                            <div class="mt-2 label label-green">
                                                Published
                                            </div>
                                        @else
                                            <div class="mt-2 label label-grey">
                                                Pending
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="whitespace-normal mt-2 ellipsis-3-lines overflow-hidden" style="max-height: 65px;">
                                            {{$review->review}}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route('customer.review.detail', $review->id)}}" class="btn btn-transparent-black btn-sm">
                                            View
                                        </a>
                                    </td>

                                </tr>
                            @endforeach

                            @if (count($reviews) <= 0)
                            <tr>
                                <td colspan="6">
                                    <div class="table-info">
                                        <span class="fa fa-list"></span>
                                        <div class="italic mt-3 text-lg">
                                            No reviews yet
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
@endsection
