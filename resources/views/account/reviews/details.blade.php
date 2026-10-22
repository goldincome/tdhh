@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Reviews #{{$review->reservation->property->reference}} - {{$review->reservation->property->name}}
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Review #{{$review->reservation->property->reference}}
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a href="/account/reviews">Reviews</a>
                    <span>/</span>
                    <a>Review</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "reviews"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                @include('layouts.errors-and-messages')

                <div class="flex justify-between">
                    <a href="/account/reviews" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                    <a href="{{route('front.details', $review->reservation->property->id)}}" class="btn btn-tdhh-orange btn-md">
                        View property
                        <span class="fa fa-external-link ml-2"></span>
                    </a>
                </div>
                <div class="mt-8 pt-8 border-t">


                    <div>
                        <div class="sm:flex sm:justify-between">
                            <div class="font-bold">
                                Your ratings
                            </div>
                            <div class="mt-2 sm:mt-0">
                                <span class="text-tdhh-orange mr-1">
                                    <span class="fa fa-star"></span>
                                    {{$review->rating}}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 text-sm grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach ($review->ratings as $rating)
                                <div>
                                    <div>
                                        @php $ratingValue = $rating->rating/5*100 @endphp
                                        {{$rating->param->name}} <span class="font-bold">({{$rating->rating}})</span>
                                    </div>
                                    <div class="mt-1 h-4 bg-gray-200">
                                        <div class="h-full bg-tdhh-orange" style="width: {{$ratingValue}}%"></div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 sm:flex sm:justify-between">
                            <div class="font-bold">
                                Your reviews
                            </div>
                            <div class="mt-2 sm:mt-0"></div>
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
                                                {{$review->user->full_name}}
                                            </div>
                                            <div class="text-fade text-xs">
                                                {{$review->created_at->format('M d, Y')}}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-tdhh-orange mr-1">
                                            <span class="fa fa-star"></span>
                                            {{$review->rating}}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 leading-6">
                                   {{$review->review}}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
@endsection
