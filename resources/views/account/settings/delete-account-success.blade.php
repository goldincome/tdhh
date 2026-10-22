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

        <div class="container py-8 sm:py-12">

            <div>
                @include('layouts.errors-and-messages')

                <div class="py-20 mx-auto text-center">


                    
                    <div class="max-w-lg mx-auto">
                        <div class="h-24">
                            <span class="fa fa-user-times text-red-500" style="font-size: 80px;"></span>
                        </div>
                        <div class="pt-6 text-2xl font-bold">
                            Account Deleted
                        </div>
                        <div class="mt-2 text-fade">
                            Your account has been permanently deleted. You can create a new account anytime, by signing up.
                        </div>
                        <div class="mt-4 flex justify-center">
                            <a href="/" class="btn btn-tdhh-orange">
                                Goto homepage
                            </a>
                        </div>
                    </div>



                    
                </div>
            </div>
        </div>
    </div>
@endsection