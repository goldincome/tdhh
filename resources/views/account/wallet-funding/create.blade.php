@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    My Wallet - Customer Name
@endsection


@section('content')
    <div>

        <div class="top-wide">
            <div>
                <div>
                    Add Fund To My Wallet
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/account">Account</a>
                    <span>/</span>
                    <a>Add Fund</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="container py-8 sm:py-12 flex">

            <div class="w-3/12 flex-shrink-0 hidden lg:block">
                @include("common.account-sidebar", ["page" => "wallet-funding"])
            </div>

            <div class="w-9/12 flex-grow lg:pl-8 lg:border-l">
                @include('layouts.errors-and-messages')

                <div class="flex justify-start">
                    <a href="/account/wallet-funding" class="btn btn-transparent-black btn-md">
                        <span class="fa fa-angle-left mr-2"></span>
                        Back
                    </a>
                </div>

                <div class="mt-8 pt-8 border-t">
                    <div class="note text-center" style="background: #faa !important;">
                        You are required to use <span class="font-bold">{{auth('web')->user()->reference}}000222</span> as the narration / remark / description during your payment.
                        <br />
                        Payments with incorrect narration / remark / description will not be approved.
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-2">
                    @foreach(currency()->getCurrencies() as $currency)
                        @if($currency['code'] !== 'NGN')
                            <div class="p-4 box">
                                <div>
                                    {{$currency['name']}}
                                </div>
                                <div class="text-2xl font-bold">
                                    {{currency()->getCurrency('NGN')['symbol']}}{{$currency['exchange_rate']}} = 1{{$currency['symbol']}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="md:flex justify-between">
                    <form class="w-full md:w-1/2" method="post" action="{{route('front.account.wallet-funding.store')}}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-10 p-8 w-full max-w-md mx-auto md:mx-0 bg-gray-100 rounded-lg">

                            <div class="form-group">
                                <label>
                                    Select the bank account you paid into
                                    <span class="form-input-required">*</span>
                                </label>
                                <select name="bank_account_id" id="" class="form-input"  required>
                                    <option value="">Select</option>
                                    @foreach ($bankAccounts as $bankAccount)
                                        <option value="{{$bankAccount->id}}">{{$bankAccount->bank_name}}({{$bankAccount->account_number}})</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label>
                                    Depositor Name (Name you paid in with)
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="depositor_name" placeholder="" class="form-input" value="{{old('depositor_name')}}" required/>
                            </div>

                            <div class="form-group">
                                <label>
                                    Amount Paid
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="text" name="amount_paid" placeholder="" class="form-input" value="{{old('amount_paid')}}" required/>
                            </div>

                            <div class="form-group">
                                <label>
                                    Date Paid
                                    <span class="form-input-required">*</span>
                                </label>
                                <input type="date" name="date_paid" placeholder="" class="form-input"value="{{old('date_paid')}}" required/>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    Upload Payment Evidence
                                    <span class="form-input-required">*</span>
                                </label>
                                <input name="teller_image" type="file" accept="image/*" class="form-input" required />
                            </div>

                            <label class="cursor-pointer">
                                <div class="flex space-x-3">
                                    <div class="mt-0.5 flex">
                                        <input type="checkbox" required class="form-input-checkbox">
                                    </div>
                                    <div class="my-auto">
                                        I used {{auth('web')->user()->reference}}000222 as the narration / remark / description in my payment
                                    </div>
                                </div>
                            </label>

                            <button type="submit" class="btn btn-lg btn-block btn-tdhh-orange mt-8">
                                Submit
                            </button>

                        

                        </div>

                    </form>

                    <div class="w-full md:w-1/2 mt-8 pl-4 md:pl-8 md:ml-8 border-l">

                        <div class="font-bold">Our Bank Accounts</div>
                        <div class="text-fade">
                            Deposit into any of our bank account below and fill the form on your left to be credited to your Wallet
                        </div>
                        <div class="mt-8">
                            @foreach($bankAccounts as $bankAccount)
                                <div class="flex justify-start">
                                    <div class="w-4 h-4 bg-tdhh-orange mt-1 mr-4 rounded-full"></div>
                                    <div>
                                        Bank: {{$bankAccount->bank_name}}<br />
                                        Account Name: {{$bankAccount->account_name}}<br />
                                        Account Number: {{$bankAccount->account_number}}<br />
                                        {{$bankAccount->routing_number ? 'Routing Number: '.$bankAccount->routing_number : 'Swift Code: '.$bankAccount->swift_code }} <br />
                                        Currency: {{config('currency.acceptable_currency')[$bankAccount->currency_code]}} ({{$bankAccount->currency_code}})<br />
                                        Country: {{$bankAccount->country->name}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection