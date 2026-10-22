@vite('resources/js/app.js')


@extends('layouts.auth')


@section('title')
    Reset password
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
                Set new password
            </div>

            <div>
                <form method="get" action="/admin/reset-password/success">
                    @csrf

                    <div class="mt-10 w-full max-w-md mx-auto md:mx-0 lg:w-96">

                        <div class="form-group">
                            <label>
                                Email Address
                            </label>
                            <input type="email" name="email" value="email@example.com" class="form-input" disabled />
                        </div>

                        <div class="form-group">
                            <label>
                                Password
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="password" name="password" placeholder="" class="form-input" />
                        </div>

                        <div class="form-group">
                            <label>
                                Confirm Password
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="password" name="password" placeholder="" class="form-input" />
                        </div>

                        <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange mt-8">
                            Reset Password
                        </button>

                        <div class="mt-3 text-sm text-gray-500">
                            Already reset password?
                            <a href="/admin/login" class="font-semibold text-tdhh-orange hover:underline">Back to login</a>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>

@endsection