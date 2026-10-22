@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Account Settings - Olawale Lawal
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
                    Account Settings
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a>Settings</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "settings"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                <div class="flex justify-between">
                    <div>
                        <a href="{{route('front.account.profile.settings.index')}}" class="lg:hidden btn btn-transparent-black btn-md">
                            <span class="fa fa-angle-left mr-2"></span>
                            Back
                        </a>
                    </div>
                </div>

                <div class="mt-8 lg:mt-0 border-t">
                    <div class="py-8 border-b md:flex md:justify-between">
                        <div>
                            <div class="font-bold">
                                Edit Profile
                            </div>
                            <div class="text-fade">
                                Update the account information such as name, phone number, etc
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{route('front.account.profile.edit')}}" class="btn btn-transparent-black btn-md">
                                Edit Profile
                                <span class="fa fa-angle-right ml-2"></span>
                            </a>
                        </div>
                    </div>
                    <div class="py-8 border-b md:flex md:justify-between">
                        <div>
                            <div class="font-bold">
                                Add funds to Wallet
                            </div>
                            <div class="text-fade">
                                Add money to fund your TDHH wallet and make reservations with your wallet balance
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{route('front.account.wallet-funding.create')}}" class="btn btn-transparent-black btn-md">
                                Add funds
                                <span class="fa fa-angle-right ml-2"></span>
                            </a>
                        </div>
                    </div>
                    <div class="py-8 border-b md:flex md:justify-between">
                        <div>
                            <div class="font-bold">
                                Change password
                            </div>
                            <div class="text-fade">
                                Change the login password for accessing your account
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{route('front.account.profile.password.form')}}" class="btn btn-transparent-black btn-md">
                                Change Password
                                <span class="fa fa-angle-right ml-2"></span>
                            </a>
                        </div>
                    </div>
                    <div class="py-8 border-b md:flex md:justify-between">
                        <div>
                            <div class="font-bold">
                                Delete Account
                            </div>
                            <div class="text-fade">
                                Delete your account and its associated data off TDHH platform
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="/account/settings/delete-account" class="btn btn-transparent-black btn-md">
                                Delete Account
                                <span class="fa fa-angle-right ml-2"></span>
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection