@if(isset($payment['name']))

    @if($payment['name'] == config('stripe.name'))
        @include('payments.stripe')
    @elseif($payment['name'] == config('paypal.name') && currency()->getUserCurrency()!=='NGN')
        {{-- @if(currency()->getUserCurrency() != 'NGN') --}}
        
            @include('payments.paypal')
        {{-- @endif --}}
    @elseif($payment['name'] == config('paystack.name') && currency()->getUserCurrency()==='NGN')
        {{-- @if(currency()->getUserCurrency() == 'NGN') --}}
            @include('payments.paystack')
        {{-- @endif --}}
    @elseif($payment['name'] == config('wallet-payment.name'))
        @include('payments.wallet-payment')
    {{--@elseif($payment['name'] == config('bank-transfer.name'))
        @include('payments.bank-transfer') --}}
    @endif
@endif