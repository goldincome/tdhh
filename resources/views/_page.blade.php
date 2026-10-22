@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    Page
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
                    Confirm Booking
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/property">Properties</a>
                    <span>/</span>
                    <a href="/property/12345">This property</a>
                    <span>/</span>
                    <a>Reserve</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12">

            

        </div>

    </div>
@endsection