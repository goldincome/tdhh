@vite('resources/js/app.js')


@extends('layouts.auth')


@section('title')
    Forgot password
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
                    <span class="fa fa-check-circle text-9xl text-green-500"></span>
                </div>
                <div class="mt-0 font-semibold text-2xl">
                    Password reset successful
                </div>
                <div class="mt-4 text-gray-500">
                    Your password has been reset successfully.
                    You can now login to access your account.
                </div>
                <a href="/partners/login" class="btn btn-lg btn-block btn-tdhh-orange mt-8">Back to Login</a>
            </div>


        </div>
    </div>
@endsection