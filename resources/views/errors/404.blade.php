@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    404 not found
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
                
            </div>
            <div>
                <span>
                    
                </span>
            </div>
        </div>
        <div></div>
    </div>

    <div class="container py-8 sm:py-12">
        <div class="mx-auto my-10 max-w-sm">


            <div class="mx-auto text-center">
                <div class="h-28">
                    <span class="fa fa-unlink text-8xl" aria-hidden="true"></span>
                </div>
                <div class="mt-4 font-semibold text-2xl">
                    Page not found!
                </div>
                <div class="mt-4 text-gray-500">
                    Sorry, the page you tried to access does not exist on our servers.
                </div>
                <div class="mt-4">
                    <a href="/" class="btn btn-orange btn-block">
                        Goto homepage
                    </a>
                </div>
            </div>


        </div>
    </div>

@endsection