@extends('layouts.admin')


@section('title')
    Edit Currency
@endsection


@section('css')
    <style>
        .admin-sidebar-finance{
            /* color: #000 !important; */
            background: #555 !important;
        }
    </style>
@endsection


@section('content')
    <div class="container">


        <div class="page-title-account">
            <div>
                Edit Currency
            </div>
            <div class="pull-right">
            </div>
        </div>

        <div class="mt-1 p-8 min-h-[400px] bg-white">
            <form method="post" action="{{route('admin.currency.update', $currency->id)}}" class="max-w-xl mx-auto" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>
                        currency name
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" disabled name="name" value="{{$currency->name}}" class="form-input" />
                </div>

                <div class="form-group">
                    <label>
                        currency name
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" disabled value="{{$currency->name}}" class="form-input" />
                </div>

                <div class="form-group">
                    <label>
                       symbol
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" disabled value="{{$currency->symbol}}" class="form-input" />
                </div>
                <div class="form-group">
                    <label>
                       Exchange Rate to Naira
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="exchange_rate" value="{{$currency->exchange_rate}}" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                       format
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" disabled name="name" value="{{$currency->format}}" class="form-input" required/>
                </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange"> Submit </button>
                </div>
            </form>
        </div>


    </div>
@endsection

