@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Edit Profile - Olawale Lawal
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
                    Edit Profile
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a href="/account/profile">Profile</a>
                    <span>/</span>
                    <a>Edit</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "profile"])
            </div>
            
            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                
                @include('layouts.errors-and-messages')

                <div class="flex justify-start">
                    <a href="/account/profile" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                </div>
                <div class="mt-8 pt-8 border-t">

                    <div>
                        <form method="post" action="{{route('front.account.profile.update',auth()->user()->id)}}">
                            @csrf

                            <div class="w-full max-w-md p-8 bg-gray-100 rounded-lg">

                                <div class="form-group">
                                    <label>
                                        First Name
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="text" name="first_name" value="{{auth()->user()->first_name}}" class="form-input"  required/>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Last Name
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="text" name="last_name" value="{{auth()->user()->last_name}}" class="form-input"  required/>
                                </div>
    
                                <div class="form-group">
                                    <label>
                                        Email Address
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{auth()->user()->email}}" disabled="disabled" class="form-input"  required/>
                                </div>
    
                                <div class="form-group">
                                    <label>
                                        Phone number
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <input type="tel" name="phone" value="{{auth()->user()->phone}}" class="form-input" required/>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Country
                                        <span class="form-input-required">*</span>
                                    </label>
                                    <select name="country_id" id="" class="form-input"  required>
                                        <option value="">Select country</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" 
                                                @if(isset(auth()->user()->country->id) && auth()->user()->country->id == $country->id) selected @endif
                                            >
                                                {{$country->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange mt-8">
                                    Edit profile
                                </button>

                            </div>

                        </form>
                    </div>
                    
                </div>

            </div>
        </div>

    </div>
@endsection