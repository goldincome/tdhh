<div class="col-sm-6">
    @if(isset($extraPayment) && $extraPayment == true)
    
      <form action="{{ route('extra-payment.paypal.store') }}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="orderId" value="{{ isset($order->id) ? $order->id : null }}"/>
          <input type="hidden" name="extraPayment" value="{{ isset($extraPayment) ? $extraPayment : null }}"/>
          <input type="hidden" name="payment" value="{{ config('paypal.name') }}">
          <input type="hidden" class="address_id" name="billing_address" value="{{$addressIds['billingAddressId']}}">
         
          <input type="hidden" class="courier" name="courier" value="">
          
          <button type="submit" class="paypal-click w-full h-20 border-2 p-2 rounded-md hover:border-tdhh-orange cursor-pointer">
            <img src="{{ asset('img/payment/paypal.jpg') }}" alt="PayPal" class="w-full h-full object-contain">
        </button>
      </form>
      
    @else
 
        <form action="{{ route('front.cart.store') }}" method="post" name="paypal">
    
            @csrf
            <input type="hidden" name="payment" value="{{ config('paypal.name') }}">
            <input type="hidden" class="address_id" name="billing_address" value="23 ideya street">
            <input type="hidden" class="delivery_address_id" name="delivery_address" value="">
            <input type="hidden" class="courier" name="courier" value="">
            <input type="hidden" name="guest_name" id="gn">
            <input type="hidden" name="guest_email" id="ge">
            <input type="hidden" name="guest_phone" id="gp">
            <input type="hidden" name="guest_car_parking" id="gcp">
            <button type="submit" class="paypal-click w-full h-20 border-2 p-2 rounded-md hover:border-tdhh-orange cursor-pointer">
                <img src="{{ asset('img/payment/paypal.jpg') }}" alt="PayPal" class="w-full h-full object-contain">
            </button>

            <!--
            <button  type="submit">
                <div>
                    <span class="fab fa-paypal"></span>
                </div>
                <div>
                    <div>
                        <span class="fa fa-angle-right"></span>
                    </div>
                    Pay with
                    <br>
                    {{-- {{ ucwords($payment['name']) }} --}}
                </div>
            </button>
            -->
        </form>
    @endif
</div>
