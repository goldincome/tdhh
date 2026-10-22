@vite('resources/js/app.js')

@extends('layouts.auth')


@section('title')
    Registration successful
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



            <div class="mt-8 mx-auto lg:mx-0 text-center max-w-sm">
                <div class="h-40">
                    <img src="{{asset('img/icons/confirm-mail.png')}}" alt="logo" class="h-full object-contain mx-auto" />
                </div>
                <div class="mt-0 font-semibold text-2xl">
                    Thank you for registering
                </div>
                <div class="mt-4 text-gray-500">
                    We have sent a confirmation email to
                    <span class="text-gray-900">{{auth('partner')->user()->email}}</span>.
                    <br />
                    Please confirm your email to be able to login.
                </div>
                <a href="/partners/login" class="btn btn-lg btn-block btn-tdhh-orange mt-8">Back to Login</a>
            </div>


        </div>
    </div>
@endsection
