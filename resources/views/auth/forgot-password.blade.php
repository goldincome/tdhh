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

            <div class="font-semibold text-2xl sm:text-3xl text-center md:text-left">
                Forgot password
            </div>

            <div>
                <form method="get" action="/forgot-password/success">
                    @csrf

                    <div class="mt-10 w-full max-w-md mx-auto md:mx-0 lg:w-96">

                        <div class="form-group">
                            <label>
                                Email Address
                                <span class="form-input-required">*</span>
                            </label>
                            <input type="email" name="email" placeholder="email@example.com" class="form-input" />
                        </div>

                        <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange mt-8">
                            Send password reset link
                        </button>

                        <div class="mt-3 text-sm text-gray-500">
                            Remember your password?
                            <a href="/login" class="font-semibold text-tdhh-orange hover:underline">Back to login</a>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>

@endsection