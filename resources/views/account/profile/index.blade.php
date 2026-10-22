@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    My Profile - Olawale Lawal
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
                    My Profile
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a>Profile</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "profile"])
            </div>
            
            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                @include("common.account-profile")
            </div>
        </div>

    </div>
@endsection