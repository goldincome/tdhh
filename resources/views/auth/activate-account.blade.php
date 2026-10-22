@vite('resources/js/app.js')


@extends('layouts.auth')


@section('title')
    Activate Account
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection


@section('content')

    <div class="w-full max-w-xl p-8 sm:p-14 mx-auto md:my-auto">
        <div class="pb-14 md:pt-20 lg:pl-4 lg:pr-4">

            <div>


                <div class="text-center">
                    <div class="h-28">
                        <span class="fa fa-times-circle text-8xl text-red-500" aria-hidden="true"></span>
                    </div>
                    <div class="mt-4 font-semibold text-2xl">
                        Invalid Access
                    </div>
                    <div class="mt-4 text-gray-500">
                        Sorry, your access token is invalid. Please contact us to request for a new activation link.
                    </div>
                    <div class="mt-4">
                        <a href="/login" class="btn btn-orange btn-block">
                            Back to login
                        </a>
                    </div>
                </div>


            </div>

        </div>
    </div>

@endsection
