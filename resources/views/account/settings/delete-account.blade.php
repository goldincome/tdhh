@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Delete Account - Olawale Lawal
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        function confirmBox(){
            if (confirm('Proceeding will permanently delete your account. Are you sure you want to continue?')){
                $(".form").submit();
            }
        }
    </script>
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Delete Account
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a href="/account/settings">Settings</a>
                    <span>/</span>
                    <a>Delete Account</a>
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
                    <a href="/account/settings" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                </div>
                <div class="mt-8 pt-8 border-t">


                    
                    {{-- <form method="post" action="/account/settings/delete-account/success" class="form"> --}}
                    <form method="get" action="{{route('front.account.profile.account.destroy', auth()->user()->id)}}" class="form">
                        <div class="border p-6">

                            <div class="text-center font-bold text-md">
                                Are you sure you want to delete your account?
                            </div>

                            <div class="mt-6">
                                <div class="note inline-block">
                                    Please note:
                                </div>
                                <ul class="list-disc mt-4 ml-4">
                                    <li>
                                        This will permanently delete your account data from our platform.
                                    </li>
                                    <li class="mt-5">
                                        Your past, current and future bookings will be deleted
                                    </li>
                                    <li class="mt-5">
                                        Your wallet balance will no longer be accessible
                                    </li>
                                    <li class="mt-5">
                                        Your property reviews will appear as anonymous.
                                    </li>
                                    <li class="mt-5">
                                        You will not be able to login to your account anymore.
                                    </li>
                                    <li class="mt-5">
                                        You can create a new account later on, whenever you wish
                                    </li>
                                    <li class="mt-5">
                                        This process cannot be reversed
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-8 mt-8 border-t">

                                <div class="flex space-x-4 justify-end">
                                    <a href="{{route('front.account.profile.settings.index')}}" class="btn btn-transparent-black">
                                        Cancel
                                    </a>
                                    <button type="button" onclick="confirmBox()" class="btn text-white bg-red-500">
                                        Delete Account
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>



                    
                </div>
            </div>
        </div>
    </div>
@endsection