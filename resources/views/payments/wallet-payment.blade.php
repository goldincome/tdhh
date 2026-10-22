<div class="col-sm-6">
    
    @if(isset($is_freight) && $is_freight == 1)
        <form action="{{ route('freight-wallet.index') }}">
            <input type="hidden" name="is_freight" value="{{$is_freight}}">
            <input type="hidden" name="freightServiceId" value="{{$freightItems['freightId']}}">
            <input type="hidden" class="address_id" name="billing_address" value="{{$addressIds['deliveryAddressId']}}">
            <input type="hidden" class="rate" name="rate" value="">
            <input type="hidden" name="shipment_obj_id" value="{{ $shipment_object_id }}">
            <button type="submit" class="payment-wallet">
                <div>
                    <span class="fa fa-angle-right"></span>
                </div>
                <div>
                    <img src="{{ asset('img/payment/wallet.jpg') }}" alt="TD Holiday Homes Wallet" class="dp-contain">
                </div>
                <div>
                    <div>
                        Wallet
                    </div>
                    <div>
                        ({{currency($customer->wallet_balance, NULL, currency()->getUserCurrency())}})
                    </div>
                </div>
            </button>
        </form>
    @elseif(isset($isAppointmentPayment) && $isAppointmentPayment == true)
       <form action="{{ route('appointment.wallet-payment.index') }}">
            <input type="hidden" name="appointment_id" value="{{$appointment->appointment_id}}" id="">
            <button type="submit" class="payment-wallet">
                <div>
                    <span class="fa fa-angle-right"></span>
                </div>
                <div>
                    <img src="{{ asset('img/payment/wallet.jpg') }}" alt="TD Holiday Homes Wallet" class="dp-contain">
                </div>
                <div>
                    <div>
                        Wallet
                    </div>
                    <div>
                        ({{currency($customer->wallet_balance, NULL, currency()->getUserCurrency())}})
                    </div>
                </div>
            </button>
        </form>
    @elseif(isset($extraPayment) && $extraPayment == true)
        <form action="{{ route('wallet-payment.index') }}">
            @if($agent)
                <input type="hidden" name="agent_id" value="{{$agent->id}}">
            @endif
            <input type="hidden" class="address_id" name="billing_address" value="{{$addressIds['deliveryAddressId']}}">
            <input type="hidden" class="rate" name="rate" value="">
            <input type="hidden" name="orderId" value="{{ isset($order->id) ? $order->id : null }}"/>
            <input type="hidden" name="extraPayment" value="{{ isset($extraPayment) ? $extraPayment : null }}"/>
            <button type="submit" class="payment-wallet">
                <div>
                    <span class="fa fa-angle-right"></span>
                </div>
                <div>
                    <img src="{{ asset('img/payment/wallet.jpg') }}" alt="TD Holiday Homes Wallet" class="dp-contain">
                </div>
                <div>
                    <div>
                        Wallet
                    </div>
                    <div>
                        ({{currency($customer->wallet_balance, NULL, currency()->getUserCurrency())}})
                    </div>
                </div>
            </button>
        </form>

    @else
        <form action="{{ route('front.cart.store') }}" method="post" id="wallet-payment" name="wallet-payment">
           @csrf
            <input type="hidden" name="payment" value="{{ config('wallet-payment.name') }}">
            <input type="hidden" name="guest_name" id="gn2">
            <input type="hidden" name="guest_email" id="ge2">
            <input type="hidden" name="guest_phone" id="gp2">
            <input type="hidden" name="guest_car_parking" id="gcp2">
            <input type="hidden" class="address_id" name="billing_address" value="255 ideya street">
            <button type="submit"  class="wallet-click w-full h-20 border-2 px-4 py-2 rounded-md hover:border-tdhh-orange cursor-pointer">
                <div class="flex justify-start">
                    <div class="w-14 h-14 p-3 mr-3 flex items-center justify-center flex-shrink-0 bg-black rounded-full">
                        <span class="fa fa-wallet text-3xl text-tdhh-orange"></span>
                    </div>
                    <div class="flex-grow flex items-center text-left">
                        <div>
                            Pay with your
                            <br>
                            <span class="font-bold">TDHH Wallet</span>
                        </div>
                        {{-- 
                        <div class="text-lg font-bold">
                            (£{{$walletBalance}})
                        </div>
                        --}}
                    </div>
                </div>
            </button>

            {{-- <button type="submit" class="payment-wallet">
                <div>
                    <span class="fa fa-angle-right"></span>
                </div>
                <div>
                    <img src="{{ asset('img/payment/wallet.jpg') }}" alt="TD Holiday Homes Wallet" class="dp-contain">
                </div>
                <div>
                    <div>
                        Wallet
                    </div>
                    <div>
                        ({{currency($customer->wallet_balance, NULL, currency()->getUserCurrency())}})
                    </div>
                </div>
            </button> --}}
            <!--
            <button type="submit">
                <div>
                    <span class="fa fa-building" style="margin-bottom: 3px;"></span>
                </div>
                <div>
                    <div>
                        <span class="fa fa-angle-right"></span>
                    </div>
                    {{-- {{ ucwords($payment['name']) }} --}}
                    <br>
                    {{-- ({{currency($customer->wallet_balance, NULL, currency()->getUserCurrency())}}) --}}
                </div>
            </button>
            -->
        
        </form>
    @endif
</div>