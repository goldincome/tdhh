@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Account - {{auth()->user()->first_name}} {{auth()->user()->last_name}}
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Account
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a>Account</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-full lg:w-3/12 flex-shrink-0">
                @include("common.account-sidebar", ["page" => ""])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l hidden lg:block">
                @include("common.account-profile")
            </div>
        </div>

    </div>
@endsection