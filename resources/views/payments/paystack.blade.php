<div class="col-sm-6">
    
        <form action="{{ route('front.cart.store') }}" method="post" name="paystack">
        @csrf
        <input type="hidden" name="payment" value="{{ config('paystack.name') }}">
        <input type="hidden" class="address_id" name="billing_address" value="255 ideya street">
        {{-- <input type="hidden" class="delivery_address_id" id="delivery_address_id"  name="delivery_address" value="{{$addressIds['deliveryAddressId']}}"> --}}
        <input type="hidden" name="currency" value="NGN">
        <input type="hidden" name="orderID" value="345">
        <input type="hidden" name="email" value="ade@mail.com"> {{-- required --}}
        <input type="hidden" name="amount" value="{{(currency_convert($cartItems->getCartTotal(),$item->associatedModel->country->currency_code,'NGN', false) * 100)}}"> {{-- required in kobo --}}
        {{--<input type="hidden" name="metadata" value="{{ json_encode($array = ['billing_address' => $address->id, 'isAvailableInNigeria' => $isAvailableInNigeria, 
        'extraPayment' => $extraPayment == true ? $extraPayment : false, 'orderId' => isset($order->id) ? $order->id : false]) }}" >--}} {{-- For other necessary things you want to add to your payload. it is optional though --}}
        <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> {{-- required --}}
        <input type="hidden" name="key" value="{{ config('paystack.secretKey') }}"> {{-- required --}}
        <input type="hidden" name="guest_name" id="gn1">
        <input type="hidden" name="guest_email" id="ge1">
        <input type="hidden" name="guest_phone" id="gp1">
        <input type="hidden" name="guest_car_parking" id="gcp1">
        <button type="submit" class="paystack-click w-full h-20 border-2 p-2 rounded-md hover:border-tdhh-orange cursor-pointer">
            <img src="{{ asset('img/payment/paystack.jpg') }}" alt="Paystack" class="w-full h-full object-contain">
        </button>

        <!--
        <button>
            <div>
                <span class="fa fa-credit-card"></span>
            </div>
            <div>
                <div>
                    <span class="fa fa-angle-right"></span>
                </div>
                Pay with
                <br>
                {{-- {{ ucwords($payment['name']) }}  --}}
            </div>
        </button>
        -->
        </form>


</div>
  
