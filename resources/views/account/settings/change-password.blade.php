@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Change Password - Olawale Lawal
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
                    Change Password
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a href="/account/settings">Settings</a>
                    <span>/</span>
                    <a>Change Password</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "settings"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                @include('layouts.errors-and-messages')

                <div class="flex justify-start">
                    <a href="{{ url()->previous() }}" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                </div>
                <div class="mt-8 pt-8 border-t">

                    <div>
                        <form method="post" action="{{route('front.account.profile.password.reset', auth()->user()->id)}}">
                            @csrf

                            <div class="w-full max-w-md p-8 bg-gray-100 rounded-lg">

                                <div class="form-group">
                                    <label>
                                        Old Password
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="password" name="old_password" placeholder="" class="form-input" required/>
                                </div>

                                <div class="form-group">
                                    <label>
                                        New Password
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="password" name="password" placeholder="" class="form-input" required/>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Confirm Password
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" placeholder="" class="form-input" required/>
                                </div>

                                <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange mt-8">
                                    Change Password
                                </button>

                                <div class="mt-3 text-sm text-gray-500">
                                    Can't remember your password?
                                    <a href="/forgot-password" class="whitespace-nowrap font-semibold text-tdhh-orange hover:underline">Reset your password</a>
                                </div>

                            </div>

                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection