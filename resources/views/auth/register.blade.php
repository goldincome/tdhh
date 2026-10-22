@vite('resources/js/app.js')


@extends('layouts.auth')


@section('title')
    Register
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

            <div class="font-semibold text-2xl sm:text-3xl text-center md:text-left">
                Register
            </div>

            <div>
                <form method="post" action="{{route('user.register.post')}}">
                    @csrf

                    <div class="mt-10 w-full max-w-md mx-auto md:mx-0 lg:w-96">

                        <div class="form-group">
                            <label>
                                First Name
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="text" name="first_name" placeholder="" class="form-input" value="{{old('first_name')}}" required/>
                        </div>
                        <div class="form-group">
                            <label>
                                Last Name
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="text" name="last_name" placeholder="" class="form-input" value="{{old('last_name')}}" required/>
                        </div>

                        <div class="form-group">
                            <label>
                                Email Address
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="email" name="email" placeholder="" class="form-input" value="{{old('email')}}" required/>
                        </div>

                        <div class="form-group">
                            <label>
                                Phone number
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="tel" name="phone" placeholder="" class="form-input"value="{{old('phone')}}" required/>
                        </div>

                        <div class="form-group">
                            <label>
                                Country
                                <span class="form-input-required">*</span>
                            </label>
                            <select name="country_id" id="" class="form-input"  required>
                                <option value="">Select country</option>
                                @foreach ($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach

                            </select>
                        </div>


                        <div class="form-group">
                            <label>
                                Password
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
                            Register
                        </button>

                        <div class="mt-3 text-sm text-gray-500">
                            Already have an account?
                            <a href="/login" class="font-semibold text-tdhh-orange hover:underline">Login</a>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>

@endsection
