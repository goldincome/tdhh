@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    My Booking Review - Olawale Lawal
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function(){

            function countStars(ele, count){
                var ele = ele;
                var index = count;
                ele.next().text("("+count+")");
                ele.next().next().val(count);
            }

            function pluralize(string, qty){
                if (qty != 1){
                    return (string + 's');
                }
                else{
                    return (string);
                }
            }


            $(".product-rating-new-js > div:nth-child(1) > div").hover(function(){
                $(this).addClass("scale-1");
                $(this).prevAll().addClass("scale-1");
            }, function(){
                $(this).removeClass("scale-1");
                $(this).prevAll().removeClass("scale-1");
            });

            // $(".product-rating-new-js > div:nth-child(1)").mouseleave(function(){
                // $(this).children().children().removeClass("scale-1");
            // });

            $(".product-rating-new-js > div:nth-child(1) > div").click(function(){
                $(this).parent().parent().parent().removeClass('error');
                // alert($(this).index() + 1);
                $(this).addClass("selected");
                $(this).prevAll().addClass("selected");
                $(this).nextAll().removeClass("selected");
                var count = ($(this).index() + 1);
                countStars($(this).parent(), count);
                // toastr.success("You rated " + $(this).parent().parent().prev().text().trim() + " " + count + " " + pluralize('star',count));
            });

            function checkRatingSelection(){
                var empty_value = 'n';
                $(".product-rating-new > div > div > div").removeClass('error');
                $(".hidden-rating-value").each(function(){
                    if ($(this).val().trim() == ''){
                        $(this).parent().parent().addClass('error');
                        empty_value = 'y';
                    }
                });
                if (empty_value == 'y'){
                    return false;
                }
                return true;
            }

            $(".form").submit(function(e){
                if (!checkRatingSelection()){
                    // toastr.error("Please select all your reactions for this product review");
                    alert("Please select all your reactions for this product review");
                    e.preventDefault();
                }
            });

        });
    </script>
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Add review
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a href="/account/bookings/12345">Booking</a>
                    <span>/</span>
                    <a>Add Review</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "bookings"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                @include('layouts.errors-and-messages')

                <div class="flex justify-start">
                    <a href="/account/bookings/12345" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                </div>

                <div class="mt-8 lg:pt-8 lg:border-t">
                    <form method="post" action="{{route('customer.review.submit', $reservation->id)}}" class="form border block p-4">
                        @csrf
                        <div class="mb-4">
                            <h5 class="text-sm font-bold text-center">
                                Please rate this property based on your experience during your stay
                            </h5>
                        </div>

                        <div class="note text-center mb-5">
                            Click the stars to rate this property
                            <div class="mt-1">
                                {{-- &nbsp; &nbsp; || &nbsp; &nbsp;  --}}
                                <span class="nowrap">
                                    1 <span class="fa fa-star"></span> = worst experience
                                </span>
                                &nbsp; &nbsp; || &nbsp; &nbsp;
                                <span class="nowrap">
                                    5 <span class="fa fa-star"></span> = best experience
                                </span>
                            </div>
                        </div>

                        <div class="product-rating-new">
                            <div class="grid grid-cols-2 xl:grid-cols-3 gap-4 text-sm">
                                @foreach ($ratingParams as $param)
                                    <div>
                                        <div class="p-4 border">
                                            <div>
                                                {{$param->name}}
                                            </div>
                                            <div class="product-rating-new-js">
                                                <div>
                                                    <div><span class="fa fa-star"></span></div>
                                                    <div><span class="fa fa-star"></span></div>
                                                    <div><span class="fa fa-star"></span></div>
                                                    <div><span class="fa fa-star"></span></div>
                                                    <div><span class="fa fa-star"></span></div>
                                                </div>
                                                <div class="overflow-hidden">
                                                    (0)
                                                </div>
                                                <input type="hidden" name="param[{{$param->id}}]" class="hidden-rating-value">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="form-group">
                                <label for="address">Please enter your review:</label>
                                <textarea name="review" rows="5" class="form-input" placeholder="Your review" style="height: 150px;" required></textarea>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange">
                                Submit Review
                            </button>
                            <a href="/account/bookings/12345" class="btn btn-transparent-black btn-block mt-2">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
