@vite('resources/js/app.js')


@extends('layouts.app')


@section('title')
    #123456789012 - Checkout - Wallet
@endsection


@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection


@section('content')
    <div>

        <div class="top-wide" style="height: 14rem;">
            <div style="margin-top: 36px;">
                <div>
                    Pay with Wallet
                </div>
                <div>
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="{{route('front.cart.index')}}">Checkout</a>
                    <span>/</span>
                    <a>Wallet</a>
                </div>
            </div>
            <div></div>
        </div>

        <div class="-mt-24 mb-8 sm:mb-12 container bg-white py-8 sm:py-12 relative z-20">
            <div class="lg:px-4">

                <div class="lg:flex">
                    <div class="lg:w-2/3 min-h-full">
                        <div class="lg:mr-10 lg:pr-10 min-h-full lg:border-r border-gray-300">
                            
                            <div class="font-bold lg:text-xl">
                                <a href="/reservation" class="inline-block mr-4">
                                    <span class="fa fa-angle-left"></span>
                                </a>
                                Pay with wallet
                            </div>

                            <div class="mt-6">

                                <div class="sm:flex sm:space-x-6">
                                    <div class="sm:w-64 p-6 flex items-center justify-center flex-shrink-0 text-center border-2 rounded-md">
                                        <div>
                                            <div>
                                                Wallet current balance
                                            </div>
                                            <div class="mt-1 text-3xl font-bold">
                                                £{{$walletBalance}}
                                            </div>
                                        </div>
                                    </div>
                                    <div>


                                        @if($walletBalance >= $cartItems->getCartTotal())
                                            <form action="{{ route('front.wallet.checkout.success') }}" method="get" name="wallet">
                                                @csrf
                                                <input type="hidden" name="payment" value="wallet"/>
                                                <input type="hidden" name="billing_address" value="5555"/>
                                                <div class="mt-6 sm:mt-0 text-center sm:text-left">
                                                    <div class="text-green-600 text-lg font-bold">
                                                        <span class="fa fa-check-circle mr-1"></span>
                                                        Sufficient funds
                                                    </div>
                                                    <div class="mt-2">
                                                        The current balance in your wallet is sufficient enough to perform this transaction. Please click the button below to proceed
                                                    </div>
                                                    <div class="mt-6 flex space-x-2">
                                                        <button type="submit" class="btn btn-tdhh-orange mx-auto sm:mx-0">
                                                            Make Payment
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @else

                                        
                                            <div class="mt-6 sm:mt-0 text-center sm:text-left">
                                                <div class="text-red-600 text-lg font-bold">
                                                    <span class="fa fa-exclamation-circle mr-1"></span>
                                                    Insufficient funds
                                                </div>
                                                <div class="mt-2">
                                                    Sorry, the current balance in your wallet is not sufficient enough to perform this transaction. Please fund your wallet in order to proceed with this transaction.
                                                </div>
                                                <div class="mt-6 flex space-x-2">
                                                    <a href="{{route('front.account.wallet-funding.create')}}"  class="btn btn-tdhh-orange mx-auto sm:mx-0 disabled">
                                                        Fund Wallet
                                                    </a>
                                                    <button type="button" class="btn btn-transparent-black mx-auto sm:mx-0 disabled" disabled>
                                                        <span class="fa fa-exclamation-circle mr-2 text-gray-400"></span>
                                                        Pay with wallet
                                                    </button>
                                                </div>
                                            </div>
                                        @endif



                                    </div>
                                </div>

                                <hr class="my-12">

                                <div>
                                    <div>
                                        Other available payment options:
                                    </div>
                                    <div>
                                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            {{-- 
                                            @foreach($paymentGateways as $payment)
                                                @include('payment-options', ['payment', 'total','customer','addressIds'])
                                            @endforeach
                                             --}}

                                            <button type="submit" class="w-full h-20 border-2 p-4 rounded-md hover:border-tdhh-orange cursor-pointer">
                                                <img src="{{ asset('img/payment/paypal.jpg') }}" alt="PayPal" class="w-full h-full object-contain">
                                            </button>

                                            <button type="submit" class="w-full h-20 border-2 p-4 rounded-md hover:border-tdhh-orange cursor-pointer">
                                                <img src="{{ asset('img/payment/paystack.jpg') }}" alt="PayStack" class="w-full h-full object-contain">
                                            </button>


                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    
                    <div class="lg:w-1/3 mt-10 lg:mt-0 pt-10 lg:pt-0 lg:flex-shrink-0 border-t lg:border-transparent">
                        <div class="font-bold lg:text-xl">
                            Property
                        </div>

                        <div class="p-4 mt-6 border rounded-lg">
                            @foreach($cartItems->getCartItems() as $item)
                                <div>
                                    <div>
                                        <a href="{{route('front.details', $item->id)}}" target="_blank" class="h-24 sm:h-28 mr-2 flex flex-shrink-0 bg-black rounded-md overflow-hidden">
                                            <img src="{{asset($item->associatedModel->cover_image->getUrl())}}" alt="name" class="object-cover m-auto" />
                                        </a>
                                        <div class="mt-2">
                                            <div>
                                                <a href="{{route('front.details', $item->id)}}" target="_blank" class="font-bold hover:underline ellipsis-2-lines">
                                                    {{$item->associatedModel->name}}
                                                </a>
                                                <div class="mt-1 text-fade text-xs">
                                                    <span class="fa fa-map-marker mr-1"></span>
                                                    {{$item->associatedModel->address}}, {{$item->associatedModel->neighborhood}} 
                                                </div>
                                            </div>
                                            <div class="mt-1 flex space-x-4 text-sm">
                                                <div>
                                                    <span class="fa fa-bed"></span>
                                                    {{$item->associatedModel->bedroom_qty}}
                                                </div>
                                                <div>
                                                    <span class="fa fa-shower"></span>
                                                    {{$item->associatedModel->bathroom_qty}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-0.5">
                                    <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                        <div>
                                            {{currency_convert($cartItems->getCartSubtotal()/$item->attributes->quantity,$item->associatedModel->country->code,currency()->getUserCurrency())}} * {{$item->attributes->quantity}} nights
                                        </div>
                                        <div class="text-right">
                                            {{currency_convert($cartItems->getCartSubtotal(),$item->associatedModel->country->code,currency()->getUserCurrency())}}
                                        </div>
                                    </div>
                                    @if($item->associatedModel->is_cleaning_available)
                                        <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                            <div>
                                                Cleaning Fee ({{ $item->associatedModel->cleaning_type === 'per_stay' ? $item->associatedModel->cleaning_fee . '/stay' : $item->attributes->quantity.' nights'}})
                                            </div>
                                            <div class="text-right">
                                                {{currency_convert($item->attributes->cleaning_fee,$item->associatedModel->country->code,currency()->getUserCurrency())}}
                                                
                                            </div>
                                        </div>
                                    @endif
                                    @if($item->associatedModel->is_parking_charge_available  && $item->associatedModel->is_parking_charged && $item->attributes->guest_car_parking == 'yes')
                                    
                                        <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                            <div>
                                                Parking Fee will apply when parking
                                            </div>
                                            <div class="text-right">
                                                {{-- {{currency_convert($item->associatedModel->parking_fee,$item->associatedModel->country->code,currency()->getUserCurrency())}} --}}
                                            </div>
                                        </div>
                                    @endif
                                    
                                    {{-- <div class="px-3 py-3 bg-gray-100 flex justify-between">
                                        <div>
                                            VAT
                                        </div>
                                        <div class="text-right">
                                            £0
                                        </div>
                                    </div> --}}
                                    <div>
                                        <div class="pt-3 mt-5 border-t flex justify-between text-lg font-bold">
                                            <div>
                                                Total
                                            </div>
                                            <div class="text-right">
                                                {{currency_convert($cartItems->getCartTotal(),$item->associatedModel->country->code,currency()->getUserCurrency())}}
                                            </div>
                                        </div>
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