@extends('layouts.admin')


@section('title')
    Bank Account
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
                Create Bank Account
            </div>
            <div class="pull-right">
            </div>
        </div>

        <div class="mt-1 p-8 min-h-[400px] bg-white">
            <form method="post" action="{{route('admin.bank-account.store')}}" class="max-w-xl mx-auto" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>
                       Country
                        <span class="form-input-required">*</span>
                    </label>
                    <select name="country_id" class="form-input" required id="">
                        <option disabled selected> -- Select --</option>
                        @foreach ($countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                       Bank Name
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="bank_name" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                      Account Name
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="account_name" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                      Account Number
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="account_number" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                      Currency Code
                        <span class="form-input-required">*</span>
                    </label>
                    <select name="currency_code" class="form-input" required id="">
                        <option disabled selected> -- Select --</option>
                        @foreach ($currencies as $currency)
                            <option value="{{$currency->code}}">{{$currency->code}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                      Bank Logo
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="image" type="jpg" name="bank_logo" class="form-input"  accept="image/png, image/gif, image/jpeg"  required/>
                </div>

                <div class="form-group">
                    <label>
                      Routing Number
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="routing_number" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                     Swift Code
                        <span class="form-input-required">*</span>
                    </label>
                    <input type="text" name="swift_code" class="form-input" required/>
                </div>

                <div class="form-group">
                    <label>
                    Status
                        <span class="form-input-required">*</span>
                    </label>
                    <select name="status" class="form-input" required id="">
                        <option disabled selected> -- Select --</option>
                        <option value="1">Active</option>
                        <option value="0">Disable</option>

                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange"> Submit </button>
                </div>
            </form>
        </div>


    </div>
@endsection

